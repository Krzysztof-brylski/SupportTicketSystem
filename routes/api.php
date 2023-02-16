<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//auth
Route::post("register/",[AuthController::class,"register"])->name("user.register");
Route::post("login/",[AuthController::class,"login"])->name("user.login");


Route::middleware('auth:sanctum')->group(function(){

    //todo admin gate


    Route::post("logout/",[AuthController::class,"logout"])->name("user.logout");
});
