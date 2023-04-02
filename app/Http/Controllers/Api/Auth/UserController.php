<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckOtpRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;



class UserController extends Controller
{
    function register(RegisterRequest $request)
    {

        // $validatedData = $request->validated();
        // if (!$validatedData) {
        //     return response()->json("failed to register");
        // } else {
            DB::beginTransaction();
            try {
                $request->merge(["password" => bcrypt($request->password)]);
                $client = Client::create($request->all());
                $client->otp_valid = now();
                $client->country_code = $request->country_code;
                $client->otp = '3456';
                $client->save();
                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'User Created Successfully',
                    'token' => $client->createToken("API TOKEN")->plainTextToken,
                    'otp' => $client->otp
                ], 200);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'status' => 422,
                    'message' => null,
                    'errors' => 'rtrwt',
                    'result' => 'failed',
                    'data' => null
                ], 422);
            }
        // }
    }


    function login(LoginRequest $request)
    {

        $client = Client::where(['phone'=> $request->phone  , 'name' => $request->name])->first();
        // if (!$client || !Hash::check($request->password, $client->password)) {
        //     throw ValidationException::withMessages([
        //         'phone' => ['The provided credentials are incorrect.'],
        //     ]);
        // }
        // return $client;
        if (!$client) {
            return response()->json("user doesn't exists");
        }
        $client->tokens()->where('tokenable_id', $client->id)->delete();
        $client->otp = '3456';
        $client->save();
        $token = $client->createToken($request->device_name)->plainTextToken;
        $data = UserResource::make($client);
        $data = data_set($data, 'token', $token);
        return response()->json([
            'status' => true,
            'otp' => $client->otp,
            'message' => 'User Logged In Successfully',
            'token' => $data
        ], 200);
    }





    function social_login(Request $request)
    {
        $user = Client::whereHas('providers', function ($query) use ($request) {
            $query->where(['provider' => $request->provider, 'provider_id' => $request->provider_id]);
        })->first();
        return Socialite::driver('google')->redirect();
    }


    // function handleGoogleRedirect()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // function handleGoogleCallback()
    // {
    //     $user = Socialite::driver('google')->user();
    //     $userExistsed = Client::where()->first();
    //     if ($userExistsed) {
    //         Auth::guard('api')->login($userExistsed);
    //         return redirect()->route('');
    //     } else {
    //         $newUser = Client::create([]);
    //         Auth::login($newUser);
    //         return redirect()->route('');
    //     }
    // }


    function logout(Request $request)
    {
        $user = Auth::user();
        // Revoke a specific token...
        $user->tokens()->where('tokenable_id', $user->id)->delete();

        return response()->json([
            'status' => 422,
            'message' => null,
            'result' => 'logged out successfully',
            'data' => null
        ], 200);
    }
}
