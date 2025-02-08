<?php

namespace App\Observers;

use App\Deployers\ProxyDomainDeployer;
use App\Models\DeploymentActivity;
use App\Models\Domain;

class DomainObserver
{
    /**
     * Handle the Domain "created" event.
     */
    public function created(Domain $domain): void
    {
        DeploymentActivity::create([
            'domain_id' => $domain->id,
            'provider' => ProxyDomainDeployer::class,
            'method' => 'create',
            'status' => 'pending',
        ]);
    }

    /**
     * Handle the Domain "updated" event.
     */
    public function updated(Domain $domain): void
    {
        DeploymentActivity::create([
            'domain_id' => $domain->id,
            'provider' => ProxyDomainDeployer::class,
            'method' => 'update',
            'status' => 'pending',
        ]);
    }

    /**
     * Handle the Domain "deleted" event.
     */
    public function deleted(Domain $domain): void
    {
        DeploymentActivity::create([
            'domain_id' => $domain->id,
            'provider' => ProxyDomainDeployer::class,
            'method' => 'delete',
            'status' => 'pending',
        ]);
    }

    /**
     * Handle the Domain "restored" event.
     */
    public function restored(Domain $domain): void
    {
        DeploymentActivity::create([
            'domain_id' => $domain->id,
            'provider' => ProxyDomainDeployer::class,
            'method' => 'restore',
            'status' => 'pending',
        ]);
    }

    /**
     * Handle the Domain "force deleted" event.
     */
    public function forceDeleted(Domain $domain): void
    {
        DeploymentActivity::create([
            'domain_id' => $domain->id,
            'provider' => ProxyDomainDeployer::class,
            'method' => 'permanently_delete',
            'status' => 'pending',
        ]);
    }
}
