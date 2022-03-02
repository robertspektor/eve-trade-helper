<?php

namespace App\Console\Commands;

use App\Services\TradeOpportunity\CreateTradeOpportunityService;
use Illuminate\Console\Command;

class CreateTradeOpportunityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:trade-opportunity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(private CreateTradeOpportunityService $createTradeOpportunityService)
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
        $this->createTradeOpportunityService->run();
        return 0;
    }
}
