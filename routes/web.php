<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageUploadController;

Route::get('/', [ImageUploadController::class, 'index']);
Route::post('/upload', [ImageUploadController::class, 'store'])->name('image.upload');

Route::get('images', [ImageUploadController::class, 'index'])->name('images.index');
Route::post('images', [ImageUploadController::class, 'store'])->name('images.store');
Route::get('images/{id}/edit', [ImageUploadController::class, 'edit'])->name('images.edit');

Route::put('images/{id}', [ImageUploadController::class, 'update'])->name('images.update');
Route::delete('images/{id}', [ImageUploadController::class, 'destroy'])->name('images.destroy');