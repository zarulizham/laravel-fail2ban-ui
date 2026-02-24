<?php

namespace ZarulIzham\Fail2Ban;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ZarulIzham\Fail2Ban\Commands\Fail2BanCommand;

class Fail2BanServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-fail2ban-ui')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_fail2ban_ui_table')
            ->hasCommand(Fail2BanCommand::class);
    }
}
