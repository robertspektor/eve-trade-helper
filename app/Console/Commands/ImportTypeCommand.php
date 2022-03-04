<?php

namespace App\Console\Commands;

use App\Services\Type\ImportTypeService;
use Illuminate\Console\Command;

class ImportTypeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports types';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(private ImportTypeService $importTypeService)
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
        $this->importTypeService->dispatchJobs();
        return 0;
    }
}
