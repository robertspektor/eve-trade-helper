<?php

declare(strict_types=1);

namespace App\Services\Location;

use App\Exceptions\AuthException;
use App\Jobs\SyncStructures;
use App\Models\Location;
use App\Models\MarketOrder;
use App\Services\Auth\AuthService;
use App\Services\Location\Exceptions\FailedImportStructureException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportStructuresService
{
    public function __construct(private AuthService $authService)
    {
    }

    public function dispatchJobs(): void
    {
        $structureIds = $this->getStructureIdsFromApi();
        $structureIds->merge($this->getStructureIdsFromMarketOrders());
        $structureIds->each(
            fn ($structureId) => SyncStructures::dispatch($structureId)->onQueue('structure_sync')
        );
    }

    private function getStructureIdsFromApi(): Collection
    {
        $response = Http::get(config('eve.esi_url') . '/universe/structures');
        return $response->collect();
    }

    private function getStructureIdsFromMarketOrders(): Collection
    {
        return MarketOrder::query()->doesntHave('location')->get()->pluck('location_id')->unique()->values();
    }

    /**
     * @throws FailedImportStructureException
     * @throws AuthException
     */
    public function updateStructure(int $structureId): void
    {
        $token = $this->authService->getToken();
        $url = Str::replace('{structureId}', $structureId, config('eve.esi_url') . '/universe/structures/{structureId}');
        $response = Http::withToken($token->getAccessToken())->get($url);
        if ($response->status() !== 200) {
            throw new FailedImportStructureException($response->body());
        }

        $structure = $response->collect();
        $existingLocation = Location::query()->find($structureId) ?? new Location();
        $existingLocation->id = $structureId;
        $existingLocation->name = $structure['name'];
        $existingLocation->save();
    }
}
