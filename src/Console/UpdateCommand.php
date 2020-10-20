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
        // todo
    }
}