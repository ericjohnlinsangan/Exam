<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    //users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/create-user', [UserController::class, 'create'])->name('users.create');
    Route::post('/create-user', [UserController::class, 'store'])->name('users.store');
    Route::get('/edit-user/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/edit-user/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/delete-user/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    //roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/create-roles', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/create-roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/create-roles/{role}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/create-roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/delete-roles/{user}', [RoleController::class, 'destroy'])->name('roles.destroy');

    //permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    
});

Auth::routes();