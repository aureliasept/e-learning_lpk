<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini kamu bisa daftarkan route untuk API. Route-route ini akan
| otomatis diberi prefix "/api" oleh Laravel.
|
*/

// Contoh route default: GET /api/user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
