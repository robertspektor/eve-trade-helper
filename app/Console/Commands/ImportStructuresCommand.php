<?php

namespace App\Console\Commands;

use App\Services\Location\ImportStructuresService;
use Illuminate\Console\Command;

class ImportStructuresCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:structures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all public structures';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(private ImportStructuresService $importStructuresService)
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
        $this->importStructuresService->dispatchJobs();
        return 0;
    }
}
