<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Client\BillingController;
use App\Http\Controllers\Client\TicketController;
use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\NetworkController;
use App\Http\Controllers\Admin\BillingController as AdminBillingController;
use App\Http\Controllers\Admin\BillingScheduleController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\BandwidthController;
use App\Http\Controllers\Admin\PaymentSettingController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Password Reset Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Client Portal Routes
    Route::prefix('client')->name('client.')->group(function () {
        // Billing & Payments
        Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
        Route::post('/billing/payment/submit', [BillingController::class, 'submitPayment'])
            ->middleware('throttle:5,1')
            ->name('billing.payment.submit');
        Route::get('/billing/{invoice}/pay', [BillingController::class, 'pay'])->name('billing.pay');
        
        // Support Tickets
        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
        
        // Service Management
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::post('/services/upgrade/{plan}', [ServiceController::class, 'upgrade'])->name('services.upgrade');
        Route::post('/services/addon/{addon}', [ServiceController::class, 'purchaseAddon'])->name('services.purchase-addon');
        
        // Account Settings
        Route::get('/account', [AccountController::class, 'index'])->name('account.index');
        Route::put('/account', [AccountController::class, 'update'])->name('account.update');
        Route::put('/account/password', [AccountController::class, 'changePassword'])->name('account.password');
        Route::post('/account/wifi', [AccountController::class, 'updateWifiPassword'])->name('account.wifi-password');
        Route::put('/account/notifications', [AccountController::class, 'updateNotifications'])->name('account.notifications');
    });

    // Admin Panel Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Customer Management
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/create', [AdminCustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [AdminCustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
        Route::get('/customers/{customer}/edit', [AdminCustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [AdminCustomerController::class, 'update'])->name('customers.update');
        Route::post('/customers/{customer}/suspend', [AdminCustomerController::class, 'suspend'])->name('customers.suspend');
        Route::post('/customers/{customer}/activate', [AdminCustomerController::class, 'activate'])->name('customers.activate');
        Route::get('/payments/{payment}/receipt', [AdminCustomerController::class, 'downloadReceipt'])->name('payments.receipt');
        Route::delete('/customers/{customer}', [AdminCustomerController::class, 'destroy'])->name('customers.destroy');
        
        // Network Monitoring (Keep for other purposes)
        Route::get('/network', [NetworkController::class, 'index'])->name('network.index');
        Route::get('/network/{device}', [NetworkController::class, 'show'])->name('network.show');
        Route::post('/network/scan', [NetworkController::class, 'scan'])->name('network.scan');
        
        // Billing Schedules (Replaces Network Monitoring in sidebar)
        Route::get('/billing-schedules', [BillingScheduleController::class, 'index'])->name('billing-schedules.index');
        Route::get('/billing-schedules/export-summary', [BillingScheduleController::class, 'exportSummary'])->name('billing-schedules.export-summary');
        Route::get('/billing-schedules/export-calendar', [BillingScheduleController::class, 'exportCalendar'])->name('billing-schedules.export-calendar');
        Route::get('/billing-schedules/{invoice}', [BillingScheduleController::class, 'show'])->name('billing-schedules.show');
        Route::post('/billing-schedules/{invoice}/status', [BillingScheduleController::class, 'updateStatus'])->name('billing-schedules.update-status');
        Route::post('/billing-schedules/{invoice}/due-date', [BillingScheduleController::class, 'updateDueDate'])->name('billing-schedules.update-due-date');
        Route::post('/billing-schedules/{invoice}/send-email', [BillingScheduleController::class, 'sendEmail'])->name('billing-schedules.send-email');
        Route::get('/billing-schedules/{invoice}/download-pdf', [BillingScheduleController::class, 'downloadPdf'])->name('billing-schedules.download-pdf');
        
        // Bandwidth Control
        Route::get('/bandwidth', [BandwidthController::class, 'index'])->name('bandwidth.index');
        Route::get('/bandwidth/create', [BandwidthController::class, 'create'])->name('bandwidth.create');
        Route::post('/bandwidth', [BandwidthController::class, 'store'])->name('bandwidth.store');
        Route::get('/bandwidth/{policy}/edit', [BandwidthController::class, 'edit'])->name('bandwidth.edit');
        Route::put('/bandwidth/{policy}', [BandwidthController::class, 'update'])->name('bandwidth.update');
        
        // Billing & Finance
        Route::get('/billing', [AdminBillingController::class, 'index'])->name('billing.index');
        Route::get('/billing/invoices', [AdminBillingController::class, 'invoices'])->name('billing.invoices');
        Route::post('/billing/invoices/generate', [AdminBillingController::class, 'generateInvoices'])->name('billing.invoices.generate');
        Route::get('/billing/reports', [AdminBillingController::class, 'reports'])->name('billing.reports');
        
        // Inventory Management
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/inventory/{item}/assign', [InventoryController::class, 'assign'])->name('inventory.assign');
        Route::post('/inventory/{item}/assign', [InventoryController::class, 'processAssignment'])->name('inventory.process-assignment');
        
        // Payment Settings
        Route::get('/settings/payment', [PaymentSettingController::class, 'index'])->name('settings.payment.index');
        Route::post('/settings/payment', [PaymentSettingController::class, 'update'])->name('settings.payment.update');
    });

    // Payment Management Routes (Limited Access - No Delete)
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
});
