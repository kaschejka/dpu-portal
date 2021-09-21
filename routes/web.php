<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\rezervnumController;
use App\Http\Controllers\prodlRezervnumController;
use App\Http\Controllers\exportrezervnumController;
use App\Http\Controllers\exportoperregController;
use App\Http\Controllers\parsebzController;
use App\Http\Controllers\exportbzController;
use App\Http\Controllers\generatedocController;
use App\Http\Controllers\generatedocvbulController;
use App\Http\Controllers\addmanagerController;
use App\Http\Controllers\historyRezervController;
use App\Http\Controllers\nGnezdoController;
use App\Http\Controllers\myreservController;
use App\Http\Controllers\upnmsController;
use App\Http\Controllers\GnezdoController;
use App\Http\Controllers\editcompanyController;
use App\Http\Controllers\taskdpuController;
use App\HTTP\Middleware\role;
use App\Http\Controllers\capibapiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::middleware(['auth:sanctum', 'verified','role:admin'])->get('/rezervnum', function () {
    return view('rezervnum');
})->name('rezervnum');

Route::middleware(['auth:sanctum', 'verified','role:admin'])->get('/bz', function () {
    return view('bz');
})->name('bz');

Route::middleware(['auth:sanctum', 'verified','role:admin'])->get('/hlr', function () {
    return view('hlr');
})->name('hlr');

Route::middleware(['auth:sanctum', 'verified','role:admin,vip'])->get('/fas', function () {
    return view('fas');
})->name('fas');

Route::middleware(['auth:sanctum', 'verified','role:admin,vip,manager'])->get('/regoper', function () {
    return view('regoper');
})->name('regoper');


Route::middleware(['auth:sanctum', 'verified','role:admin,manager'])->get('/generatedoc', function () {
    return view('generatedoc');
})->name('generatedoc');

Route::middleware(['auth:sanctum', 'verified','role:admin'])->get('/config', function () {
    return view('config');
})->name('config');

Route::middleware(['auth:sanctum', 'verified','role:admin,manager'])->get('/my_reserve', function () {
    return view('my_reserve');
})->name('my_reserve');

Route::middleware(['auth:sanctum', 'verified','role:admin'])->get('/upnms', function () {
    return view('upnms');
})->name('upnms');

Route::middleware(['auth:sanctum', 'verified','role:admin,manager'])->get('/editcompany', function () {
    return view('editcompany');
})->name('editcompany');

Route::middleware(['auth:sanctum', 'verified','role:admin,vip'])->get('/gnezdo', function () {
    return view('gnezdo');
})->name('gnezdo');
Route::middleware(['auth:sanctum', 'verified','role:admin'])->get('/capibapi', function () {
    return view('capibapi');
})->name('capibapi');


Route::middleware(['auth:sanctum', 'verified','role:admin'])->get('/crbz', function () {
    return view('createBzGnezdo');
})->name('createBzGnezdo');

Route::middleware(['auth:sanctum', 'verified','role:admin,manager'])->get('/taskdpunum', function () {
    return view('taskdpunum');
})->name('taskdpunum');

Route::middleware(['auth:sanctum', 'verified','role:admin,manager'])->get('/opentask', function () {
    return view('allopentask');
})->name('allopentask');
Route::middleware(['auth:sanctum', 'verified','role:admin,manager'])->get('/opentask/{task}', [taskdpuController::class,'seeTask']);

Route::post('/editcompany/submit', [editcompanyController::class,'editcompany']);
Route::post('/upnms/submit', [upnmsController::class,'upnms']);
Route::get('/historyrezerv', [historyRezervController::class,'history']);
Route::post('/config/submit', [addmanagerController::class,'addmanager']);
Route::post('/rezervnum/submit', [rezervnumController::class,'reznumsub']);
Route::get('/rezervnum/export', [exportrezervnumController::class,'rnexp']);
Route::post('/bz/submit', [parsebzController::class,'parsebz']);
Route::get('/bz/export', [exportbzController::class,'export']);
Route::post('/generatedoc/submit', [generatedocController::class,'generatedoc']);
Route::post('/generatedoc/submitvbul', [generatedocvbulController::class,'generatedoc']);
Route::get('/generatedoc/ord', [nGnezdoController::class,'ord']);
Route::get('/my_reserved', [myreservController::class,'rez']);
Route::post('/prodlenie_rezerva', [prodlRezervnumController::class,'reznumsub']);
Route::post('/getFollowMe/submit', [GnezdoController::class,'getfollowMe']);
Route::post('/getFollowMe/reservUID', [GnezdoController::class,'reservuid']);
Route::post('/getFollowMe/markerKarusel', [GnezdoController::class,'markerKarusel']);
Route::post('/cb', [capibapiController::class,'createFileCapiBapi']);
