<?php

namespace App\Support;

use Illuminate\Http\Request;

class ColdstoreAppSurfaceResolver
{
    public function resolve(Request $request): string
    {
        $requestedSurface = $this->normalizeSurface($request->query('surface'));

        if ($requestedSurface !== null) {
            $request->session()->put('coldstore_app_surface', $requestedSurface);

            return $requestedSurface;
        }

        $sessionSurface = $this->normalizeSurface($request->session()->get('coldstore_app_surface'));

        if ($sessionSurface !== null) {
            return $sessionSurface;
        }

        if ($this->isNativePhpRequest($request)) {
            return 'mobile';
        }

        return $this->normalizeSurface(config('coldstore.app_surface', 'desktop')) ?? 'desktop';
    }

    private function isNativePhpRequest(Request $request): bool
    {
        $userAgent = strtolower((string) $request->userAgent());

        return str_contains($userAgent, 'nativephp');
    }

    private function normalizeSurface(mixed $surface): ?string
    {
        $normalizedSurface = strtolower(trim((string) $surface));

        return in_array($normalizedSurface, ['desktop', 'mobile'], true)
            ? $normalizedSurface
            : null;
    }
}
