<?php

namespace ZarulIzham\Fail2Ban\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

class NewLastHourController
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'label' => 'New Last Hour',
            'count' => 12,
        ]);
    }
}
