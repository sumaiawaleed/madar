<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EarningsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\LinkPositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectStageController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\PublicLinkPageController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/u/{handle}', PublicLinkPageController::class)->name('link-page.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:6,1');
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/earnings', EarningsController::class)->name('earnings');

    Route::resource('clients', ClientController::class)->except('show');
    Route::resource('proposals', ProposalController::class);
    Route::resource('projects', ProjectController::class)->except('show');
    Route::patch('/projects/{project}/stage', [ProjectStageController::class, 'update'])->name('projects.stage.update');
    Route::resource('invoices', InvoiceController::class);
    Route::post('/invoices/{invoice}/payment', [InvoicePaymentController::class, 'store'])->name('invoices.payment.store');
    Route::resource('appointments', AppointmentController::class)->except('show');
    Route::resource('links', LinkController::class)->except('show');
    Route::patch('/links/{link}/position', [LinkPositionController::class, 'update'])->name('links.position.update');

    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/settings', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/workspace', [WorkspaceController::class, 'destroy'])->name('workspace.destroy');
});
