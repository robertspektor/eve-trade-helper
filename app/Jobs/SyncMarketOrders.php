<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Region;
use App\Services\MarketOrders\SyncMarketOrdersService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncMarketOrders implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    /**
     * @var int[]
     */
    public array $backoff = [300, 600, 1200];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Region $region, private int $page)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SyncMarketOrdersService $syncMarketOrders)
    {
        $syncMarketOrders->sync($this->region, $this->page);
    }
}
