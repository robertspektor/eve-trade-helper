<?php

declare(strict_types=1);

namespace App\Services\Location;

use App\Jobs\SyncLocations;
use App\Models\Location;
use App\Services\Universe\GetNameService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportLocationService
{
    protected string $url = '/universe/stations';

    public function import(): void
    {
        SyncLocations::dispatch($this->getLocationIds())->onQueue('location_sync');
    }

    public function getByLocationIds(Collection $locationIds)
    {
        Log::debug('ImportLocationService ' . $locationIds->count() . ' location to save');

        DB::transaction(function() use ($locationIds) {

            $chunks = $locationIds->chunk(1000);
            $chunks->each(function ($chunk) {

                Log::debug('-> chunk of 1000');

                $names = GetNameService::getNames($chunk->toArray());
                $names->each(function ($name) {
                    $existingLocation = Location::query()->find((int) $name['id']) ?? new Location();
                    $existingLocation->id = (int) $name['id'];
                    $existingLocation->name = $name['name'];
                    $existingLocation->save();
                });
            });
        });
    }

    private function getLocationIds(): Collection
    {
        $response = Http::get(config('eve.esi_url') . $this->url);
        return $response->collect();
    }
}
