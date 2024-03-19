<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
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

Route::controller(LoginController::class)->group(function () {
    Route::get('/', 'login')->name('login');
    Route::get('/login', 'login');
    Route::post('/login', 'authentikasi');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(PasswordController::class)->middleware('auth')->group(function () {
    Route::get('/changePassword', 'index')->name('changePassword');
    Route::post('/change', 'change')->name('change');
});

Route::controller(DashboardController::class)->middleware('auth')->group(function () {
    Route::get('/dashboard', 'index')->name('dashboard');
});
