<?php

use App\Http\Controllers\AdminCableController;
use App\Http\Controllers\AdminConnectivityDeviceController;
use App\Http\Controllers\AdminLocationController;
use App\Http\Controllers\AdminOwnerController;
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

Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);

    return redirect()->back();
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
    Route::get('/admin/cables/create', [AdminCableController::class, 'create'])->name('cables.create');
    Route::get('/admin/cables/{cable}/edit', [AdminCableController::class, 'edit'])->name('cables.edit');

    // Admin routes for zones
    Route::get('/admin/zones', [AdminZoneController::class, 'index'])->name('zones.index');
    Route::get('/admin/zones/create', [AdminZoneController::class, 'create'])->name('zones.create');
    Route::get('/admin/zones/{zone}/edit', [AdminZoneController::class, 'edit'])->name('zones.edit');

    // Admin routes for locations
    Route::get('/admin/locations', [AdminLocationController::class, 'index'])->name('locations.index');
    Route::get('/admin/locations/create', [AdminLocationController::class, 'create'])->name('locations.create');
    Route::get('/admin/locations/{location}/edit', [AdminLocationController::class, 'edit'])->name('locations.edit');

    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/admin/users', [UserController::class, 'index'])->name('users');
    Route::get('/admin/owners', [AdminOwnerController::class, 'index'])->name('owners.index');

});

require __DIR__.'/auth.php';
