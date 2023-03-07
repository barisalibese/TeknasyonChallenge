<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;
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

Route::post('/register',[DeviceController::class,'register']);

Route::group(['middleware' => \App\Http\Middleware\OauthAccess::class], function()
{
    Route::post('/purchase',[DeviceController::class,'purchase']);
    Route::get('/check-subscription',[DeviceController::class,'checkSubscription']);
});

