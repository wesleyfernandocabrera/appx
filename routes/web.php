<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MacroController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    // Usuários
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/create', [UserController::class, 'store'])->name('store');
        Route::get('/export', [UserController::class, 'export'])->name('export');
        Route::get('/export/pdf', [UserController::class, 'exportPdf'])->name('export.pdf');

        Route::get('/{user}', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::put('/{user}/profile', [UserController::class, 'updateProfile'])->name('updateProfile');
        Route::put('/{user}/interests', [UserController::class, 'updateInterests'])->name('updateInterests');
        Route::put('/{user}/roles', [UserController::class, 'updateRoles'])->name('updateRoles');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Recursos padrão
    Route::resource('sectors', SectorController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('macros', MacroController::class);

    // Documentos
    Route::resource('documents', DocumentController::class)->except(['show']);
    
    // Funcionalidades extras de documentos
    Route::post('/documents/{document}/toggle-lock', [DocumentController::class, 'toggleLock'])->name('documents.toggle-lock');
    Route::get('/documents/trash', [DocumentController::class, 'trash'])->name('documents.trash');
    Route::post('/documents/{id}/restore', [DocumentController::class, 'restore'])->name('documents.restore');
});
