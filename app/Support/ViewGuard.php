<?php

namespace App\Support;

/**
 * Restricts which Blade views can be rendered through the generic
 * /modal/{view} and /view/{path} endpoints.
 */
class ViewGuard
{
    public static function authorize(string $path): void
    {
        // only dotted view names, no traversal / odd characters
        if (!preg_match('/^[A-Za-z0-9_\-]+(\.[A-Za-z0-9_\-]+)*\.?$/', $path)) {
            abort(404);
        }

        $user = auth()->user();
        $role = $user->role ?? null;

        if (str_starts_with($path, 'admin.')) {
            if ($role !== 'admin') {
                abort(403);
            }
        } elseif (str_starts_with($path, 'instructor.')) {
            if (!in_array($role, ['admin', 'instructor'], true)) {
                abort(403);
            }
        } elseif (str_starts_with($path, 'frontend.default.') || str_starts_with($path, 'components.')) {
            // public presentation fragments; logged-in-only ones check auth inside the views/controllers
            if (str_contains($path, 'admin') && $role !== 'admin') {
                abort(403);
            }
        } else {
            abort(404);
        }
    }
}
