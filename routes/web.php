<?php

use Illuminate\Support\Facades\Route;
use ZarulIzham\Fail2Ban\Http\Controllers\DashboardPageController;
use ZarulIzham\Fail2Ban\Http\Controllers\SpaAssetController;

Route::middleware(['web', 'fail2ban.auth'])
    ->prefix('fail2ban-ui')
    ->name('fail2ban-ui.')
    ->group(function (): void {
        Route::get('/assets/{path}', SpaAssetController::class)
            ->where('path', '.*')
            ->name('assets');

        Route::get('/', DashboardPageController::class)->name('dashboard');
    });
