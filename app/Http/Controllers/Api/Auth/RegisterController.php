<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\RegistrationNotificationEvent;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use App\Notifications\RegistrationNotification;
use Illuminate\Support\Facades\DB;
use App\Traits\SMS;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    use SMS;

    public $select;
    public function __construct()
    {
        parent::__construct();
        $this->select = ['id', 'name', 'email', 'otp'];
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'code'    => 422
            ], 422);
        }

        $email = strtolower($request->input('email'));
        $userExists = User::where('email', $email)->first();

        if ($userExists) {
            return response()->json([
                'status'  => true,
                'message' => $userExists->otp_verified_at
                    ? 'Email registered and verified.'
                    : 'Email registered but Email not verified.',
                'data'    => [
                    'is_verified'   => $userExists->otp_verified_at ? 1 : 0,
                    'is_registered' => 1
                ],
            ], $userExists->otp_verified_at ? 200 : 422);
        }



        try {
            DB::beginTransaction();

            do {
                $slug = "user_" . rand(1000000000, 9999999999);
            } while (User::where('slug', $slug)->exists());

            $user = User::create([
                'name'               => $request->input('name'),
                'slug'               => $slug,
                'email'              => $email,
                'password'           => Hash::make($request->input('password')),
                'otp'                => rand(1000, 9999),
                'otp_expires_at'     => Carbon::now()->addMinutes(60),
                'status'             => 'active',
                'last_activity_at'   => Carbon::now()
            ]);

            DB::table('model_has_roles')->insert([
                'role_id'     => 4,
                'model_type'  => 'App\Models\User',
                'model_id'    => $user->id
            ]);

            $notiData = [
                'user_id' => $user->id,
                'title'   => 'User registered successfully.',
                'body'    => 'User registered successfully.'
            ];

            $admins = User::role('admin', 'web')->get();
            foreach ($admins as $admin) {
                $admin->notify(new RegistrationNotification($notiData));
                if (config('settings.reverb') === 'on') {
                    broadcast(new RegistrationNotificationEvent($notiData, $admin->id))->toOthers();
                }
            }

            $data = User::select($this->select)->find($user->id);

            Mail::to($user->email)->send(new OtpMail($user->otp, $user, 'Verify Your Email Address'));

            DB::commit();

            $token = auth('api')->login($user);

            return response()->json([
                'status'  => true,
                'message' => 'User registered successfully.',
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'User registration failed: ' . $e->getMessage(),
                'code'    => 500
            ], 500);
        }
    }

    public function VerifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp'   => 'required|digits:4',
        ]);
        try {
            $user = User::where('email', $request->input('email'))->first();

            $token = auth('api')->login($user);

            //! Check if email has already been verified
            if (!empty($user->otp_verified_at)) {
                return  Helper::jsonErrorResponse('Email already verified.', 409);
            }

            if ((string)$user->otp !== (string)$request->input('otp')) {
                return Helper::jsonErrorResponse('Invalid OTP code', 422);
            }

            //* Check if OTP has expired
            if (Carbon::parse($user->otp_expires_at)->isPast()) {
                return Helper::jsonErrorResponse('OTP has expired. Please request a new OTP.', 422);
            }

            //* Verify the email
            $user->otp_verified_at   = now();
            $user->otp               = null;
            $user->otp_expires_at    = null;
            $user->save();

            return response()->json([
                'status'     => true,
                'message'    => 'Email verification successful',
                'code'       => 200,
                'token' =>  $token,
                'data' => [
                     'id' => $user->id,
                'avatar' => $user->avatar,
                'name' => $user->name,
                'email' => $user->email,
                'user_info' => $user->user_info ? 1 : 0,
                'Payment_method' => $user->user_info?->payment_method ? 1 : 0,
                'is_nutration' => $user->nutration ? 1 : 0,

                ]
            ], 200);
        } catch (Exception $e) {
            return Helper::jsonErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function ResendOtp(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                return Helper::jsonErrorResponse('User not found.', 404);
            }

            if ($user->otp_verified_at) {
                return Helper::jsonErrorResponse('Email already verified.', 409);
            }

            $newOtp               = rand(1000, 9999);
            $otpExpiresAt         = Carbon::now()->addMinutes(60);
            $user->otp            = $newOtp;
            $user->otp_expires_at = $otpExpiresAt;
            $user->save();

            //* Send the new OTP to the user's email
            Mail::to($user->email)->send(new OtpMail($newOtp, $user, 'Verify Your Email Address'));

            return response()->json(
                [
                    'success' => true,
                    'message' => 'A new OTP has been sent to your email',
                    'otp' =>  $newOtp,
                   'code' => 200,

                ]
            );
        } catch (Exception $e) {
            return Helper::jsonErrorResponse($e->getMessage(), 200);
        }
    }
}
