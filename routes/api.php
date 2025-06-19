<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\AutoController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\StationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/orders/show/{id}', [OrderController::class, 'show'])->name('ShowOrder');
Route::post('/rides/store', [RideController::class, 'store'])->name('StoreRide');
Route::get('/drivers/routes/current/{driver_id}', [RideController::class, 'currentroutesbydriver'])->name('CurrentRoutes');

// Route::post('/distance-matrix', [OrderController::class, 'getDistanceMatrix'])->name('DistanceMatrix');
Route::get('/stations', [StationController::class, 'index'])->name('IndexStation');
Route::get('/stations/{id}/{type}/drivers', [StationController::class, 'drivers'])->name('DriversOnStation');

Route::get('/drivers/show/{id}', [DriverController::class, 'showapi'])->name('ShowDriver');
Route::get('/autos/show/{id}', [AutoController::class, 'showapi'])->name('ShowAuto');


Route::get('/stations/{id}/autodata', [StationController::class, 'autodata'])->name('AutodataStation');
Route::get('/stations/{id}/finanse', [StationController::class, 'finanse'])->name('FinanseStation');
Route::get('/stations/{id}/finanse2', [StationController::class, 'finanse2'])->name('FinanseStation');

