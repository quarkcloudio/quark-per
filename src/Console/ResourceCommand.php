<?php

namespace QuarkCMS\QuarkAdmin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class ResourceCommand extends Command
{
    protected $files;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'quarkadmin:resource {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new quarkadmin resource class';

    /**
     * Create a new command instance. 创建一个新的命令实例
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command. 执行控制台命令
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $name = 'App\\Admin\\Resources\\'.$this->getNameInput();

        $path = $this->getPath($name);

        if ($this->alreadyExists($name)) {
            $this->error($name.' already exists!');

            return false;
        }

        $this->files->put($path, $this->buildClass($this->getNameInput()));

        $this->info($name.' created successfully.');
    }

    /**
     * Get the stub file for the generator. 获取生成器模板文件
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = $stub ?? '/stubs/resource.stub';

        return __DIR__.$stub;
    }

    /**
     * * Build the class with the given name. 使用给定的名称构建类
     *
     * Remove the base controller import if we are already in base namespace. 如果我们已经在基命名空间中，则删除基控制器导入
     *
     * @param $name
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceClass($stub, $name);
    }

    /**
     * Determine if the class already exists.  确定类是否已经存在
     *
     * @param  string  $name
     * @return bool
     */
    protected function alreadyExists($name)
    {
        return $this->files->exists($this->getPath($name));
    }

    /**
     * Get the destination class path. 获取目标类路径
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst('App', '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Replace the class name for the given stub. 替换给定存根的类名
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass(&$stub, $name)
    {
        $stub  = str_replace('DummyClass', $name, $stub);

        return $stub;
    }

    /**
     * Get the desired class name from the input. 从输入中获取所需的类名
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }
}