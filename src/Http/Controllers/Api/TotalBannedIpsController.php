<?php

namespace ZarulIzham\Fail2Ban\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

class TotalBannedIpsController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'label' => 'Total Banned IPs',
            'count' => 578,
        ]);
    }
}
