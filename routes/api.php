<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;

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

Route::group(['prefix' => 'v1'], function(){
	Route::get('/upload', [SalesController::class, 'upload']);
	Route::post('/upload', [SalesController::class, 'chunkFile']);
	Route::get('/batch/{batchId}', [SalesController::class, 'batch']);
	Route::get('/batch-info/in-progress',[SalesController::class, 'inProgress']);
});
