<?php

namespace ZarulIzham\Fail2Ban\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use ZarulIzham\Fail2Ban\Fail2Ban;

class OverviewController
{
    public function __invoke(Fail2Ban $fail2Ban): JsonResponse
    {
        $overview = $fail2Ban->overview();

        return response()->json([
            'title' => 'Overview Active Jails and Blocks',
            'subtitle' => 'Filter banned IPs or manage jail configuration.',
            'number_of_jail' => $overview['number_of_jail'],
            'jail_list' => $overview['jail_list'],
            'rows' => $overview['rows'],
            'raw_output' => $overview['raw_output'],
        ]);
    }
}
