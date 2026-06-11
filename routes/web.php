<?php

use App\Http\Controllers\Api\BarcodeScanController;
use App\Http\Controllers\Api\ColdstoreJobsController;
use App\Http\Controllers\Api\ColdstoreOverviewController;
use App\Http\Controllers\ColdstoreDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ColdstoreDashboardController::class, 'index'])->name('coldstore.dashboard');
Route::get('/scanner', [ColdstoreDashboardController::class, 'scanner'])->name('coldstore.scanner');
Route::get('/settings', [ColdstoreDashboardController::class, 'settings'])->name('coldstore.settings');

Route::get('/api/coldstore/overview', ColdstoreOverviewController::class)->name('api.coldstore.overview');
Route::get('/api/coldstore/jobs', ColdstoreJobsController::class)->name('api.coldstore.jobs');
Route::post('/api/coldstore/barcodes', BarcodeScanController::class)->name('api.coldstore.barcodes.store');
