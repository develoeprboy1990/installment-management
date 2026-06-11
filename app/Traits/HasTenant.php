<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Services\TenantManager;
use Illuminate\Database\Eloquent\Builder;

/**
 * HasTenant Trait
 *
 * Add this trait to every model that belongs to a tenant (store).
 * It does two things automatically:
 *
 * 1. GLOBAL SCOPE  — every query is automatically filtered by the current tenant's ID.
 *    e.g. Customer::all() returns only the logged-in store's customers.
 *
 * 2. AUTO ASSIGN   — when creating a new record, tenant_id is automatically filled
 *    from the current tenant context. You don't need to set it manually.
 */
trait HasTenant
{
    protected static function bootHasTenant(): void
    {
        // ── Global Scope: auto-filter all queries by current tenant ──────────
        static::addGlobalScope('tenant', function (Builder $builder) {
            /** @var TenantManager $tenantManager */
            $tenantManager = app(TenantManager::class);

            if ($tenantManager->check()) {
                $table = (new static())->getTable();
                $builder->where("{$table}.tenant_id", $tenantManager->id());
            }
        });

        // ── Auto-assign tenant_id on create ───────────────────────────────────
        static::creating(function ($model) {
            if (empty($model->tenant_id)) {
                /** @var TenantManager $tenantManager */
                $tenantManager = app(TenantManager::class);
                if ($tenantManager->check()) {
                    $model->tenant_id = $tenantManager->id();
                }
            }
        });
    }

    // ── Relationship back to Tenant ──────────────────────────────────────────
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Query without tenant scope (for SuperAdmin use).
     * Usage: Customer::withoutTenantScope()->get()
     */
    public static function withoutTenantScope(): Builder
    {
        return static::withoutGlobalScope('tenant');
    }

    /**
     * Query for a specific tenant (for SuperAdmin use).
     * Usage: Customer::forTenant(3)->get()
     */
    public static function forTenant(int $tenantId): Builder
    {
        return static::withoutGlobalScope('tenant')
                     ->where((new static())->getTable() . '.tenant_id', $tenantId);
    }
}
