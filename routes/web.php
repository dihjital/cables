<?php

use App\Http\Controllers\AdminCableController;
use App\Http\Controllers\AdminConnectivityDeviceController;
use App\Http\Controllers\AdminZoneController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ConnectivityDeviceController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Admin routes for connectivity devices
    Route::get('/admin/connectivity_devices', [AdminConnectivityDeviceController::class, 'index'])
        ->name('connectivity_device.index');

    Route::get('/admin/connectivity_devices/{connectivity_device}/edit', [AdminConnectivityDeviceController::class, 'edit'])
        ->name('connectivity_device.edit');
    Route::patch('/admin/connectivity_devices/{connectivity_device}', [AdminConnectivityDeviceController::class, 'update'])
        ->name('connectivity_device.update');

    Route::delete('/admin/connectivity_devices/{connectivity_device}', [AdminConnectivityDeviceController::class, 'destroy'])
        ->name('connectivity_device.delete');

    Route::get('/admin/connectivity_devices/create', [AdminConnectivityDeviceController::class, 'create'])
        ->name('connectivity_device.create');
    Route::post('/admin/connectivity_devices', [AdminConnectivityDeviceController::class, 'store'])
        ->name('connectivity_device.new');

    // Admin routes for cables
    Route::get('/admin/cables', [AdminCableController::class, 'index'])->name('cables.index');
    Route::get('/admin/cable/create', [AdminCableController::class, 'create'])->name('cables.create');

    // Admin routes for zones
    Route::get('/admin/zones', [AdminZoneController::class, 'index'])->name('zones.index');
    Route::get('/admin/zones/create', [AdminZoneController::class, 'create'])->name('zones.create');
    Route::get('/admin/zones/{zone}/edit', [AdminZoneController::class, 'edit'])->name('zones.edit');

    Route::get('/history', [HistoryController::class, 'index'])->name('history');

    Route::get('/admin/users', [UserController::class, 'index'])->name('users');

});

Route::get('/zones', [ZoneController::class, 'index'])->name('zones');
Route::post('/zones', [ZoneController::class, 'store'])
    ->name('zone.new');
// Route::delete('/zones/{zone_name}/delete/{location_name}', [ZoneController::class, 'destroy']);

Route::get('/locations', [LocationController::class, 'index'])->name('locations');
Route::delete('/locations/{location_name}/zones/{zone_name}', [LocationController::class, 'destroy'])
    ->name('zone.location.delete');

// Publicly available routes
Route::get('/connectivity_devices', [ConnectivityDeviceController::class, 'index']);

require __DIR__.'/auth.php';
