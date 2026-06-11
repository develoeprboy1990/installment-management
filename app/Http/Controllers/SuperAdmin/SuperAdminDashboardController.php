<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tenants'    => Tenant::count(),
            'active_tenants'   => Tenant::where('status', 'active')->count(),
            'inactive_tenants' => Tenant::where('status', 'inactive')->count(),
            'suspended_tenants'=> Tenant::where('status', 'suspended')->count(),
            'total_users'      => User::whereNotNull('tenant_id')->count(),
        ];

        $tenants = Tenant::withCount('users', 'customers', 'purchases')
                         ->latest()
                         ->paginate(15);

        return view('superadmin.dashboard', compact('stats', 'tenants'));
    }
}
