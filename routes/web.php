<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;
// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [AIController::class, 'index']);
Route::post('/upload', [AIController::class, 'upload'])->name('upload');