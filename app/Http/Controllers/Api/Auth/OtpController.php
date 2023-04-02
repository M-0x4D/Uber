<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckOtpRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    function check_otp(CheckOtpRequest $request)
    {
        $user = Client::where(['otp' => $request->otp, 'phone' => $request->phone])->first();
        // return $user;

        if (!($user->id ?? null)) {
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('auth.failed')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        if ($user->country_code != $request->country_code){
            return response()->json([
                'status' => 422,
                'message' => null,
                'errors' => ['default' => [__('auth.failed')]],
                'result' => 'failed',
                'data' => null
            ], 422);
        }

        return response()->json([
            'status' => 200,
            'message' => __('auth.otp_verified_success'),
            'errors' => null,
            'result' => 'success',
            'data' => ['user' => auth()->user()]
        ], 200);
    }
}
