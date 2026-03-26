<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\IssueVoucherController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnItemController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
    Route::resource('products', ProductController::class)->except(['show', 'create']);
    Route::resource('vendors', VendorController::class)->except(['show', 'create', 'edit']);
    Route::resource('employees', EmployeeController::class)->except(['show', 'create']);

    Route::resource('purchases', PurchaseController::class)->except(['show', 'create', 'edit', 'update']);
    Route::resource('issues', IssueVoucherController::class)->except(['show', 'create', 'edit', 'update']);
    Route::resource('returns', ReturnItemController::class)->except(['show', 'create', 'edit', 'update', 'destroy']);

    Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/employee-issues', [ReportController::class, 'employeeIssues'])->name('reports.employee-issues');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
