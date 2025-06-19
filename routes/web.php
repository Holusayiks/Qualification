<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AutoController;
use App\Http\Controllers\DispetcherController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\MenedgerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\StationController;
use App\Http\Middleware\DispetcherAuth;
use App\Http\Middleware\DriverAuth;
use App\Http\Middleware\MenedgerAuth;
use App\Http\Middleware\SupplierAuth;
use App\Models\Dispetcher;
use App\Models\Driver;
use App\Models\Menedger;
use App\Models\Order;
use App\Models\Ride;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Route;



Route::get('admin',function (){
    $drivers=Driver::all();
    $menedgers=Menedger::all();
    $dispetchers=Dispetcher::all();
    $suppliers=Supplier::all();
    return view('admin',compact(['drivers','menedgers','dispetchers','suppliers']));
});
Route::get('generator',function (){
    $rides=Ride::all();
    $orders=Order::all();
    return view('generator',compact(['rides','orders']));
});
Route::get('/', function () {
    return view('welcome');
});
Route::get('/main', function () {
    echo 'main';
})->name('main');
Route::middleware(['auth', 'verified'])->get('/dashboard',[AuthenticatedSessionController::class, 'index'])->name('dashboard');
/**Driver routes **/
Route::middleware(DriverAuth::class)->prefix('driver')->group(function(){
    Route::get('/dashboard', [DriverController::class, 'show'])->name('driverDashboardShow');
    Route::get('/dashboard/{id}/edit', [DriverController::class, 'edit'])->name('driverDashboardEdit');
    Route::get('/dashboard/{id}/delete', [DriverController::class, 'delete'])->name('driverDashboardDelete');
    Route::post('/dashboard/status', [DriverController::class, 'status'])->name('driverDashboardStatus');
    Route::get('/dashboard/accident/{type}', [DriverController::class, 'accident'])->name('driverDashboardAccident');
    Route::get('/dashboard/accident/disable', [DriverController::class, 'disableaccident'])->name('driverDashboardDisableAccident');
    Route::get('/dashboard/ridestart', [DriverController::class, 'ridestart'])->name('driverDashboardRideStart');
    Route::post('/dashboard/{id}/update', [DriverController::class, 'update'])->name('driverDashboardUpdate');
    Route::get('/dashboard/orders', [OrderController::class, 'indexbydriver'])->name('driverOrders');
    Route::get('/dashboard/orders/show', [RideController::class, 'showbydriver'])->name('driverOrder');
    Route::get('/dashboard/rides/update/{point}', [RideController::class, 'updatepoint'])->name('updatePoint');
    Route::get('/dashboard/messagereaded', [DriverController::class, 'messagereaded'])->name('driverDashboardMessageReaded');
    Route::get('/dashboard/vacantioncancel', [DriverController::class, 'vacantioncancel'])->name('driverDashboardVacantionCancel');
    Route::get('/dashboard/rides/{ride_id}/return/{driver_id}', [RideController::class, 'returnride'])->name('driverDashboardReturnRide');
    Route::get('/dashboard/rides/{ride_id}/confirm', [RideController::class, 'confirmride'])->name('driverDashboardReturnRide');

});
/**Supplier routes **/
Route::middleware(SupplierAuth::class)->prefix('supplier')->group(function(){
    Route::get('/dashboard', [SupplierController::class, 'show'])->name('supplierDashboardShow');
    Route::get('/dashboard/{id}/edit', [SupplierController::class, 'edit'])->name('supplierDashboardEdit');
    Route::post('/dashboard/{id}/update', [SupplierController::class, 'update'])->name('supplierDashboardUpdate');
    Route::get('/dashboard/orders', [OrderController::class, 'indexbysupplier'])->name('supplierOrders');
    Route::get('/dashboard/orders/{id}/show', [OrderController::class, 'showbysupplier'])->name('supplierOrderShow');
    Route::get('/dashboard/orders/add', [OrderController::class, 'createbysupplier'])->name('supplierAddOrder');
    Route::post('/dashboard/orders/store', [OrderController::class, 'storebysupplier'])->name('supplierStoreOrder');
    Route::get('/dashboard/orders/{id}/edit', [OrderController::class, 'editbysupplier'])->name('supplierOrderEdit');
    Route::get('/dashboard/orders/{id}/delete', [OrderController::class, 'delete'])->name('supplierOrderDelete');
    Route::post('/dashboard/orders/{id}/update', [OrderController::class, 'updatebysupplier'])->name('supplierUpdateOrder');

});
/**Menedger routes **/
Route::middleware(MenedgerAuth::class)->prefix('menedger')->group(function(){
    Route::get('/dashboard', [MenedgerController::class, 'show'])->name('menedgerDashboardShow');
    Route::get('/dashboard/drivers', [DriverController::class, 'indexbymanadger'])->name('menedgerDashboardDrivers');
    Route::get('/dashboard/autos', [AutoController::class, 'indexbymanadger'])->name('menedgerDashboardAutos');
    Route::get('/dashboard/{id}/edit', [MenedgerController::class, 'edit'])->name('menedgerDashboardEdit');
    Route::post('/dashboard/{id}/update', [MenedgerController::class, 'update'])->name('menedgerDashboardUpdate');
    Route::get('/dashboard/drivers/{id}/delete', [DriverController::class, 'delete'])->name('menedgerDashboardDelete');
    Route::get('/dashboard/drivers/status', [DriverController::class, 'status'])->name('menedgerDashboardStatus');
    Route::get('/dashboard/orders', [OrderController::class, 'indexbymanadger'])->name('menedgerDashboardOrders');
    Route::post('/dashboard/orders/edit', [OrderController::class, 'editbymanadger'])->name('menedgerDashboardOrderEdit');
    Route::get('/dashboard/orders/{id}/update', [OrderController::class, 'updatebymanadger'])->name('menedgerDashboardOrderUpdate');

    Route::get('/dashboard/rides', [RideController::class, 'indexbymanadger'])->name('menedgerDashboardRides');

    Route::get('/dashboard/orders/remove/{id}', [OrderController::class, 'removebymanadger'])->name('menedgerDashboardOrdersRemove');
    Route::get('/dashboard/stations', [StationController::class, 'indexbymanadger'])->name('menedgerDashboardStations');


    Route::post('/dashboard/rides/{ride_id}/changestation', [RideController::class, 'changestation'])->name('menedgerDashboardChangeDriver');
    Route::post('/dashboard/rides/{ride_id}/changedates', [RideController::class, 'changedates'])->name('menedgerDashboardChangeDriver');

    Route::get('/dashboard/rides/{ride_id}/accept', [RideController::class, 'accept'])->name('menedgerDashboardAccept');

    Route::get('/dashboard/rides/finanse', [RideController::class, 'finansebymenedger'])->name('menedgerDashboardFinanse');
    Route::get('/dashboard/rides/finanse/journal', [MenedgerController::class, 'fanansejournal'])->name('menedgerDashboardJournal');
});
/**Dispetcher routes **/
Route::middleware(DispetcherAuth::class)->prefix('dispetcher')->group(function(){
    Route::get('/dashboard', [DispetcherController::class, 'show'])->name('dispatcherDashboardShow');
    Route::get('/dashboard/drivers', [DriverController::class, 'indexbydispatcher'])->name('driversDashboard');
    Route::get('/dashboard/drivers/{id}/resolveaccident', [RideController::class, 'resolvingaccident'])->name('ResolvingAccidentDashboard');
    Route::get('/dashboard/drivers/{id}/atstation', [DriverController::class, 'atstation'])->name('DriverAtStationDashboard');
    Route::post('/dashboard/drivers/{id}/resolveaccident/easy', [RideController::class, 'resolveaccidenteasylevel'])->name('ResolveAccidentEasyLevelDashboard');
    Route::post('/dashboard/drivers/{id}/resolveaccident/hard', [RideController::class, 'resolveaccidenthardlevel'])->name('ResolveAccidentHardLevelDashboard');
    Route::get('/dashboard/autos', [AutoController::class, 'indexbydispatcher'])->name('autosDashboard');
    Route::get('/dashboard/{id}/edit', [DispetcherController::class, 'edit'])->name('dispatcherDashboardEdit');
    Route::post('/dashboard/{id}/update', [DispetcherController::class, 'update'])->name('dispatcherDashboardUpdate');
    Route::get('/dashboard/{id}/delete', [DispetcherController::class, 'delete'])->name('dispatcherDashboardDelete');
    Route::get('/dashboard/drivers/{driver_id}/status/{answer}', [DriverController::class, 'statusbydispatcher'])->name('GiveVacation');
    Route::get('/dashboard/invacation', [DriverController::class, 'invacationbydispatcher'])->name('driversInVacationDashboard');
    Route::get('/dashboard/invacation', [DriverController::class, 'invacationbydispatcher'])->name('driversInVacationDashboard');
    Route::get('/dashboard/changedriver/{driver}/{auto}', [AutoController::class, 'changedriver'])->name('changeDriverDashboard');
    Route::get('/dashboard/stations', [StationController::class, 'indexbydispatcher'])->name('dispatcherDashboardStations');
    //changedriver
    Route::get('/dashboard/rides', [RideController::class, 'indexbydispatcher'])->name('dispatcherDashboardRides');
    Route::post('/dashboard/rides/{ride_id}/changedriver', [RideController::class, 'changedriver'])->name('dispetcherDashboardChangeDriver');
    //endbydispetcher
    Route::get('/dashboard/rides/{ride_id}/end', [RideController::class, 'endbydispetcher'])->name('dispetcherDashboardEndRide');


    Route::get('/dashboard/autos/create', [AutoController::class, 'createbydispetcher'])->name('dispetcherAutoCreate');
    Route::post('/dashboard/autos/store', [AutoController::class, 'storebydispetcher'])->name('dispetcherAutoStore');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
