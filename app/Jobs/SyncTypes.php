<?php

namespace App\Jobs;

use App\Services\Type\ImportTypeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SyncTypes implements ShouldQueue
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
    public function __construct(private Collection $typeIds)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImportTypeService $importTypeService)
    {
        $importTypeService->updateType($this->typeIds);
    }
}
