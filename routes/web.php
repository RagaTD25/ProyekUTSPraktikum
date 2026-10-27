<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KegiatanController;

Route::get('/kegiatan', [KegiatanController::class, 'index'])
    ->name('kegiatan.index');

Route::post('/kegiatan', [KegiatanController::class, 'store'])
    ->name('kegiatan.store');

Route::get('/kegiatan/{id}/edit', [KegiatanController::class, 'edit'])
    ->whereNumber('id')
    ->name('kegiatan.edit');

Route::put('/kegiatan/{id}', [KegiatanController::class, 'update'])
    ->whereNumber('id')
    ->name('kegiatan.update');

Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy'])
    ->whereNumber('id')
    ->name('kegiatan.destroy');