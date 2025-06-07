<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/game/continue', [GameController::class, 'continue'])->name('game.continue');
    Route::get('/game/production', [ProductionController::class, 'index'])->name('game.production');
    Route::get('/game/sales', [SalesController::class, 'index'])->name('game.sales');
    Route::get('/game/hr', [HrController::class, 'index'])->name('game.hr');
    Route::get('/game/hr/hired', [HrController::class, 'index_hired'])->name('game.hr.hired');

    Route::prefix('api')->group(function () {
        Route::post('/game/new', [GameController::class, 'create'])->name('api.game.new');
        Route::post('/game/save', [GameController::class, 'store'])->name('api.game.save');
        Route::post('/game/paychecks', [GameController::class, 'paychecks'])->name('api.game.paychecks');
        Route::post('/game/hr/hire', [HrController::class, 'hire'])->name('api.game.hr.hire');
        Route::post('/game/hr/fire', [HrController::class, 'fire'])->name('api.game.hr.fire');
        Route::post('/game/project/create', [ProjectController::class, 'create'])->name('api.game.project.create');
        Route::post('/game/project/assign', [ProjectController::class, 'assign'])->name('api.game.project.assign');
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
