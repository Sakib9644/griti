<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public $select;
    public function __construct()
    {
        parent::__construct();
        $this->select = ['id', 'name', 'email', 'avatar'];
    }

    public function RedirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function HandleProviderCallback($provider)
    {
        $data = Socialite::driver($provider)->stateless()->user();
        return $data;
    }

   public function SocialLogin(Request $request)
{
    $request->validate([
        'token'    => 'required',
        'provider' => 'required|in:google,facebook,apple',
    ]);

    try {
        $provider = $request->provider;

        // Get social user from token
        $socialUser = Socialite::driver($provider)->stateless()->userFromToken($request->token);

        if (!$socialUser) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        // Check if user exists (even if soft-deleted)
        $user = User::withTrashed()->where('email', $socialUser->getEmail())->first();

        if ($user && $user->deleted_at) {
            return response()->json([
                'status' => false,
                'message' => 'Your account has been deleted.',
            ], 410);
        }

        $isNewUser = false;

        // Create new user if not exists
        if (!$user) {
            $password = Str::random(16);

            // Handle Apple name fallback
            $name = $socialUser->getName() ?? "User_" . Str::random(8);

            $user = User::create([
                'name'              => $name,
                'email'             => $socialUser->getEmail(),
                'password'          => bcrypt($password),
                'avatar'            => $socialUser->getAvatar() ?? null,
                'status'            => 'active',
                'slug'              => now()->timestamp,
                'otp_verified_at'   => now(),
            ]);

            // Assign default role
            DB::table('model_has_roles')->insert([
                'role_id'    => 4,
                'model_type' => User::class,
                'model_id'   => $user->id,
            ]);

            $isNewUser = true;

            // Optional: Notify admins about new user
            /*
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new UserRegistrationNotification($user, "{$user->name} has joined the platform"));
            }
            */
        }

        // Login user and generate API token
        Auth::login($user);
        $token = auth('api')->login($user);

        // Prepare response data
        $data = [
            'id'             => $user->id,
            'avatar'         => $user->avatar,
            'name'           => $user->name,
            'email'          => $user->email,
            'user_info'      => $user->user_info ? 1 : 0,
            'Payment_method' => $user->user_info?->payment_method ? 1 : 0,
            'nutration'      => $user->nutration ? 1 : 0,
        ];

        return response()->json([
            'status'     => true,
            'message'    => 'User logged in successfully.',
            'code'       => 200,
            'token_type' => 'bearer',
            'token'      => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'data'       => $data,
            'is_new_user'=> $isNewUser
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong',
            'error'   => $e->getMessage(),
        ], 500);
    }
}

}
