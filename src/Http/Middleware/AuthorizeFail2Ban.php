<?php

namespace ZarulIzham\Fail2Ban\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use ZarulIzham\Fail2Ban\Fail2Ban;

class AuthorizeFail2Ban
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! app(Fail2Ban::class)->check($request)) {
            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => 'Forbidden.',
                ], 403);
            }

            abort(403);
        }

        return $next($request);
    }
}
