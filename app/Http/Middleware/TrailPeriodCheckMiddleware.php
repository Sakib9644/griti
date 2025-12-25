<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrailPeriodCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        if ($user) {
            $userInfo = $user->user_info; // assuming relation: User hasOne UserInfo

            if ($userInfo && $userInfo->payment_status === 'trial') {
                $trialCreated = $userInfo->created_at;
                $trialDays = 3;
                $trail = $trialCreated->diffInDays(Carbon::now());


                if ( $trail > $trialDays) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Your trial period has expired. You have not paid your subscription.'
                    ], 403);
                }
            }
        }

        return $next($request);
    }
}
