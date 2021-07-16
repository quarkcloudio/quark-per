<?php

namespace QuarkCMS\QuarkAdmin\Console;

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
        $resourcesPath = __DIR__.'/../../resources';
        $basePath = base_path();
        copy_dir_to_folder($resourcesPath, $basePath);

        $this->info('Update resources successed!');
    }
}