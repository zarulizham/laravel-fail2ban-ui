<?php

use Illuminate\Support\Facades\Route;
use ZarulIzham\Fail2Ban\Http\Controllers\Api\ActiveJailsController;
use ZarulIzham\Fail2Ban\Http\Controllers\Api\NewLastHourController;
use ZarulIzham\Fail2Ban\Http\Controllers\Api\OverviewController;
use ZarulIzham\Fail2Ban\Http\Controllers\Api\RecurringIpsController;
use ZarulIzham\Fail2Ban\Http\Controllers\Api\TotalBannedIpsController;
use ZarulIzham\Fail2Ban\Http\Controllers\Api\UnbanIpController;

Route::middleware(['web', 'fail2ban.auth'])
    ->prefix('api/fail2ban-ui')
    ->name('fail2ban-ui.api.')
    ->group(function (): void {
        Route::get('/active-jails', ActiveJailsController::class)->name('active-jails');
        Route::get('/total-banned-ips', TotalBannedIpsController::class)->name('total-banned-ips');
        Route::get('/new-last-hour', NewLastHourController::class)->name('new-last-hour');
        Route::get('/recurring-ips', RecurringIpsController::class)->name('recurring-ips');
        Route::get('/overview', OverviewController::class)->name('overview');
        Route::post('/unban-ip', UnbanIpController::class)->name('unban-ip');
    });
