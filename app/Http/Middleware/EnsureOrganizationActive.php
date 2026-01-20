<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Super admins bypass this check
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // For organization admins, check their organization status
        if ($user->isOrganizationAdmin()) {
            $organization = $user->organization;

            if (!$organization) {
                return redirect()->route('organization.create')
                    ->with('warning', 'Please complete your organization profile.');
            }

            if ($organization->status === 'pending') {
                return redirect()->route('organization.pending')
                    ->with('info', 'Your organization is pending approval.');
            }

            if ($organization->status === 'rejected') {
                return redirect()->route('organization.rejected')
                    ->with('error', 'Your organization has been rejected. Reason: ' . $organization->rejection_reason);
            }

            if ($organization->status === 'suspended') {
                return redirect()->route('organization.suspended')
                    ->with('error', 'Your organization has been suspended.');
            }

            if (!$organization->isActive()) {
                abort(403, 'Your organization is not active.');
            }
        }

        return $next($request);
    }
}
