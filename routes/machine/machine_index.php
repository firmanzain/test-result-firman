<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Key Abilities
|--------------------------------------------------------------------------
|
| Here is the listed const's for API Key Abilities on these routes.
| So on routes section can more clean call and readable.
|
*/

if (!defined('ABILITY_MACHINE_SYSTEM')) {
    define('ABILITY_MACHINE_SYSTEM', App\Enums\SystemAbility::MACHINE->value);
}


Route::name('v1.')->prefix('v1')->group(fn() => require __DIR__ . '/machine_'.'v1.php');
