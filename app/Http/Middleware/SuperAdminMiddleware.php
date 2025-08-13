<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is not authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated. Please login first.'
                ], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Anda harus login terlebih dahulu untuk mengakses halaman ini.');
        }

        // Check if user exists and has the required role
        $user = Auth::user();

        if (!$user || $user->role !== 'super_admin') {
            // Log unauthorized access attempt
            Log::warning('Unauthorized Super Admin access attempt', [
                'user_id' => $user ? $user->id : null,
                'user_email' => $user ? $user->email : null,
                'user_role' => $user ? $user->role : null,
                'requested_url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak. Diperlukan hak akses Super Admin.'
                ], 403);
            }

            // Different redirect based on user role
            if ($user && in_array($user->role, ['admin', 'super_admin'])) {
                // Regular admin trying to access super admin area
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Akses ditolak. Anda tidak memiliki hak akses Super Admin untuk mengakses fitur ini.');
            } else {
                // Non-admin user or guest
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Akses ditolak. Diperlukan hak akses Super Admin.');
            }
        }

        // Additional security check - ensure the user account is active
        if (!$this->isUserAccountActive($user)) {
            Auth::logout();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.'
                ], 403);
            }

            return redirect()->route('login')
                ->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.');
        }

        // Set additional headers for security
        $response = $next($request);

        // Add security headers if response supports it
        if ($response instanceof \Illuminate\Http\Response || $response instanceof \Illuminate\Http\JsonResponse) {
            $response->header('X-Super-Admin-Access', 'true');
            $response->header('X-Admin-Role', $user->role);
        }

        return $response;
    }

    /**
     * Check if user account is active and valid
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    private function isUserAccountActive($user): bool
    {
        // Check if user exists and has valid data
        if (!$user || !$user->id) {
            return false;
        }

        // Check if user email is verified (optional check)
        // Uncomment if you want to enforce email verification
        // if (!$user->email_verified_at) {
        //     return false;
        // }

        // Check if account is not suspended (if you have a suspended field)
        // if (isset($user->status) && $user->status === 'suspended') {
        //     return false;
        // }

        // Check if user was created recently (prevent immediate access)
        // You can adjust this timeframe as needed
        if ($user->created_at->diffInMinutes(now()) < 1) {
            // Allow some grace period for account setup
            return true;
        }

        return true;
    }

    /**
     * Get user-friendly error message based on context
     *
     * @param  \App\Models\User|null  $user
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function getContextualErrorMessage($user, Request $request): string
    {
        if (!$user) {
            return 'Anda harus login sebagai Super Admin untuk mengakses halaman ini.';
        }

        $action = $this->getActionFromRequest($request);

        switch ($user->role) {
            case 'admin':
                return "Akses ditolak. Fitur {$action} hanya dapat diakses oleh Super Admin.";
            case 'user':
                return 'Akses ditolak. Anda tidak memiliki hak akses administrator.';
            default:
                return 'Akses ditolak. Diperlukan hak akses Super Admin.';
        }
    }

    /**
     * Extract action context from request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function getActionFromRequest(Request $request): string
    {
        $path = $request->path();

        if (str_contains($path, 'users')) {
            return 'Kelola Admin';
        } elseif (str_contains($path, 'settings')) {
            return 'Pengaturan Sistem';
        } elseif (str_contains($path, 'config')) {
            return 'Konfigurasi';
        }

        return 'fitur ini';
    }
}
