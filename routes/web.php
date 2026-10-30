<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDocumentController;
use App\Http\Controllers\Admin\AdminMediaController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminPrototypeController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Enterprise\EnterpriseDashboardController;
use App\Http\Controllers\Farm\FarmDashboardController;
use App\Http\Controllers\Farm\FarmDeviceController;
use App\Http\Controllers\Farm\FarmOperationsController;
use App\Http\Controllers\PublicCmsController;
use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Route;

// Public Product Website & Dynamic CMS Routes
Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/solutions', [PublicSiteController::class, 'solutions'])->name('solutions.index');
Route::get('/solutions/soilsense', [PublicSiteController::class, 'soilsense'])->name('solutions.soilsense');
Route::get('/solutions/watersense', [PublicSiteController::class, 'watersense'])->name('solutions.watersense');
Route::get('/solutions/envirosense', [PublicSiteController::class, 'envirosense'])->name('solutions.envirosense');
Route::get('/solutions/terra', [PublicSiteController::class, 'terra'])->name('solutions.terra');
Route::get('/solutions/{slug}', [PublicSiteController::class, 'showSolution'])->name('solutions.show');

Route::get('/platform', [PublicSiteController::class, 'platform'])->name('platform');
Route::get('/enterprise', [PublicSiteController::class, 'enterprise'])->name('enterprise');

Route::get('/prototype', [PublicCmsController::class, 'prototypes'])->name('prototype.index');
Route::get('/prototype/{prototype:slug}', [PublicCmsController::class, 'showPrototype'])->name('prototype.show');

Route::get('/pendukung', [PublicCmsController::class, 'documents'])->name('pendukung.index');
Route::get('/pendukung/{supportingDocument:slug}/download', [PublicCmsController::class, 'downloadDocument'])->name('pendukung.download');

Route::get('/about', [PublicSiteController::class, 'about'])->name('about');
Route::get('/contact', [PublicSiteController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicSiteController::class, 'submitContact'])
    ->middleware('throttle:contact')
    ->name('contact.submit');

// Authentication Routes
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login')
        ->name('login.attempt');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Centralized Administrator Dashboard (/admin) — Strict Role & Section Enforcement
Route::prefix('admin')->name('admin.')->middleware(['auth', 'portal:admin'])->group(function (): void {
    Route::get('/', [AdminDashboardController::class, 'overview'])
        ->middleware('portal:admin,overview')
        ->name('overview');

    Route::middleware('portal:admin,website_content')->group(function (): void {
        Route::get('/website-content', [AdminDashboardController::class, 'websiteContent'])->name('website-content');
        Route::put('/website-content/{siteContent}', [AdminDashboardController::class, 'updateWebsiteContent'])->name('website-content.update');
    });

    Route::middleware('portal:admin,products')->group(function (): void {
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    });

    Route::middleware('portal:admin,prototypes')->group(function (): void {
        Route::get('/prototypes', [AdminPrototypeController::class, 'index'])->name('prototypes.index');
        Route::post('/prototypes', [AdminPrototypeController::class, 'store'])->name('prototypes.store');
        Route::put('/prototypes/{prototype}', [AdminPrototypeController::class, 'update'])->name('prototypes.update');
        Route::delete('/prototypes/{prototype}', [AdminPrototypeController::class, 'destroy'])->name('prototypes.destroy');
    });

    Route::middleware('portal:admin,documents')->group(function (): void {
        Route::get('/documents', [AdminDocumentController::class, 'index'])->name('documents.index');
        Route::post('/documents', [AdminDocumentController::class, 'store'])->name('documents.store');
        Route::put('/documents/{supportingDocument}', [AdminDocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{supportingDocument}', [AdminDocumentController::class, 'destroy'])->name('documents.destroy');
        Route::get('/documents/{supportingDocument}/download', [AdminDocumentController::class, 'download'])->name('documents.download');
    });

    Route::middleware('portal:admin,media')->group(function (): void {
        Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
        Route::post('/media', [AdminMediaController::class, 'store'])->name('media.store');
        Route::delete('/media/{mediaItem}', [AdminMediaController::class, 'destroy'])->name('media.destroy');
    });

    Route::middleware('portal:admin,users')->group(function (): void {
        Route::get('/users', [AdminUserController::class, 'users'])->name('users.index');
        Route::post('/users', [AdminUserController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [AdminUserController::class, 'updateUser'])->name('users.update');
    });

    Route::middleware('portal:admin,farms')->group(function (): void {
        Route::get('/farms', [AdminDashboardController::class, 'farms'])->name('farms.index');
    });

    Route::middleware('portal:admin,devices')->group(function (): void {
        Route::get('/devices', [AdminDashboardController::class, 'devices'])->name('devices.index');
    });

    Route::middleware('portal:admin,enterprise')->group(function (): void {
        Route::get('/enterprise', [AdminUserController::class, 'organizations'])->name('enterprise.index');
        Route::post('/enterprise', [AdminUserController::class, 'storeOrganization'])->name('enterprise.store');
    });

    Route::middleware('portal:admin,contacts')->group(function (): void {
        Route::get('/contacts', [AdminDashboardController::class, 'contacts'])->name('contacts.index');
        Route::patch('/contacts/{contactRequest}', [AdminDashboardController::class, 'updateContact'])->name('contacts.update');
    });

    Route::middleware('portal:admin,reports')->group(function (): void {
        Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports.index');
        Route::get('/reports/export', [AdminDashboardController::class, 'exportBackupManifest'])->name('reports.export');
    });

    Route::middleware('portal:admin,settings')->group(function (): void {
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings.index');
    });
});

// Farm Management Application (/app) — Strict Tenant Isolation
Route::prefix('app')->name('app.')->middleware(['auth', 'portal:farm'])->group(function (): void {
    Route::get('/', [FarmDashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/lands', [FarmOperationsController::class, 'lands'])->name('lands.index');
    Route::post('/lands', [FarmOperationsController::class, 'storeLand'])
        ->middleware('portal:farm_manage')
        ->name('lands.store');
    Route::post('/lands/{land}/plots', [FarmOperationsController::class, 'storePlot'])
        ->middleware('portal:farm_manage')
        ->name('lands.plots.store');
    Route::delete('/lands/{land}', [FarmOperationsController::class, 'destroyLand'])
        ->middleware('portal:farm_manage')
        ->name('lands.destroy');

    Route::get('/crops', [FarmOperationsController::class, 'crops'])->name('crops.index');
    Route::post('/crops', [FarmOperationsController::class, 'storeCrop'])->name('crops.store');
    Route::patch('/crops/{cropCycle}', [FarmOperationsController::class, 'updateCropStatus'])->name('crops.update');

    Route::get('/activities', [FarmOperationsController::class, 'activities'])->name('activities.index');
    Route::post('/activities', [FarmOperationsController::class, 'storeActivity'])->name('activities.store');

    Route::get('/inputs', [FarmOperationsController::class, 'inputs'])->name('inputs.index');
    Route::post('/inputs', [FarmOperationsController::class, 'storeInput'])->name('inputs.store');

    Route::get('/harvests', [FarmOperationsController::class, 'harvests'])->name('harvests.index');
    Route::post('/harvests', [FarmOperationsController::class, 'storeHarvest'])->name('harvests.store');

    Route::get('/sales', [FarmOperationsController::class, 'sales'])->name('sales.index');
    Route::post('/sales', [FarmOperationsController::class, 'storeSale'])->name('sales.store');

    Route::get('/finance', [FarmOperationsController::class, 'finance'])->name('finance.index');
    Route::post('/finance', [FarmOperationsController::class, 'storeFinancialRecord'])->name('finance.store');

    Route::get('/intelligence', [FarmDashboardController::class, 'intelligence'])->name('intelligence');
    Route::post('/intelligence', [FarmDashboardController::class, 'intelligence'])->name('intelligence.calculate');
    Route::get('/intelligence/export', [FarmDashboardController::class, 'exportIntelligenceCsv'])->name('intelligence.export');

    Route::get('/devices', [FarmDeviceController::class, 'index'])->name('devices.index');
    Route::post('/devices', [FarmDeviceController::class, 'store'])
        ->middleware('portal:farm_manage')
        ->name('devices.store');
    Route::post('/devices/{device}/readings', [FarmDeviceController::class, 'storeReading'])->name('devices.readings.store');

    Route::get('/learning', [FarmDashboardController::class, 'learning'])->name('learning.index');
    Route::get('/learning/{learningArticle:slug}', [FarmDashboardController::class, 'showLearning'])->name('learning.show');
});

// Enterprise Dashboard (/enterprise/dashboard) — Multi-Farm Organization Console
Route::prefix('enterprise')->name('enterprise.')->middleware(['auth', 'portal:enterprise'])->group(function (): void {
    Route::get('/dashboard', [EnterpriseDashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/farms', [EnterpriseDashboardController::class, 'storeFarm'])->name('farms.store');
    Route::get('/dashboard/export', [EnterpriseDashboardController::class, 'exportCsv'])->name('export');
});
