<?php

namespace App\Services;

use App\Models\Tenant;

/**
 * TenantManager — Singleton service that holds the currently active tenant.
 *
 * Usage anywhere in code:
 *   app(TenantManager::class)->get()   → current Tenant model
 *   app(TenantManager::class)->id()    → current tenant ID (int|null)
 *   app(TenantManager::class)->check() → true if tenant is set
 */
class TenantManager
{
    protected ?Tenant $currentTenant = null;

    public function set(Tenant $tenant): void
    {
        $this->currentTenant = $tenant;
    }

    public function get(): ?Tenant
    {
        return $this->currentTenant;
    }

    public function id(): ?int
    {
        return $this->currentTenant?->id;
    }

    public function check(): bool
    {
        return $this->currentTenant !== null;
    }

    public function forget(): void
    {
        $this->currentTenant = null;
    }
}
