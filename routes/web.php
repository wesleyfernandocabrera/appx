<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\CompanyController;

    Route::middleware(['auth'])->group(function(){
    Route::get('/', function () {
        return view('home');
    })->name('home');
    
    Route::get('/users', [UserController::class, 'index'])->name('users.index');  
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');   
    Route::post('/users/create', [UserController::class, 'store'])->name('users.store');   
    
    Route::get('/users/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    Route::put('/users/{user}/profile', [UserController::class, 'updateProfile'])->name('users.updateProfile');
    Route::put('/users/{user}/interests', [UserController::class, 'updateInterests'])->name('users.updateInterests');
    
    Route::put('/users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.updateRoles');
    
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    Route::resource('sectors', SectorController::class);

    Route::resource('companies', CompanyController::class);


});