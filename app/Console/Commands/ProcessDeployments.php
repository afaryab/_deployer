<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DeploymentActivity;

class ProcessDeployments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process deployments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing deployments...');
        $failedDeploymentActions = DeploymentActivity::where('status', 'failed')->where('next_retry_id', null)->orderBy('created_at', 'asc')->get();

        foreach($failedDeploymentActions as $failedJob) {

            $this->info('Resetting Job ' . $failedJob->tenant_app->name);

            $newJob = $failedJob->replicate();

            $newJob->__set('status', 'pending');
            $newJob->__set('output', null);

            $newJob->save();

            $failedJob->__set('next_retry_id', $newJob->id);

            $failedJob->save();
        }

        $deploymentActions = DeploymentActivity::where('status', 'pending')->orderBy('created_at', 'asc')->limit(5)->get();

        foreach($deploymentActions as $deploymentAction) {
            // $deploymentAction->update(['status' => 'processing']);
            if($deploymentAction->tenant_app){
                $this->info('Deploying ' . $deploymentAction->tenant_app->name);

                if ($deploymentAction->provider::deploy($deploymentAction)) {
                    $this->info('Deployment completed: ' . $deploymentAction->tenant_app->name);
                } else {
                    $this->info('Deployment failed: ' . $deploymentAction->tenant_app->name);
                }
            }else if($deploymentAction->domain){
                $this->info('Deploying ' . $deploymentAction->domain->domain);

                if ($deploymentAction->provider::deploy($deploymentAction)) {
                    $this->info('Deployment completed: ' . $deploymentAction->domain->domain);
                } else {
                    $this->info('Deployment failed: ' . $deploymentAction->domain->domain);
                }
            }
            sleep(1);
        }

    }
}
