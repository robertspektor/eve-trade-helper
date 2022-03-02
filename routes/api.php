<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::middleware([
    StartSession::class
])->group(function() {
    Route::get('/auth/callback', [AuthController::class, 'callback']);
    Route::get('/auth/login', [AuthController::class, 'login']);
});



Route::get('/', function () {
    return response()->json(['status' => 'OK', 'timestamp' => Carbon::now()]);
});

Route::fallback(function () {
    abort(404, 'API resource not found');
});
