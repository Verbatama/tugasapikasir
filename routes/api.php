<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\KasirController;
use App\Http\Controllers\Api\PenjualanController;
use App\Http\Controllers\AuthController;


Route::apiResource('barangs', App\Http\Controllers\Api\BarangController::class)->middleware('auth:sanctum');
Route::apiResource('kasirs', App\Http\Controllers\Api\KasirController::class);
Route::apiResource('penjualans', App\Http\Controllers\Api\PenjualanController::class);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
