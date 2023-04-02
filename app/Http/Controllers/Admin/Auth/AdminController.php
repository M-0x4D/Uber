<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    function admin_register(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->merge(["password" => bcrypt($request->password)]);
            $client = Admin::create($request->all());
            $client->otp_valid = now();
            $client->country_code = $request->country_code;
            $client->otp = '3456';
            $client->save();
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $client->createToken("API TOKEN")->plainTextToken
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
    }
    function admin_login(Request $request)
    {
        $admin = Admin::where('phone', $request->phone)->first();
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'phone' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        return redirect()->route('');

    }

    function admin_logout(Request $request)
    {

    }
}
