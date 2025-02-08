<?php

namespace App\Deployers;

use App\Deployers\Deployer;
use App\Deployers\DeployerInterface;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ApplicationDeployer extends Deployer implements DeployerInterface
{



    public function create()
    {
        $this->deploymentAction->update([
            'status' => 'in-progress'
        ]);

        $folder_path = $this->deploymentAction->application->folder_path;

        try {
            $this->migrateCommand($folder_path);

            $this->clearConfigCommand($folder_path);

            $this->clearCacheCommand($folder_path);

            $this->clearCommand($folder_path);

        }catch(ProcessFailedException $e){
            $this->deploymentAction->update([
                'status' => 'failed',
                'message' => 'Failed to install',
                'output' => [
                    'tenant' => $e->getMessage()
                ]
            ]);
        }

        $this->deploymentAction->update([
            'status' => 'completed',
            'message' => 'Application is ready',
            'output' => [
            ]
        ]);
        return true;

    }

    public function update()
    {
        return true;
    }

    public function delete()
    {
        return true;
    }

    public static function links(){
        return [

        ];
    }

    protected function migrateCommand($folderPath)
    {
        return $this->command(['php', 'artisan', 'migrate:fresh'], $folderPath);
    }

    protected function seedCommand($folderPath)
    {
        return $this->command(['php', 'artisan', 'db:seed'], $folderPath);
    }

    protected function clearCommand($folderPath)
    {
        return $this->command(['php', 'artisan', 'config:cache'], $folderPath);
    }

    protected function clearConfigCommand($folderPath)
    {
        return $this->command(['php', 'artisan', 'config:clear'], $folderPath);
    }

    protected function clearCacheCommand($folderPath)
    {
        return $this->command(['php', 'artisan', 'cache:clear'], $folderPath);
    }

    protected function command($command, $folderPath)
    {
        $process = new Process($command, $folderPath);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process;
    }
}
