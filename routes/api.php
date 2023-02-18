<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\LabelsController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\TicketController;
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


Route::middleware(['auth:sanctum','ability:role-admin'])->group(function(){
    Route::apiResource("labels", LabelsController::class)->except('index');
    Route::apiResource("categories", CategoriesController::class)->except('index');
    Route::get('logs/',[LogsController::class,'index']);
    Route::get('logs/{logs}',[LogsController::class,'show']);
    Route::put('ticket/assign/{ticket}',[TicketController::class,'assignAgent']);
});


Route::middleware('auth:sanctum')->group(function(){
    Route::middleware(['ability:role-agent,role-admin'])->group(function(){
        Route::put('ticket/update/status/{ticket}',[TicketController::class,'updateStatus']);
        Route::post("ticket/comment/{ticket}", [TicketController::class,'comment']);
    });


    Route::apiResource("ticket", TicketController::class)->except('update');
    Route::get('labels/',[LabelsController::class,'index']);
    Route::get('categories/',[CategoriesController::class,'index']);
    Route::post("logout/",[AuthController::class,"logout"])->name("user.logout");
});
