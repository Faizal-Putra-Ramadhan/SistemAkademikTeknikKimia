<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Middleware untuk rate limiting operasi sensitif
 * Mencegah brute force attacks pada operasi create, update, delete
 */
class ThrottleSensitiveOperations
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * Limits:
     * - User creation: 10 per minute
     * - User updates: 20 per minute
     * - Password reset: 5 per minute
     * - User deletion: 5 per minute
     */
    public function handle(Request $request, Closure $next)
    {
        // Determine operation type and set appropriate limits
        $operation = $this->getOperationType($request);
        $limits = $this->getLimits($operation);

        if (! $limits) {
            return $next($request);
        }

        // Generate unique key based on user and operation
        $key = $this->resolveRequestSignature($request, $operation);

        // Check if rate limit is exceeded
        if ($this->limiter->tooManyAttempts($key, $limits['max_attempts'])) {
            $seconds = $this->limiter->availableIn($key);

            Log::warning('Rate limit exceeded for sensitive operation', [
                'operation' => $operation,
                'ip' => $request->ip(),
                'user' => auth()->user()->UserID ?? 'guest',
                'retry_after' => $seconds,
            ]);

            return redirect()->back()
                ->with('error', "Terlalu banyak percobaan. Silakan coba lagi dalam {$seconds} detik.");
        }

        // Increment the attempt counter
        $this->limiter->hit($key, $limits['decay_minutes'] * 60);

        return $next($request);
    }

    /**
     * Determine the type of operation being performed
     */
    protected function getOperationType(Request $request): ?string
    {
        $method = $request->method();
        $route = $request->route();

        if (! $route) {
            return null;
        }

        $routeName = $route->getName();

        if (! $routeName) {
            return null;
        }

        // Map routes to operation types
        if (str_contains($routeName, 'tambah-user') && $method === 'POST') {
            return 'user_creation';
        }

        if (str_contains($routeName, 'kelola-user.update') && $method === 'PUT') {
            return 'user_update';
        }

        if (str_contains($routeName, 'reset-password') && $method === 'POST') {
            return 'password_reset';
        }

        if (str_contains($routeName, 'kelola-user.destroy') && $method === 'DELETE') {
            return 'user_deletion';
        }

        return null;
    }

    /**
     * Get rate limit configuration for operation type
     */
    protected function getLimits(?string $operation): ?array
    {
        $limits = [
            'user_creation' => [
                'max_attempts' => 10,
                'decay_minutes' => 1,
            ],
            'user_update' => [
                'max_attempts' => 20,
                'decay_minutes' => 1,
            ],
            'password_reset' => [
                'max_attempts' => 5,
                'decay_minutes' => 1,
            ],
            'user_deletion' => [
                'max_attempts' => 5,
                'decay_minutes' => 1,
            ],
        ];

        return $limits[$operation] ?? null;
    }

    /**
     * Resolve the request signature for rate limiting
     */
    protected function resolveRequestSignature(Request $request, string $operation): string
    {
        $userId = auth()->check() ? auth()->user()->UserID : 'guest';
        $ip = $request->ip();

        return sprintf(
            'throttle:%s:%s:%s',
            $operation,
            $userId,
            sha1($ip)
        );
    }
}
