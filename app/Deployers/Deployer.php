<?php

namespace App\Deployers;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Deployer
{
    protected $deploymentAction;

    public static function toString()
    {
        return static::class;
    }


    public function __construct($deploymentAction)
    {
        $this->deploymentAction = $deploymentAction;
    }

    public static function deploy($deploymentAction)
    {
        $class = new static($deploymentAction);
        switch ($deploymentAction->method) {
            case 'create':
                return $class->create();
                break;
            case 'update':
                return $class->update();
                break;
            case 'delete':
                return $class->delete();
                break;
            default:
                throw new \Exception('Invalid action');
        }
    }

    public function parkDomain($domain, $folderPath = '/var/www/_deployer', $publicPath = 'public')
    {

        $output = [];
        // Define Nginx configuration
        $nginxConfig = <<<EOL
            server {
                listen 80;
                server_name $domain;

                root $folderPath/$publicPath;

                index index.php index.html index.htm;

                error_log  /var/log/nginx/$domain-error.log;
                access_log /var/log/nginx/$domain-access.log;


                add_header Content-Security-Policy "frame-ancestors 'self' *.docker.processton.com";
                add_header X-Content-Type-Options "nosniff";
                add_header X-Accel-Buffering no;
                add_header Connection keep-alive;

                location ~ \.php$ {

                    fastcgi_pass unix:/run/php/php8.3-fpm.sock;
                    fastcgi_index index.php;
                    include fastcgi_params;
                    fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
                    fastcgi_param PATH_INFO \$fastcgi_path_info;
                    proxy_read_timeout 1500;
                    fastcgi_read_timeout 1500;

                }
                location / {

                    try_files \$uri /index.php?\$query_string;
                    gzip_static on;

                }
            }
        EOL;


        if (!file_exists("/var/log/nginx/" . $domain)) {
            $process = new Process(['mkdir', '-p', $domain], '/var/log/nginx/');
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        }

        $configFilePath = '/etc/nginx/sites-available/' . $domain . '.conf';

        try {
            file_put_contents($configFilePath, $nginxConfig);

            if (
                file_exists($configFilePath)
            ) {
                $output[] = [
                    'status' => 'success',
                    'message' => 'Nginx configuration created successfully',
                    'command' => 'file_put_contents',
                ];
            } else {

                $output[] = [
                    'status' => 'failed',
                    'message' => 'Operation was successfull but nginx configuration is not created',
                    'command' => 'file_put_contents',
                ];

                return $output;
            }
        } catch (\Exception $e) {
            $output[] = [
                'status' => 'failed',
                'message' => $e->getMessage(),
                'command' => 'unable to create file',
            ];

            return $output;
        }


        try {

            $enableSymlinkPath = '/etc/nginx/sites-enabled/' . $domain . '.conf';
            if (!file_exists($enableSymlinkPath)) {
                $process = new Process(['ln', '-s', $configFilePath, $enableSymlinkPath]);
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                $output[] = [
                    'status' => 'success',
                    'message' => 'Nginx configuration symlink created successfully',
                    'command' => 'ln -s',
                    'additional' => $process->getOutput()
                ];
            }

            // Test Nginx configuration
            $process = new Process(['nginx', '-t']);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output[] = [
                'status' => 'success',
                'message' => 'Nginx configuration test successful',
                'command' => 'nginx -t',
                'additional' => $process->getOutput()
            ];

            // Reload Nginx to apply changes
            $process = new Process(['service', 'nginx', 'reload']);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output[] = [
                'status' => 'success',
                'message' => 'Nginx reloaded successfully',
                'command' => 'service nginx reload',
                'additional' => $process->getOutput()
            ];

            return $output;
        } catch (ProcessFailedException $e) {

            $output[] = [
                'status' => 'failed',
                'message' => $e->getMessage(),
                'command' => $e->getProcess()->getCommandLine(),
                'additional' => $e->getProcess()->getOutput()
            ];

            return $output;
        }
    }

    public function parkProxyDomain($domain, $tenantFolder, $folderPath, $publicPath){

        $output = [];

        // Define Nginx configuration
        $nginxConfig = <<<EOL
            server {
                listen 80;
                server_name $domain;

                root $folderPath/$publicPath;

                index index.php index.html index.htm;

                error_log  /var/log/nginx/$tenantFolder/$domain-error.log;
                access_log /var/log/nginx/$tenantFolder/$domain-access.log;


                add_header Content-Security-Policy "frame-ancestors 'self' *.docker.processton.com";
                add_header X-Content-Type-Options "nosniff";
                add_header X-Accel-Buffering no;
                add_header Connection keep-alive;

                location ~ \.php$ {

                    fastcgi_pass unix:/run/php/php8.3-fpm.sock;
                    fastcgi_index index.php;
                    include fastcgi_params;
                    fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
                    fastcgi_param PATH_INFO \$fastcgi_path_info;
                    proxy_read_timeout 1500;
                    fastcgi_read_timeout 1500;

                }
                location / {

                    try_files \$uri /index.php?\$query_string;
                    gzip_static on;

                }
            }
        EOL;

        if (!file_exists("/var/log/nginx/". $tenantFolder)) {
            $process = new Process(['mkdir', '-p', $tenantFolder], '/var/log/nginx/');
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        }

        if (!file_exists('/etc/nginx/sites-available/' . $tenantFolder)) {
            $process = new Process(['mkdir', '-p', $tenantFolder], '/etc/nginx/sites-available/');
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        }
        $configFilePath = '/etc/nginx/sites-available/'. $tenantFolder .'/'. $domain . '.conf';

        try{
            file_put_contents($configFilePath, $nginxConfig);

            if(
                file_exists($configFilePath)
            ){
                $output[] = [
                    'status' => 'success',
                    'message' => 'Nginx configuration created successfully',
                    'command' => 'file_put_contents',
                ];
            }else{

                $output[] = [
                    'status' => 'failed',
                    'message' => 'Operation was successfull but nginx configuration is not created',
                    'command' => 'file_put_contents',
                ];

                return $output;
            }

        } catch (\Exception $e) {
            $output[] = [
                'status' => 'failed',
                'message' => $e->getMessage(),
                'command' => 'unable to create file',
            ];

            return $output;
        }


        try{

            $enableSymlinkPath = '/etc/nginx/sites-enabled/'. $domain . '.conf';
            if (!file_exists($enableSymlinkPath)) {
                $process = new Process(['ln', '-s', $configFilePath, $enableSymlinkPath]);
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                $output[] = [
                    'status' => 'success',
                    'message' => 'Nginx configuration symlink created successfully',
                    'command' => 'ln -s',
                    'additional' => $process->getOutput()
                ];

            }

            // Test Nginx configuration
            $process = new Process(['nginx', '-t']);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output[] = [
                'status' => 'success',
                'message' => 'Nginx configuration test successful',
                'command' => 'nginx -t',
                'additional' => $process->getOutput()
            ];

            // Reload Nginx to apply changes
            $process = new Process(['service', 'nginx', 'reload']);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output[] = [
                'status' => 'success',
                'message' => 'Nginx reloaded successfully',
                'command' => 'service nginx reload',
                'additional' => $process->getOutput()
            ];

            return $output;

        } catch (ProcessFailedException $e) {

            $output[] = [
                'status' => 'failed',
                'message' => $e->getMessage(),
                'command' => $e->getProcess()->getCommandLine(),
                'additional' => $e->getProcess()->getOutput()
            ];

            return $output;

        }

    }

    function makeSafeTableName(string $name): string
    {
        // MySQL Reserved Keywords (Add more if needed)
        $reservedWords = [
            'SELECT',
            'INSERT',
            'UPDATE',
            'DELETE',
            'TABLE',
            'FROM',
            'WHERE',
            'ORDER',
            'JOIN',
            'GROUP',
            'USER',
            'DATABASE',
            'INDEX',
            'VIEW',
            'TRIGGER',
            'LIMIT',
            'HAVING',
            'PRIMARY',
            'KEY',
            'FOREIGN',
            'CONSTRAINT',
            'CREATE',
            'DROP',
            'ALTER',
            'DEFAULT',
            'IF',
            'ELSE',
            'NULL',
            'NOT',
            'AND',
            'OR',
            'LIKE',
            'IN',
            'EXISTS',
            'SET',
            'VALUES',
            'TEXT',
            'DATE'
        ];

        // Convert to lowercase
        $name = strtolower($name);

        // Replace spaces, special characters, and multiple underscores with a single underscore
        $name = preg_replace('/[^a-z0-9_]+/', '_', $name);
        $name = preg_replace('/_+/', '_', $name);

        // Remove underscores from start & end
        $name = trim($name, '_');

        // Check if the name is a reserved word and add a suffix if needed
        if (in_array(strtoupper($name), $reservedWords)) {
            $name .= '_tbl';
        }

        // Ensure max length does not exceed 64 characters
        if (strlen($name) > 64) {
            // Generate a 3-4 digit random number
            $randomNumber = rand(100, 9999);

            // Trim the string and append the random number
            $name = substr($name, 0, 60) . '_' . $randomNumber;
        }

        return $name;
    }



}
