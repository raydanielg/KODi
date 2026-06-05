<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Landlord\DashboardController as LandlordDashboard;
use App\Http\Controllers\Agent\DashboardController as AgentDashboard;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboard;
use App\Http\Controllers\Support\DashboardController as SupportDashboard;
use App\Http\Controllers\Maintenance\DashboardController as MaintenanceDashboard;
use App\Http\Controllers\Accountant\DashboardController as AccountantDashboard;
use App\Http\Controllers\Investor\DashboardController as InvestorDashboard;

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

Auth::routes();

// Super Admin Routes
Route::prefix('super-admin')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('super-admin.dashboard');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
});

// Landlord Routes
Route::prefix('landlord')->middleware(['auth', 'role:landlord'])->group(function () {
    Route::get('/dashboard', [LandlordDashboard::class, 'index'])->name('landlord.dashboard');
});

// Agent Routes
Route::prefix('agent')->middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/dashboard', [AgentDashboard::class, 'index'])->name('agent.dashboard');
});

// Tenant Routes
Route::prefix('tenant')->middleware(['auth', 'role:tenant'])->group(function () {
    Route::get('/dashboard', [TenantDashboard::class, 'index'])->name('tenant.dashboard');
});

// Support Agent Routes
Route::prefix('support')->middleware(['auth', 'role:support'])->group(function () {
    Route::get('/dashboard', [SupportDashboard::class, 'index'])->name('support.dashboard');
});

// Maintenance Staff Routes
Route::prefix('maintenance')->middleware(['auth', 'role:maintenance'])->group(function () {
    Route::get('/dashboard', [MaintenanceDashboard::class, 'index'])->name('maintenance.dashboard');
});

// Accountant Routes
Route::prefix('accountant')->middleware(['auth', 'role:accountant'])->group(function () {
    Route::get('/dashboard', [AccountantDashboard::class, 'index'])->name('accountant.dashboard');
});

// Investor Routes
Route::prefix('investor')->middleware(['auth', 'role:investor'])->group(function () {
    Route::get('/dashboard', [InvestorDashboard::class, 'index'])->name('investor.dashboard');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');
