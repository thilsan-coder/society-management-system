<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicWebsiteController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SanthaController;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::get('/', [PublicWebsiteController::class, 'home'])->name('public.home');
Route::get('/about', [PublicWebsiteController::class, 'about'])->name('public.about');
Route::get('/committee', [PublicWebsiteController::class, 'committee'])->name('public.committee');
Route::get('/public-media', [PublicWebsiteController::class, 'media'])->name('public.media');
Route::get('/public-reports', [PublicWebsiteController::class, 'reports'])->name('public.reports');
Route::get('/contact', [PublicWebsiteController::class, 'contact'])->name('public.contact');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Application Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Members Directory & Account Management
    Route::resource('members', MemberController::class);

    // Meetings Management
    Route::resource('meetings', MeetingController::class);
    Route::post('/meetings/{meeting}/attendance', [MeetingController::class, 'recordAttendance'])->name('meetings.attendance');
    Route::post('/meetings/{meeting}/transition', [MeetingController::class, 'transitionStatus'])->name('meetings.transition');

    // Financials: Santha, Fines, Payments, Expenses
    Route::get('/santhas', [SanthaController::class, 'index'])->name('santhas.index');

    Route::get('/fines', [FineController::class, 'index'])->name('fines.index');
    Route::post('/fines/spot', [FineController::class, 'storeSpotFine'])->name('fines.spot');
    Route::post('/fines/default', [FineController::class, 'storeDefaultFine'])->name('fines.default');
    Route::post('/fines/{fine}/double', [FineController::class, 'doubleSpotFine'])->name('fines.double');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/unpaid-items/{member}', [PaymentController::class, 'getUnpaidItems'])->name('payments.unpaid');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/pdf', [PaymentController::class, 'pdf'])->name('payments.pdf');

    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generateFinancialReport'])->name('reports.generate');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/transition', [ReportController::class, 'transitionStatus'])->name('reports.transition');
    Route::get('/reports/{report}/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');

    // Posters & Media
    Route::resource('media', MediaController::class);
    Route::post('/media/{media}/transition', [MediaController::class, 'transitionStatus'])->name('media.transition');

    // President Governance & Security Audit
    Route::get('/approvals', [ApprovalController::class, 'index'])->middleware('role:president')->name('approvals.index');
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->middleware('role:president')->name('audit-logs.index');
});
