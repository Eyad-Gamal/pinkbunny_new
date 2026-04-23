<?php

namespace App\Http\Middleware;

use App\Services\VisitorTrackingService;
use Closure;
use Illuminate\Http\Request;

class TrackVisitor
{
    public function __construct(private VisitorTrackingService $tracker) {}

    public function handle(Request $request, Closure $next)
    {
        if (!$request->is('admin/*') && !$request->is('api/*') && !$request->expectsJson()) {
            try {
                $this->tracker->track($request);
            } catch (\Exception) {
                // Silent fail — never break page load
            }
        }

        return $next($request);
    }
}
