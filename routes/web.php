<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageUploadController;

Route::get('/', [ImageUploadController::class, 'index']);
Route::post('/upload', [ImageUploadController::class, 'store'])->name('image.upload');
