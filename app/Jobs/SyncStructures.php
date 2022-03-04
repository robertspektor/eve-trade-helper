<?php

namespace App\Jobs;

use App\Exceptions\AuthException;
use App\Services\Location\Exceptions\FailedImportStructureException;
use App\Services\Location\ImportStructuresService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SyncStructures implements ShouldQueue
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
    public function __construct(private int $structureId)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImportStructuresService $importStructuresService)
    {
        try {
            $importStructuresService->updateStructure($this->structureId);
        } catch (AuthException $e) {
            Log::error('AuthException: ' . $e->getMessage());
        } catch (FailedImportStructureException $e) {
            Log::error('FailedImportStructureException: ' . $e->getMessage());
        }
    }
}
