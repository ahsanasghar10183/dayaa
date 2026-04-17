<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscribed
{
    /**
     * Handle an incoming request.
     * Ensures the organization has an active subscription before accessing protected routes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Allow access if no user is authenticated (handled by auth middleware)
        if (!$user) {
            return $next($request);
        }

        // Allow super admins to bypass subscription check
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Get organization
        $organization = $user->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('error', 'Please complete your organization profile first.');
        }

        // Allow access to billing routes (so they can subscribe)
        if ($request->routeIs('organization.billing.*')) {
            return $next($request);
        }

        // Allow access to profile routes
        if ($request->routeIs('organization.profile.*')) {
            return $next($request);
        }

        // Allow access to status pages (pending, rejected, suspended)
        if ($request->routeIs('organization.pending') ||
            $request->routeIs('organization.rejected') ||
            $request->routeIs('organization.suspended')) {
            return $next($request);
        }

        // Check for active or trialing subscription
        $subscription = $organization->subscription()
            ->whereIn('status', ['active', 'trialing'])
            ->first();

        if (!$subscription) {
            // No active subscription - redirect to billing setup
            return redirect()->route('organization.billing.create')
                ->with('warning', 'Please activate your subscription to access the platform.');
        }

        // Subscription exists and is active/trialing
        return $next($request);
    }
}
