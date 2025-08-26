<?php

use App\Http\Controllers\RoleController;
use Orion\Facades\Orion;

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    Orion::resource('roles', RoleController::class);
});
