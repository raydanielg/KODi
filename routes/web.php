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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// ============================================================
// SUPER ADMIN ROUTES
// ============================================================
Route::prefix('super-admin')->name('super-admin.')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [App\Http\Controllers\SuperAdmin\UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\SuperAdmin\UserManagementController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SuperAdmin\UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [App\Http\Controllers\SuperAdmin\UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\SuperAdmin\UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\SuperAdmin\UserManagementController::class, 'destroy'])->name('destroy');
        Route::get('/admins', [App\Http\Controllers\SuperAdmin\UserManagementController::class, 'admins'])->name('admins');
        Route::get('/landlords', [App\Http\Controllers\SuperAdmin\UserManagementController::class, 'landlords'])->name('landlords');
        Route::get('/agents', [App\Http\Controllers\SuperAdmin\UserManagementController::class, 'agents'])->name('agents');
        Route::get('/tenants', [App\Http\Controllers\SuperAdmin\UserManagementController::class, 'tenants'])->name('tenants');
    });

    // Roles & Permissions
    Route::get('/roles', [App\Http\Controllers\SuperAdmin\RoleController::class, 'index'])->name('roles.index');

    // System
    Route::get('/database', [App\Http\Controllers\SuperAdmin\SystemController::class, 'database'])->name('database.index');
    Route::get('/backups', [App\Http\Controllers\SuperAdmin\BackupController::class, 'index'])->name('backups.index');
    Route::get('/logs', [App\Http\Controllers\SuperAdmin\SystemController::class, 'logs'])->name('logs.index');
    Route::get('/queues', [App\Http\Controllers\SuperAdmin\SystemController::class, 'queues'])->name('queues.index');
    Route::get('/settings', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'index'])->name('settings.index');
    Route::get('/maintenance', [App\Http\Controllers\SuperAdmin\SystemController::class, 'maintenance'])->name('maintenance.index');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Properties
    Route::prefix('properties')->name('properties.')->group(function () {
        Route::get('/',              [App\Http\Controllers\Admin\PropertyController::class, 'index'])->name('index');
        Route::get('/{id}',          [App\Http\Controllers\Admin\PropertyController::class, 'show'])->name('show');
        Route::get('/{id}/edit',     [App\Http\Controllers\Admin\PropertyController::class, 'edit'])->name('edit');
        Route::put('/{id}',          [App\Http\Controllers\Admin\PropertyController::class, 'update'])->name('update');
        Route::delete('/{id}',       [App\Http\Controllers\Admin\PropertyController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/verify',  [App\Http\Controllers\Admin\PropertyController::class, 'verify'])->name('verify');
    });

    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/',              [App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
        Route::post('/',             [App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
        Route::get('/{id}',          [App\Http\Controllers\Admin\UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit',     [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
        Route::put('/{id}',          [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
        Route::delete('/{id}',       [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/suspend', [App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('suspend');
        Route::get('/verify',        [App\Http\Controllers\Admin\UserController::class, 'verify'])->name('verify');
    });

    // Applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', function () { return view('admin.applications.index'); })->name('index');
        Route::get('/{id}', function () { return view('admin.applications.show'); })->name('show');
    });

    // Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\PaymentController::class, 'edit'])->name('edit');
    });

    // Disputes
    Route::prefix('disputes')->name('disputes.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DisputeController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Admin\DisputeController::class, 'show'])->name('show');
    });

    // Announcements
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', function () { return view('admin.announcements.index'); })->name('index');
        Route::get('/create', function () { return view('admin.announcements.create'); })->name('create');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
        Route::get('/generate', [App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('generate');
    });

    // Maintenance
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\MaintenanceController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Admin\MaintenanceController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\MaintenanceController::class, 'edit'])->name('edit');
        Route::put('/{id}/status', [App\Http\Controllers\Admin\MaintenanceController::class, 'updateStatus'])->name('updateStatus');
    });

    // Logs
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', function () { return view('admin.logs.index'); })->name('index');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () { return view('admin.settings.index'); })->name('index');
    });
});

// ============================================================
// LANDLORD ROUTES
// ============================================================
Route::prefix('landlord')->name('landlord.')->middleware(['auth', 'role:landlord'])->group(function () {
    Route::get('/dashboard', [LandlordDashboard::class, 'index'])->name('dashboard');

    // Properties
    Route::prefix('properties')->name('properties.')->group(function () {
        Route::get('/', [App\Http\Controllers\Landlord\PropertyController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Landlord\PropertyController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Landlord\PropertyController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Landlord\PropertyController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Landlord\PropertyController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Landlord\PropertyController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Landlord\PropertyController::class, 'destroy'])->name('destroy');
    });

    // Tenants
    Route::prefix('tenants')->name('tenants.')->group(function () {
        Route::get('/', [App\Http\Controllers\Landlord\TenantController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Landlord\TenantController::class, 'show'])->name('show');
    });

    // Applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [App\Http\Controllers\Landlord\ApplicationController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Landlord\ApplicationController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [App\Http\Controllers\Landlord\ApplicationController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [App\Http\Controllers\Landlord\ApplicationController::class, 'reject'])->name('reject');
    });

    // Rent Collection
    Route::prefix('rent-collection')->name('rent-collection.')->group(function () {
        Route::get('/', [App\Http\Controllers\Landlord\RentCollectionController::class, 'index'])->name('index');
        Route::get('/history', [App\Http\Controllers\Landlord\RentCollectionController::class, 'history'])->name('history');
    });

    // Leases
    Route::prefix('leases')->name('leases.')->group(function () {
        Route::get('/', [App\Http\Controllers\Landlord\LeaseController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Landlord\LeaseController::class, 'show'])->name('show');
        Route::get('/create/{property}', [App\Http\Controllers\Landlord\LeaseController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Landlord\LeaseController::class, 'store'])->name('store');
    });

    // Maintenance
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [App\Http\Controllers\Landlord\MaintenanceController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Landlord\MaintenanceController::class, 'show'])->name('show');
    });

    // Messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', function () { return view('landlord.messages.index'); })->name('index');
        Route::get('/{id}', function () { return view('landlord.messages.show'); })->name('show');
    });

    // Payouts
    Route::prefix('payouts')->name('payouts.')->group(function () {
        Route::get('/', [App\Http\Controllers\Landlord\PayoutController::class, 'index'])->name('index');
        Route::get('/request', [App\Http\Controllers\Landlord\PayoutController::class, 'requestPayout'])->name('request');
        Route::post('/', [App\Http\Controllers\Landlord\PayoutController::class, 'store'])->name('store');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () { return view('landlord.reports.index'); })->name('index');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () { return view('landlord.settings.index'); })->name('index');
        Route::post('/', function () { return back(); })->name('update');
    });
});

// ============================================================
// AGENT ROUTES
// ============================================================
Route::prefix('agent')->name('agent.')->middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/dashboard', [AgentDashboard::class, 'index'])->name('dashboard');

    // Properties
    Route::prefix('properties')->name('properties.')->group(function () {
        Route::get('/', [App\Http\Controllers\Agent\PropertyController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Agent\PropertyController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Agent\PropertyController::class, 'store'])->name('store');
    });

    // Applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [App\Http\Controllers\Agent\ApplicationController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Agent\ApplicationController::class, 'show'])->name('show');
    });

    // Tenants/Clients
    Route::prefix('tenants')->name('tenants.')->group(function () {
        Route::get('/', function () { return view('agent.tenants.index'); })->name('index');
        Route::get('/{id}', function () { return view('agent.tenants.show'); })->name('show');
    });

    // Commission
    Route::prefix('commission')->name('commission.')->group(function () {
        Route::get('/', [App\Http\Controllers\Agent\CommissionController::class, 'index'])->name('index');
    });

    // Payouts
    Route::prefix('payouts')->name('payouts.')->group(function () {
        Route::get('/', function () { return view('agent.payouts.index'); })->name('index');
    });

    // Messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', function () { return view('agent.messages.index'); })->name('index');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () { return view('agent.reports.index'); })->name('index');
    });
});

// ============================================================
// TENANT ROUTES
// ============================================================
Route::prefix('tenant')->name('tenant.')->middleware(['auth', 'role:tenant'])->group(function () {
    Route::get('/dashboard', [TenantDashboard::class, 'index'])->name('dashboard');

    // Properties
    Route::prefix('properties')->name('properties.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tenant\PropertyController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Tenant\PropertyController::class, 'show'])->name('show');
    });

    // Applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tenant\ApplicationController::class, 'index'])->name('index');
        Route::post('/{property}/apply', [App\Http\Controllers\Tenant\ApplicationController::class, 'apply'])->name('apply');
    });

    // Rentals
    Route::prefix('rentals')->name('rentals.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tenant\RentalController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Tenant\RentalController::class, 'show'])->name('show');
    });

    // Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tenant\PaymentController::class, 'index'])->name('index');
        Route::get('/make-payment', [App\Http\Controllers\Tenant\PaymentController::class, 'makePayment'])->name('make-payment');
        Route::post('/', [App\Http\Controllers\Tenant\PaymentController::class, 'store'])->name('store');
        Route::get('/history', [App\Http\Controllers\Tenant\PaymentController::class, 'history'])->name('history');
    });

    // Maintenance
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tenant\MaintenanceController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Tenant\MaintenanceController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Tenant\MaintenanceController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Tenant\MaintenanceController::class, 'show'])->name('show');
    });

    // Documents
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', function () { return view('tenant.documents.index'); })->name('index');
    });

    // Messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tenant\MessageController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Tenant\MessageController::class, 'show'])->name('show');
        Route::post('/', [App\Http\Controllers\Tenant\MessageController::class, 'store'])->name('store');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', function () { return view('tenant.notifications.index'); })->name('index');
    });

    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [App\Http\Controllers\Tenant\ReviewController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Tenant\ReviewController::class, 'store'])->name('store');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () { return view('tenant.profile.index'); })->name('index');
        Route::post('/', function () { return back(); })->name('update');
    });
});

// ============================================================
// SUPPORT ROUTES
// ============================================================
Route::prefix('support')->name('support.')->middleware(['auth', 'role:support'])->group(function () {
    Route::get('/dashboard', [SupportDashboard::class, 'index'])->name('dashboard');

    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', function () { return view('support.tickets.index'); })->name('index');
        Route::get('/{id}', function () { return view('support.tickets.show'); })->name('show');
    });

    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', function () { return view('support.chat.index'); })->name('index');
    });

    Route::prefix('knowledge-base')->name('knowledge-base.')->group(function () {
        Route::get('/', function () { return view('support.knowledge-base.index'); })->name('index');
    });

    Route::prefix('faq')->name('faq.')->group(function () {
        Route::get('/', function () { return view('support.faq.index'); })->name('index');
    });

    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', function () { return view('support.messages.index'); })->name('index');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () { return view('support.reports.index'); })->name('index');
    });

    Route::get('/settings', function () { return view('support.settings.index'); })->name('settings');
});

// ============================================================
// MAINTENANCE ROUTES
// ============================================================
Route::prefix('maintenance')->name('maintenance.')->middleware(['auth', 'role:maintenance'])->group(function () {
    Route::get('/dashboard', [MaintenanceDashboard::class, 'index'])->name('dashboard');

    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', function () { return view('maintenance.tasks.index'); })->name('index');
        Route::get('/{id}', function () { return view('maintenance.tasks.show'); })->name('show');
        Route::post('/{id}/status', function () { return back(); })->name('status');
    });

    Route::prefix('schedule')->name('schedule.')->group(function () {
        Route::get('/', function () { return view('maintenance.schedule.index'); })->name('index');
    });

    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', function () { return view('maintenance.messages.index'); })->name('index');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () { return view('maintenance.reports.index'); })->name('index');
    });
});

// ============================================================
// ACCOUNTANT ROUTES
// ============================================================
Route::prefix('accountant')->name('accountant.')->middleware(['auth', 'role:accountant'])->group(function () {
    Route::get('/dashboard', [AccountantDashboard::class, 'index'])->name('dashboard');

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', function () { return view('accountant.payments.index'); })->name('index');
        Route::get('/{id}', function () { return view('accountant.payments.show'); })->name('show');
    });

    Route::prefix('payouts')->name('payouts.')->group(function () {
        Route::get('/', function () { return view('accountant.payouts.index'); })->name('index');
    });

    Route::prefix('revenue')->name('revenue.')->group(function () {
        Route::get('/', function () { return view('accountant.revenue.index'); })->name('index');
    });

    Route::prefix('taxes')->name('taxes.')->group(function () {
        Route::get('/', function () { return view('accountant.taxes.index'); })->name('index');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () { return view('accountant.reports.index'); })->name('index');
    });

    Route::get('/settings', function () { return view('accountant.settings.index'); })->name('settings');
});

// ============================================================
// INVESTOR ROUTES
// ============================================================
Route::prefix('investor')->name('investor.')->middleware(['auth', 'role:investor'])->group(function () {
    Route::get('/dashboard', [InvestorDashboard::class, 'index'])->name('dashboard');

    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/', function () { return view('investor.financial.index'); })->name('index');
    });

    Route::prefix('metrics')->name('metrics.')->group(function () {
        Route::get('/', function () { return view('investor.metrics.index'); })->name('index');
    });

    Route::prefix('growth')->name('growth.')->group(function () {
        Route::get('/', function () { return view('investor.growth.index'); })->name('index');
    });

    Route::prefix('performance')->name('performance.')->group(function () {
        Route::get('/', function () { return view('investor.performance.index'); })->name('index');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () { return view('investor.reports.index'); })->name('index');
    });

    Route::prefix('cap-table')->name('cap-table.')->group(function () {
        Route::get('/', function () { return view('investor.cap-table.index'); })->name('index');
    });

    Route::get('/profile', function () { return view('investor.profile.index'); })->name('profile');
});

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/privacy', function () { return view('privacy'); })->name('privacy');
Route::get('/terms', function () { return view('terms'); })->name('terms');
Route::get('/properties', function () { return view('properties'); })->name('properties');
Route::get('/how-it-works', function () { return view('how-it-works'); })->name('how-it-works');
Route::get('/faq', function () { return view('faq'); })->name('faq');
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
Route::get('/properties/{id}', function ($id) { return view('property-details', ['id' => $id]); })->name('property-details');

// Logout GET support
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout.get');
