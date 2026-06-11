<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use App\Services\TenantManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ResolveTenant Middleware
 *
 * Runs on every admin request.
 * Reads the logged-in user's tenant_id, loads the Tenant model,
 * and stores it in TenantManager so all models auto-scope to it.
 */
class ResolveTenant
{
    public function __construct(protected TenantManager $tenantManager) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // SuperAdmin has no tenant_id — skip scoping
        if ($user && $user->tenant_id) {
            $tenant = Tenant::find($user->tenant_id);

            if (!$tenant) {
                abort(403, 'Your store account does not exist.');
            }

            if ($tenant->status === 'suspended') {
                abort(403, 'Your store has been suspended. Please contact support.');
            }

            if ($tenant->status === 'inactive') {
                abort(403, 'Your store account is inactive.');
            }

            // Set tenant context — all models will now auto-filter
            $this->tenantManager->set($tenant);
        }

        return $next($request);
    }
}
