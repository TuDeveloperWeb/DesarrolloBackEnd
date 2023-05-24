<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;


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


Route::group(['prefix' => 'client'], function () {
    Route::get('/prueba', [ClientController::class,"index"]);
    Route::post('/store', [ClientController::class,"store"]);
    Route::get('/list',[ClientController::class,'index'] );
    Route::get('/edit/{id}',[ClientController::class,'edit'] );
    Route::delete('/delete/{id}',[ClientController::class,'delete'] );
    Route::post('/update',[ClientController::class,'update'] );
});
