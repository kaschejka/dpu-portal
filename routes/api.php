<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\operregController;
use App\HTTP\Controllers\progressController;
use App\Http\Controllers\hlrController;
use App\Http\Controllers\addmanagerController;
use App\Http\Controllers\capibapiController;
use App\Http\Controllers\configController;
use App\Http\Controllers\rezervnumController;
use App\Http\Controllers\taskdpuController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/numfas', [operregController::class,'operregsub']);
Route::get('/progress', [progressController::class,'progress']);
Route::post('/gethlr', [hlrController::class,'hlr']);
Route::post('/editManager', [addmanagerController::class,'editReserveManager']);
Route::post('/delManager', [addmanagerController::class,'delReserveManager']);
Route::post('/delUser', [addmanagerController::class,'delUser']);
Route::post('/capibapi', [capibapiController::class,'createFileCapiBapi']);
Route::post('/config', [configController::class,'config']);
Route::post('/viborNumber', [rezervnumController::class,'vibor']);
Route::post('/taskdpu', [taskdpuController::class,'task']);
Route::post('/closeTask', [taskdpuController::class,'closeTask']);
Route::post('/numreserv', [rezervnumController::class,'reznumsub']);
Route::post('/exportResultReserve', [rezervnumController::class,'exportFile']);
Route::post('/getSesionJira', [taskdpuController::class,'getSesionJira']);
