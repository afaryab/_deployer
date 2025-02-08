<?php

namespace App\Observers;

use App\Deployers\DomainDeployer;
use App\Models\Application;
use App\Models\DeploymentActivity;

class ApplicationObserver
{
    /**
     * Handle the Application "created" event.
     */
    public function created(Application $application): void
    {
        DeploymentActivity::create([
            'application_id' => $application->id,
            'provider' => DomainDeployer::class,
            'method' => 'create',
            'status' => 'pending',
        ]);
    }

    /**
     * Handle the Application "updated" event.
     */
    public function updated(Application $application): void
    {
        DeploymentActivity::create([
            'application_id' => $application->id,
            'provider' => DomainDeployer::class,
            'method' => 'update',
            'status' => 'pending',
        ]);
    }

    /**
     * Handle the Application "deleted" event.
     */
    public function deleted(Application $application): void
    {
        DeploymentActivity::create([
            'application_id' => $application->id,
            'provider' => DomainDeployer::class,
            'method' => 'delete',
            'status' => 'pending',
        ]);
    }

    /**
     * Handle the Application "restored" event.
     */
    public function restored(Application $application): void
    {
        DeploymentActivity::create([
            'application_id' => $application->id,
            'provider' => DomainDeployer::class,
            'method' => 'restore',
            'status' => 'pending',
        ]);
    }

    /**
     * Handle the Application "force deleted" event.
     */
    public function forceDeleted(Application $application): void
    {
        DeploymentActivity::create([
            'application_id' => $application->id,
            'provider' => DomainDeployer::class,
            'method' => 'permanently_delete',
            'status' => 'pending',
        ]);
    }
}
