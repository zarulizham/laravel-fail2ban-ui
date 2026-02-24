<?php

namespace ZarulIzham\Fail2Ban\Commands;

use Illuminate\Console\Command;

class Fail2BanCommand extends Command
{
    public $signature = 'laravel-fail2ban-ui';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
