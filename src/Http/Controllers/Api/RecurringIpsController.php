<?php

namespace ZarulIzham\Fail2Ban\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

class RecurringIpsController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'label' => 'Recurring IPs (7 days)',
            'count' => 26,
        ]);
    }
}
