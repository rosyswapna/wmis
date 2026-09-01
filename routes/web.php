<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Accountant\HospitalController;
use App\Http\Controllers\Accountant\ClientController;
use App\Http\Controllers\Accountant\ServiceController;
use App\Http\Controllers\Accountant\InvoiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Accountant\ReportController;
use App\Http\Controllers\NotificationController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    //return view('dashboard');

    $role = auth()->user()->role;

    return match ($role) {
        'accountant' => redirect()->route('accountant.dashboard'),
        default      => view('dashboard'),
    };

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    Route::get('/notifications',[NotificationController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markNotificationAsRead'])->name('notifications.read');
});


Route::middleware(['auth', 'role:system admin'])->group(function () {
    // Show the form to create a user
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    
    // Save the new user to the database
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');

    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    
});


Route::middleware(['auth', 'role:accountant'])->group(function () {

    Route::get('/dashboard/monthly-sales', [DashboardController::class, 'monthlySales'])->name('dashboard.monthly-sales');

    // Show the form to create a user
    Route::get('accountant/dashboard', [UserController::class, 'create'])->name('accountant.dashboard');

    Route::get('/hospital', [HospitalController::class, 'index'])->name('hospital');
    Route::get('/hospital/edit', [HospitalController::class, 'edit'])->name('hospital.edit');
    Route::patch('/hospital/{id}', [HospitalController::class, 'store'])->name('hospital.store');   
        
    Route::get('/accountant/clients', [ClientController::class, 'index'])->name('clients');
    Route::get('/accountant/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/accountant/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/accountant/clients/edit/{id}', [ClientController::class, 'edit'])->name('clients.edit');
    Route::patch('/accountant/clients/{id}', [ClientController::class, 'update'])->name('clients.update'); 
    Route::delete('/accountant/clients/delete/{id}', [ClientController::class, 'delete'])->name('clients.delete');

    Route::get('/accountant/services', [ServiceController::class, 'index'])->name('services');
    Route::get('/accountant/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/accountant/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/accountant/services/edit/{id}', [ServiceController::class, 'edit'])->name('services.edit');
    Route::patch('/accountant/services/{id}', [ServiceController::class, 'update'])->name('services.update'); 
    Route::delete('/accountant/services/delete/{id}', [ServiceController::class, 'delete'])->name('services.delete');

    Route::get('/accountant/invoices', [InvoiceController::class, 'index'])->name('invoices');
    Route::get('/accountant/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/accountant/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/accountant/invoices/edit/{id}', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::patch('/accountant/invoices/{id}', [InvoiceController::class, 'update'])->name('invoices.update'); 
    Route::get('/accountant/invoices/show/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/accountant/invoices/print/{id}', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::delete('/accountant/invoices/cancel/{id}', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
    Route::post('/accountant/invoices/draft', [InvoiceController::class, 'draft'])->name('invoices.draft');
    
    Route::get('/accountant/reports/workers', [ReportController::class, 'workers'])->name('reports.workers');

    Route::get('/accountant/reports/workers/export', [ReportController::class, 'exportWorkers'])->name('reports.workers.export');
    Route::get('/reports/workers/export', [ReportController::class, 'exportWorkers'])->name('reports.workers.export');

    Route::get('/reports/workers/export/{id}/download/{notification}',[ReportController::class,'downloadWorkersExport']
)->name('reports.workers.download');

});


require __DIR__.'/auth.php';
