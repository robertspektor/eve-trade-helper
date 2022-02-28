<?php

declare(strict_types=1);
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['status' => 'OK', 'timestamp' => Carbon::now()]);
});

Route::fallback(function () {
    abort(404, 'API resource not found');
});
