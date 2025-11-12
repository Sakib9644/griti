<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OnboardingController extends Controller
{
    /**
     * Store or update user onboarding info
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'age' => 'nullable|integer',
            'bmi' => 'nullable|string',
            'body_part_focus' => 'nullable|string',
            'body_satisfaction' => 'nullable|string',
            'celebration_plan' => 'nullable|string',
            'current_body_type' => 'nullable|string',
            'current_weight' => 'nullable|numeric',
            'dream_body' => 'nullable|string',
            'height' => 'nullable|numeric',
            'target_weight' => 'nullable|numeric',
            'trying_duration' => 'nullable|string',
            'urgent_improvement' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'subscription_id' => 'nullable|string',
            'signature' => 'nullable|file|mimes:jpg,jpeg,png,pdf', // validate file type
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Handle signature file upload if exists
        $signaturePath = null;
        if ($request->hasFile('signature')) {
            $signaturePath = Helper::fileUpload(
                $request->file('signature'),
                'user/signature',
                getFileName($request->file('signature'))
            );
        }

        // Store or update the user info
        $userInfo = UserInfo::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'age' => $request->age,
                'bmi' => $request->bmi,
                'body_part_focus' => $request->body_part_focus,
                'body_satisfaction' => $request->body_satisfaction,
                'celebration_plan' => $request->celebration_plan,
                'current_body_type' => $request->current_body_type,
                'current_weight' => $request->current_weight,
                'dream_body' => $request->dream_body,
                'height' => $request->height,
                'target_weight' => $request->target_weight,
                'trying_duration' => $request->trying_duration,
                'urgent_improvement' => $request->urgent_improvement,
                'payment_status' => $request->payment_status ?? 'trial',
                'subscription_id' => $request->subscription_id ?? null,
                'signature' => url($signaturePath )?? null,
                'price' => 0,
            ]
        );

        $data = [


                'age' => $userInfo->age,
                'bmi' => $userInfo->bmi,
                'body_part_focus' => $userInfo->body_part_focus,
                'body_satisfaction' => $userInfo->body_satisfaction,
                'celebration_plan' => $userInfo->celebration_plan,
                'current_body_type' => $userInfo->current_body_type,
                'current_weight' => $userInfo->current_weight,
                'dream_body' => $userInfo->dream_body,
                'height' => $userInfo->height,
                'target_weight' => $userInfo->target_weight,
                'trying_duration' => $userInfo->trying_duration,
                'urgent_improvement' => $userInfo->urgent_improvement,
                'payment_status' => $userInfo->payment_status ?? 'trial',
                'subscription_id' => $userInfo->subscription_id ?? null,
                'signature' => url($userInfo->signature )?? null,
                // 'price' => 0,


        ];

        return response()->json([
            'status' => 'success',
            'message' => 'User info saved successfully',
            'data' => $data ,
        ], 200);
    }
}
