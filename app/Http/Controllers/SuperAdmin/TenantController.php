<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    /**
     * List all tenants.
     */
    public function index()
    {
        $tenants = Tenant::withCount('users', 'customers', 'purchases', 'installments')
                         ->latest()
                         ->paginate(15);

        return view('superadmin.tenants.index', compact('tenants'));
    }

    /**
     * Show form to create a new tenant/store.
     */
    public function create()
    {
        return view('superadmin.tenants.create');
    }

    /**
     * Store a new tenant AND its first Admin user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:tenants,email',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'status'         => 'required|in:active,inactive,suspended',
            // First admin user
            'admin_name'     => 'required|string|max:255',
            'admin_email'    => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Create the tenant
            $tenant = Tenant::create([
                'name'    => $validated['name'],
                'slug'    => Str::slug($validated['name']) . '-' . Str::random(4),
                'email'   => $validated['email'],
                'phone'   => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'status'  => $validated['status'],
            ]);

            // 2. Create the Admin user for this tenant
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name'      => $validated['admin_name'],
                'email'     => $validated['admin_email'],
                'password'  => Hash::make($validated['admin_password']),
            ]);

            // 3. Assign Admin role
            $user->assignRole('Admin');
        });

        return redirect()->route('superadmin.tenants.index')
                         ->with('success', 'Store created successfully with Admin user!');
    }

    /**
     * Show a single tenant's details and stats.
     */
    public function show(Tenant $tenant)
    {
        $summary = $tenant->getSummary();
        $users   = $tenant->users()->with('roles')->get();

        return view('superadmin.tenants.show', compact('tenant', 'summary', 'users'));
    }

    /**
     * Show edit form.
     */
    public function edit(Tenant $tenant)
    {
        return view('superadmin.tenants.edit', compact('tenant'));
    }

    /**
     * Update tenant details.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|unique:tenants,email,' . $tenant->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status'  => 'required|in:active,inactive,suspended',
            'subscription_expires_at' => 'nullable|date',
        ]);

        $tenant->update($validated);

        return redirect()->route('superadmin.tenants.index')
                         ->with('success', 'Store updated successfully!');
    }

    /**
     * Delete a tenant (cascade deletes all their data via FK).
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()->route('superadmin.tenants.index')
                         ->with('success', 'Store and all its data deleted.');
    }

    /**
     * Toggle tenant status between active / inactive.
     */
    public function toggleStatus(Tenant $tenant)
    {
        $newStatus = $tenant->status === 'active' ? 'inactive' : 'active';
        $tenant->update(['status' => $newStatus]);

        return back()->with('success', "Store status changed to {$newStatus}.");
    }
}
