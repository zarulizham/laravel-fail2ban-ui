<?php

namespace ZarulIzham\Fail2Ban\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ZarulIzham\Fail2Ban\Fail2Ban;

class UnbanIpController
{
    public function __invoke(Request $request, Fail2Ban $fail2Ban): JsonResponse
    {
        $validated = $request->validate([
            'service_name' => ['required', 'string'],
            'ip' => ['required', 'ip'],
        ]);

        $result = $fail2Ban->unbanIp($validated['service_name'], $validated['ip']);

        if (! $result['ok']) {
            return response()->json([
                'message' => 'Failed to unban IP.',
                'output' => $result['output'],
            ], 422);
        }

        return response()->json([
            'message' => 'IP unbanned successfully.',
            'output' => $result['output'],
        ]);
    }
}
