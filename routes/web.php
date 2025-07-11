<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

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

Route::get('/', [ProjectController::class, 'index']);

Route::middleware('auth')->group(function () {

    Route::resource('projects', ProjectController::class)->except([
        'create', 'edit'
    ]);

    Route::prefix('projects/{project}')->group(function () {

        Route::resource('tasks', TaskController::class)->only([
            'store', 'update', 'destroy'
        ]);

        Route::resource('users', UserController::class)->only([
            'index', 'store', 'destroy'
        ]);
    });

    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
