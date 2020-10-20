<?php

namespace QuarkCMS\QuarkAdmin\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'quarkadmin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the quark-admin package';

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
        // 发布资源
        $this->call('vendor:publish', [
            '--provider' => "QuarkCMS\QuarkAdmin\QuarkServiceProvider"
        ]);

        // 加载类
        $this->call('composer dump-autoload');

        // 初始化数据库
        $this->initDatabase();

        // 初始化Admin目录
        $this->initAdminDirectory();

        // 创建软连接
        $this->call('storage:link');
    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function initDatabase()
    {
        $this->call('migrate');

        if (\QuarkCMS\QuarkAdmin\Models\Admin::count() == 0) {
            $this->call('db:seed', ['--class' => 'QuarkAdminSeeder']);
        }
    }

    /**
     * Initialize the admAin directory.
     *
     * @return void
     */
    protected function initAdminDirectory()
    {
        $this->directory = app_path('Http/Controllers/Admin');

        if (is_dir($this->directory)) {
            $this->line("<error>{$this->directory} directory already exists !</error> ");

            return;
        }

        $this->makeDir('/');
        $this->line('<info>Admin directory was created:</info> '.str_replace(base_path(), '', $this->directory));

        $this->createDashboardController();
        $this->createUpgradeController();
        $this->createRoutesFile();
    }

    /**
     * Create DashboardController.
     *
     * @return void
     */
    public function createDashboardController()
    {
        $controller = $this->directory.'/DashboardController.php';
        $contents = $this->getStub('DashboardController');

        $this->laravel['files']->put($controller,$contents);
        $this->line('<info>DashboardController file was created:</info> '.str_replace(base_path(), '', $controller));
    }


    /**
     * Create UpgradeController.
     *
     * @return void
     */
    public function createUpgradeController()
    {
        $controller = $this->directory.'/UpgradeController.php';
        $contents = $this->getStub('UpgradeController');

        $this->laravel['files']->put($controller,$contents);
        $this->line('<info>UpgradeController file was created:</info> '.str_replace(base_path(), '', $controller));
    }

    /**
     * Create routes file.
     *
     * @return void
     */
    protected function createRoutesFile()
    {
        $file = base_path().'/routes/admin.php';
        $contents = $this->getStub('routes');

        $this->laravel['files']->put($file, $contents);
        $this->line('<info>Routes file was created:</info> '.str_replace(base_path(), '', $file));
    }

    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__."/stubs/$name.stub");
    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makeDir($path = '')
    {
        $this->laravel['files']->makeDirectory("{$this->directory}/$path", 0755, true, true);
    }
}