<?php

namespace QuarkCloudIO\QuarkAdmin\Console;

use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'quarkadmin:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the quark-admin package';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->publishResources();

        $this->info('Update quarkadmin new version completed.');
    }

    /**
     * 发布配置文件
     *
     * @return void
     */
    public function publishConfig()
    {
        $configPath = __DIR__.'/../../config';
        $basePath = base_path();
        copy_dir_to_folder($configPath, $basePath);

        $this->info('Update config successed!');
    }

    /**
     * 发布迁移文件
     *
     * @return void
     */
    public function publishMigrations()
    {
        $configPath = __DIR__.'/../../database/migrations';
        $databasePath = database_path();
        copy_dir_to_folder($configPath, $databasePath);

        $this->info('Update migrations successed!');
    }

    /**
     * 发布资源文件
     *
     * @return void
     */
    public function publishResources()
    {
        $resourcesPath = __DIR__.'/../../../quark/public';
        $basePath = base_path('public\admin');

        if(!realpath($basePath)) {
            mkdir($basePath);
        }

        $dirs = get_folder_dirs($resourcesPath);
        $files = get_folder_files($resourcesPath);
        
        if(is_array($dirs)) {
            foreach ($dirs as $key => $value) {
                $dirPath = $resourcesPath.'/'.$value;
                copy_dir_to_folder($dirPath, $basePath);
            }
        }
        
        if(is_array($files)) {
            foreach ($files as $key => $value) {
                $filePath = $resourcesPath.'/'.$value;
                copy_file_to_folder($filePath, $basePath);
            }
        }

        $this->info('Update resources successed!');
    }
}