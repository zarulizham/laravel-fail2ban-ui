<?php

namespace ZarulIzham\Fail2Ban\Http\Controllers;

use Illuminate\View\View;

class DashboardPageController
{
    public function __invoke(): View
    {
        return view('fail2ban-ui::layouts.fail2ban');
    }
}
