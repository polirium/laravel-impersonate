<?php

use Illuminate\Support\Facades\Route;
use Polirium\Impersonate\Controllers\ImpersonateController;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/impersonate/take/{id}/{guardName?}', [ImpersonateController::class, 'take'])->name('impersonate');
    Route::get('/impersonate/leave', [ImpersonateController::class, 'leave'])->name('impersonate.leave');
});
