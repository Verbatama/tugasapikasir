<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\KasirController;
use App\Http\Controllers\Api\PenjualanController;

Route::apiResource('barangs', App\Http\Controllers\Api\BarangController::class);
Route::apiResource('kasirs', App\Http\Controllers\Api\KasirController::class);
Route::apiResource('penjualans', App\Http\Controllers\Api\PenjualanController::class);