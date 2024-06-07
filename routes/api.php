<?php

use App\Http\Controllers\Api\AuthController;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// http://localhost:8000/api/
// Universal Resource Locator (URL)
// tickets
// users

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->post('/logout', [AuthController::class, 'logout']);


Route::middleware(['auth:sanctum'])->get('/tickets', function(){
    return Ticket::all();
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
