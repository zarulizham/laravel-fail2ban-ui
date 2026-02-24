<?php

namespace ZarulIzham\Fail2Ban;

use Illuminate\Routing\Router;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ZarulIzham\Fail2Ban\Commands\Fail2BanCommand;
use ZarulIzham\Fail2Ban\Http\Middleware\AuthorizeFail2Ban;

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
            ->name('fail2ban-ui')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('web')
            ->hasRoute('api')
            ->hasMigration('create_laravel_fail2ban_ui_table')
            ->hasCommand(Fail2BanCommand::class);
    }

    public function packageBooted(): void
    {
        $this->app->make(Router::class)->aliasMiddleware('fail2ban.auth', AuthorizeFail2Ban::class);
    }
}
