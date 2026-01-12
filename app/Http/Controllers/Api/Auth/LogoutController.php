<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Models\FirebaseTokens;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class LogoutController extends Controller
{
    public $select;
    public function __construct()
    {
        parent::__construct();
        $this->select = ['id', 'name', 'email', 'avatar'];
    }
    public function logout(Request $request)
    {
        try {
            $request->validate([

                'device_id' =>'required|exists:firebase_tokens,device_id'
            ]);
            if (!Auth::guard('api')->check()) {
                return Helper::jsonErrorResponse('User not authenticated', 401);
            }

           $dl = FirebaseTokens::where('user_id', Auth::guard('api')->id())
                ->where('device_id', $request->device_id)
                ->delete();



            Auth::guard('api')->logout();

            return Helper::jsonResponse(true, 'Logged out successfully. Token revoked.', 200);
        } catch (\Exception $e) {
            return Helper::jsonErrorResponse($e->getMessage(), 500);
        }
    }
}
