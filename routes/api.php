<?php

use App\Http\Controllers\api\admin\AuthController;
use App\Http\Controllers\api\admin\LetterController;
use App\Http\Controllers\api\admin\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('authenticate',[AuthController::class,'authenticate']);

Route::middleware('auth:sanctum')->group(function(){
Route::post('logout',[AuthController::class,'logout']);
Route::get('check',[AuthController::class,'check']);

Route::apiResource('letter',LetterController::class);
});
