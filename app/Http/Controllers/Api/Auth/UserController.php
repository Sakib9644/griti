<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Music;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $select;
    public function __construct()
    {
        parent::__construct();
        $this->select = ['id', 'name', 'email', 'avatar', 'otp_verified_at', 'last_activity_at'];
    }
    public function me()
    {
        $user = User::select($this->select)
            ->with([
                'user_info:user_id,age,current_weight,height,target_weight',
                'activeWorkouts.workout_list:id,title,image,calories,minutes'
            ])
            ->find(auth('api')->id());

        $activeWorkouts = $user->activeWorkouts;

        // Totals
        $totalWorkouts = $activeWorkouts->count();

        $totalCalories = $activeWorkouts->sum(function ($workout) {
            return $workout->workout_list->calories ?? 0;
        });

        $activeDays = $user->activeWorkouts
            ->map(function ($w) {
                return $w->created_at->format('Y-m-d'); // only date
            })
            ->unique()
            ->count();

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,

            // ✅ Everything inside user_info
            'user_info' => [
                'age' => $user->user_info->age ?? 0,
                'current_weight' => $user->user_info->current_weight ?? 0,
                'height' => $user->user_info->height ?? 0,
                'target_weight' => $user->user_info->target_weight ?? 0,

                'total_workoutsCompleted' => $totalWorkouts ?? 0,
                'total_calories_burned' => $totalCalories ?? 0,
                'active_days' =>     $activeDays ?? 0
            ],
        ];

        return Helper::jsonResponse(true, 'User details fetched successfully', 200, $data);
    }




   public function updateProfile(Request $request)
{
    // Validate incoming request
    $validatedData = $request->validate([
        'name' => 'required|string|max:100',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        'address' => 'nullable|string|max:255',

        // Age will be converted to date
        'age' => 'nullable|integer|min:0',
        'current_weight' => 'nullable|numeric|min:0',
        'height' => 'nullable|numeric|min:0',
        'target_weight' => 'nullable|numeric|min:0',
        'price' => 'nullable|numeric|min:0', // added to avoid SQL error
        'password' => 'nullable|string|min:6', // optional password
    ]);

    $user = auth('api')->user();

    // Handle password
    if (!empty($validatedData['password'])) {
        $validatedData['password'] = bcrypt($validatedData['password']);
    } else if (array_key_exists('password', $validatedData)) {
        unset($validatedData['password']);
    }

    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        if (!empty($user->avatar)) {
            Helper::fileDelete(public_path($user->getRawOriginal('avatar')));
        }
        $validatedData['avatar'] = Helper::fileUpload(
            $request->file('avatar'),
            'user/avatar',
            getFileName($request->file('avatar'))
        );
    } else {
        $validatedData['avatar'] = $user->avatar;
    }

    // Update main user fields
    $userFields = ['name', 'avatar', 'password', 'address'];
    $user->update(array_intersect_key($validatedData, array_flip($userFields)));

    // Prepare user_info data
    $userInfoFields = ['age', 'current_weight', 'height', 'target_weight', 'price'];
    $userInfoData = array_intersect_key($validatedData, array_flip($userInfoFields));

    // Convert age to date
    if (isset($userInfoData['age'])) {
        $userInfoData['age'] = \Carbon\Carbon::now()
            ->subYears($userInfoData['age'])
            ->format('Y-m-d');
    }

    // Ensure price is set to 0 if missing to avoid SQL error
    $userInfoData['price'] = $userInfoData['price'] ?? 0;

    // Update or create user_info
    if ($user->user_info) {
        $user->user_info->update($userInfoData);
    } else {
        $user->user_info()->create($userInfoData);
    }

    // Reload user with selected fields
    $data = User::select($this->select)->find($user->id);

    return Helper::jsonResponse(true, 'Profile updated successfully', 200, $data);
}


    public function updatepass(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth('api')->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return Helper::jsonResponse(true, 'Password updated successfully', 200);
    }




    public function updateAvatar(Request $request)
    {
        $validatedData = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);
        $user = auth('api')->user();
        if (!empty($user->avatar)) {
            Helper::fileDelete(public_path($user->getRawOriginal('avatar')));
        }
        $validatedData['avatar'] = Helper::fileUpload($request->file('avatar'), 'user/avatar', getFileName($request->file('avatar')));
        $user->update($validatedData);
        $data = User::select($this->select)->find($user->id);
        return Helper::jsonResponse(true, 'Avatar updated successfully', 200, $data);
    }

    public function delete()
    {
        $user = User::findOrFail(auth('api')->id());
        if (!empty($user->avatar) && file_exists(public_path($user->avatar))) {
            Helper::fileDelete(public_path($user->avatar));
        }
        Auth::logout('api');
        $user->delete();
        return Helper::jsonResponse(true, 'Profile deleted successfully', 200);
    }

    public function destroy()
    {
        $user = User::findOrFail(auth('api')->id());
        if (!empty($user->avatar) && file_exists(public_path($user->avatar))) {
            Helper::fileDelete(public_path($user->avatar));
        }
        Auth::logout('api');
        $user->forceDelete();
        return Helper::jsonResponse(true, 'Profile deleted successfully', 200);
    }


    public function height_weight(Request $request)
    {
        $user = auth('api')->user();

        // Update the fields
        $user->user_info->weight_unit = $request->weight_unit;
        $user->user_info->height_unit = $request->height_unit;
        $user->user_info->save(); // save user_info, not user

        // Return only the two fields
        $data = [
            'weight_unit' => $user->user_info->weight_unit,
            'height_unit' => $user->user_info->height_unit,
        ];

        return Helper::jsonResponse(true, 'User Info Updated successfully', 200, $data);
    }
    public function get_height_weight(Request $request)
    {
        $user = auth('api')->user();

        $data = [
            'weight_unit' => $user->user_info->weight_unit,
            'height_unit' => $user->user_info->height_unit,
        ];

        return Helper::jsonResponse(true, 'User Info Retrive successfully', 200, $data);
    }

    public function music()
    {

        $music = Music::select('id', 'music_file', 'title', 'duration')->get();

        return Helper::jsonResponse(true, 'Music retrive successfully', 200, $music);
    }

    public function user_music()
    {

        $user = auth('api')->user();
        if ($user->music) {
            $data = $user->music()->select('id', 'music_file', 'title', 'duration')->get();

            return Helper::jsonResponse(true, 'Music retrive successfully', 200, $data);
        } else {
            $data = Music::where('is_default', 1)->select('id', 'music_file', 'title', 'duration')->get();
            return Helper::jsonResponse(true, 'Music retrive successfully', 200, $data);
        }
    }

    public function assignmusic(Request $request)
    {

        $user = auth('api')->user();

        $user->music_id = $request->music_id;

        $user->save();

        return Helper::jsonResponse(true, 'Music Updated successfully', 200);
    }
}
