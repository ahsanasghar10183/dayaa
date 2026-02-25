<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\ActivityLog;
use App\Mail\OrganizationApproved;
use App\Mail\OrganizationRejected;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    /**
     * Display a listing of organizations
     */
    public function index(Request $request): View
    {
        $query = Organization::with(['user', 'subscription']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('charity_number', 'like', '%' . $request->search . '%');
            });
        }

        $organizations = $query->latest()->paginate(15);

        return view('super-admin.organizations.index', compact('organizations'));
    }

    /**
     * Display the specified organization
     */
    public function show(Organization $organization): View
    {
        $organization->load([
            'user',
            'subscription',
            'campaigns',
            'devices',
            'donations' => function ($query) {
                $query->where('payment_status', 'success')->latest()->limit(10);
            }
        ]);

        // Calculate statistics
        $stats = [
            'total_campaigns' => $organization->campaigns()->count(),
            'active_campaigns' => $organization->campaigns()->where('status', 'active')->count(),
            'total_devices' => $organization->devices()->count(),
            'online_devices' => $organization->devices()->where('status', 'online')->count(),
            'total_donations' => $organization->donations()->where('payment_status', 'success')->count(),
            'total_amount' => $organization->donations()->where('payment_status', 'success')->sum('amount'),
        ];

        return view('super-admin.organizations.show', compact('organization', 'stats'));
    }

    /**
     * Approve an organization
     */
    public function approve(Organization $organization): RedirectResponse
    {
        if ($organization->status !== 'pending') {
            return back()->with('error', 'Only pending organizations can be approved.');
        }

        $organization->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
            'action' => 'approved',
            'model_type' => Organization::class,
            'model_id' => $organization->id,
            'description' => 'Organization approved by ' . auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Send approval email to organization owner
        $organization->load('user');
        if ($organization->user && $organization->user->email) {
            Mail::to($organization->user->email)->send(new OrganizationApproved($organization));
        }

        return back()->with('success', 'Organization approved successfully.');
    }

    /**
     * Reject an organization
     */
    public function reject(Request $request, Organization $organization): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($organization->status !== 'pending') {
            return back()->with('error', 'Only pending organizations can be rejected.');
        }

        $organization->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
            'action' => 'rejected',
            'model_type' => Organization::class,
            'model_id' => $organization->id,
            'description' => 'Organization rejected: ' . $request->rejection_reason,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Send rejection email to organization owner
        $organization->load('user');
        if ($organization->user && $organization->user->email) {
            Mail::to($organization->user->email)->send(new OrganizationRejected($organization, $request->rejection_reason));
        }

        return back()->with('success', 'Organization rejected.');
    }

    /**
     * Suspend an organization
     */
    public function suspend(Request $request, Organization $organization): RedirectResponse
    {
        $request->validate([
            'suspension_reason' => 'required|string|max:500',
        ]);

        if ($organization->status !== 'active') {
            return back()->with('error', 'Only active organizations can be suspended.');
        }

        $organization->update([
            'status' => 'suspended',
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
            'action' => 'suspended',
            'model_type' => Organization::class,
            'model_id' => $organization->id,
            'description' => 'Organization suspended: ' . $request->suspension_reason,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // TODO: Send suspension email to organization

        return back()->with('success', 'Organization suspended.');
    }

    /**
     * Reactivate a suspended organization
     */
    public function reactivate(Organization $organization): RedirectResponse
    {
        if ($organization->status !== 'suspended') {
            return back()->with('error', 'Only suspended organizations can be reactivated.');
        }

        $organization->update([
            'status' => 'active',
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
            'action' => 'reactivated',
            'model_type' => Organization::class,
            'model_id' => $organization->id,
            'description' => 'Organization reactivated by ' . auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // TODO: Send reactivation email to organization

        return back()->with('success', 'Organization reactivated successfully.');
    }

    /**
     * Delete an organization
     */
    public function destroy(Organization $organization): RedirectResponse
    {
        // Log activity before deletion
        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
            'action' => 'deleted',
            'model_type' => Organization::class,
            'model_id' => $organization->id,
            'description' => 'Organization deleted by ' . auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $organization->delete();

        return redirect()->route('super-admin.organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }
}
