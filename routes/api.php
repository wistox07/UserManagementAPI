<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserSystemController;
use App\Models\UserSystem;
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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::prefix('v1')->group(function () {

    Route::prefix("auth")->group(function (){
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);

        Route::middleware("validate.token")->group(function(){
            Route::post('chosee_system', [AuthController::class, 'choseeSystem']);
        });
    });

    Route::middleware("validate.token")->group(function(){
        Route::get('user/systems', [UserSystemController::class, 'getSystemsByToken']);
        Route::get('user/{id}/systems', [UserSystemController::class, 'getSystemsByUser']);

    });





});


