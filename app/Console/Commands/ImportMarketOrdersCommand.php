<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\MarketOrders\ImportMarketOrdersService;
use Illuminate\Console\Command;

class ImportMarketOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:market-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add jobs to import market orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(private ImportMarketOrdersService $importMarketOrdersService)
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
        $this->importMarketOrdersService->run();
        $this->importMarketOrdersService->cleanup();

        return 0;
    }
}
