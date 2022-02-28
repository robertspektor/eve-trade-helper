<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\MarketOrders\SyncMarketOrdersService;
use Illuminate\Console\Command;

class SyncMarketOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:market-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Jobs to Sync Market Orders Queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(private SyncMarketOrdersService $syncMarketOrdersService)
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
        $this->syncMarketOrdersService->run();
        $this->syncMarketOrdersService->cleanup();

        return 0;
    }
}
