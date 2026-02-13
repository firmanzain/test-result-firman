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

if (!defined('ABILITY_BACKOFFICE_SYSTEM')) {
    define('ABILITY_BACKOFFICE_SYSTEM', App\Enums\SystemAbility::BACKOFFICE->value);
}

Route::name('v1.')->prefix('v1')->group(fn() => require __DIR__ . '/backoffice_' . 'v1.php');
