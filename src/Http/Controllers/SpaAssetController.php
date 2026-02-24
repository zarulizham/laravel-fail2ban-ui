<?php

namespace ZarulIzham\Fail2Ban\Http\Controllers;

use Illuminate\Http\Response;

class SpaAssetController
{
    public function __invoke(string $path): Response
    {
        $normalizedPath = trim($path, '/');

        if ($normalizedPath === '' || str_contains($normalizedPath, '..')) {
            abort(404);
        }

        $fullPath = dirname(__DIR__, 3).'/resources/spa/'.$normalizedPath;

        if (! is_file($fullPath)) {
            abort(404);
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        $contentType = match ($extension) {
            'js' => 'application/javascript; charset=UTF-8',
            'vue' => 'text/plain; charset=UTF-8',
            'css' => 'text/css; charset=UTF-8',
            default => 'text/plain; charset=UTF-8',
        };

        return response(file_get_contents($fullPath), 200, [
            'Content-Type' => $contentType,
        ]);
    }
}
