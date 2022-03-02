<?php

namespace App\Console\Commands;

use App\Services\Location\ImportLocationService;
use Illuminate\Console\Command;

class ImportLocationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports eve online locations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(private ImportLocationService $importLocationService)
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
        $this->importLocationService->import();
        return 0;
    }
}
