<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TradeController;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::middleware([
    StartSession::class
])->group(function() {
    Route::get('/auth/callback', [AuthController::class, 'callback']);
    Route::get('/auth/login', [AuthController::class, 'login']);
});

Route::get('/', [StatusController::class, 'status']);
Route::get('/trades', [TradeController::class, 'get']);

Route::patch('/types/{typeId}', [TradeController::class, 'updateTypeById']);

Route::fallback(function () {
    abort(404, 'API resource not found');
});
