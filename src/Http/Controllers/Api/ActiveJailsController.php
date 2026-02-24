<?php

namespace ZarulIzham\Fail2Ban\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use ZarulIzham\Fail2Ban\Fail2Ban;

class ActiveJailsController
{
    public function __invoke(Fail2Ban $fail2Ban): JsonResponse
    {
        $activeJails = $fail2Ban->activeJail();

        return response()->json([
            'label' => 'Active Jails',
            'count' => $activeJails['number_of_jail'],
            'jail_list' => $activeJails['jail_list'],
            'raw_output' => $activeJails['raw_output'],
        ]);
    }
}
