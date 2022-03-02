<?php

namespace App\Jobs;

use App\Services\Location\ImportLocationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SyncLocations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function __construct(private Collection $locationIds)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImportLocationService $importLocationService)
    {
        $importLocationService->getByLocationIds($this->locationIds);
    }
}
