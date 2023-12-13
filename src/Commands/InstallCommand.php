<?php

namespace Phpsa\FilamentCms\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament-cms:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the cms resources';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->comment('Publishing CMS Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'filament-cms-config']);

        $this->comment('Publishing Filament CMS Migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'filament-cms-migrations']);
        $this->callSilent('vendor:publish', ['--tag' => 'tags-migrations']);

        $this->info('Filament CMS was installed successfully.');

        copy(__DIR__ . '/../../stubs/cms.php', base_path('routes/cms.php'));

        return 0;
    }
}
