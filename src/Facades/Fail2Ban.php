<?php

namespace ZarulIzham\Fail2Ban\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ZarulIzham\Fail2Ban\Fail2Ban
 */
class Fail2Ban extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \ZarulIzham\Fail2Ban\Fail2Ban::class;
    }
}
