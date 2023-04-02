<?php

use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\Auth\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:api' ])->group(function(){
    
});

Route::middleware(['auth:sanctum' ])->group(function(){
    Route::post('logout' , [UserController::class , 'logout']);
    Route::post('check-otp' , [OtpController::class , 'check_otp']);

});
Route::post('register' , [UserController::class , 'register']);
Route::post('login' , [UserController::class , 'login']);    

Route::post('social-login' , [UserController::class , 'social_login'])->name('social.login');


Route::get('/auth/redirect', [UserController::class , 'handleGoogleRedirect']);
 
Route::get('/auth/callback', [UserController::class , 'handleGoogleCallback']);

