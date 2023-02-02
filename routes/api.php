<?php

use App\Http\Controllers\api\admin\AuthController;
use App\Http\Controllers\api\admin\CategoryController;
use App\Http\Controllers\api\admin\LetterController;
use App\Http\Controllers\api\admin\SearchController;
use App\Http\Controllers\api\admin\UserController;
use App\Http\Controllers\api\admin\WordController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('categories',[CategoryController::class,'index']);
Route::get('categories/{id}',[CategoryController::class,'show']);

Route::get('words',[WordController::class,'index']);
Route::get('words/{id}',[WordController::class,'show']);
Route::get('words_count',[WordController::class,'countSort']);

Route::post('authenticate',[AuthController::class,'authenticate']);

Route::middleware('auth:sanctum')->group(function(){

//auth    
Route::post('logout',[AuthController::class,'logout']);
Route::get('check',[AuthController::class,'check']);

//categories
Route::get('categoriesdate',[CategoryController::class,'sortDate']);
Route::post('categories',[CategoryController::class,'store']);
Route::put('categories/{id}',[CategoryController::class,'update']);
Route::delete('categories/{id}',[CategoryController::class,'destroy']);

//Word
Route::get('wordsdate',[WordController::class,'sortDate']);
Route::post('words',[WordController::class,'store']);
Route::put('words/{id}',[WordController::class,'update']);
Route::delete('words/{id}',[WordController::class,'destroy']);

//Search
Route::get('searches',[SearchController::class,'index']);
Route::delete('searches/{id}',[SearchController::class,'destroy']);

});

