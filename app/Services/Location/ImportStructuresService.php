<?php

declare(strict_types=1);

namespace App\Services\Location;

use App\Jobs\SyncStructures;
use App\Models\Location;
use App\Services\Location\Exceptions\FailedImportStructureException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportStructuresService
{
    public function dispatchJobs(): void
    {
        $structureIds = $this->getStructureIds();
        $structureIds->each(
            fn ($structureId) => SyncStructures::dispatch($structureId)->onQueue('structure_sync')
        );
    }

    private function getStructureIds(): Collection
    {
        $response = Http::get(config('eve.esi_url') . '/universe/structures');
        return $response->collect();
    }

    /**
     * @throws FailedImportStructureException
     */
    public function updateStructure(int $structureId): void
    {
        $url = Str::replace('{structureId}', $structureId, config('eve.esi_url') . '/universe/structures/{structureId}');
        $response = Http::withToken(config('eve.token'))->get($url);
        if ($response->status() !== 200) {
            throw new FailedImportStructureException();
        }

        $structure = $response->collect();
        $existingLocation = Location::query()->find($structureId) ?? new Location();
        $existingLocation->id = $structureId;
        $existingLocation->name = $structure['name'];
        $existingLocation->save();
    }
}
