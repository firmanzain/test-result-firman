<?php

use App\Http\Controllers\ApiHealthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', ApiHealthController::class)->name('api.health');

Route::name('api.')->group(function () {
    Route::prefix('backoffice')->name('backoffice.')->group(fn() => require __DIR__ . '/backoffice/backoffice_index.php');
    Route::prefix('machine')->name('machine.')->group(fn() => require __DIR__ . '/machine/machine_index.php');
});
