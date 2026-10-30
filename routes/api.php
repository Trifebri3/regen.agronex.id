<?php

use App\Http\Controllers\Enterprise\EnterpriseDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('/health', fn () => response()->json([
        'platform' => 'REGEN by AGRONEX',
        'status' => 'ok',
        'version' => '1.0.0',
    ]))->name('health');

    Route::middleware(['web', 'auth', 'portal:enterprise'])->group(function (): void {
        Route::get('/enterprise/summary', [EnterpriseDashboardController::class, 'apiSummary'])
            ->name('enterprise.summary');
    });
});
