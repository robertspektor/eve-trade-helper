<?php

declare(strict_types=1);

namespace App\Services\Type;

use App\Jobs\SyncTypes;
use App\Models\MarketOrder;
use App\Models\Type;
use App\Services\Universe\Exceptions\FailedGetNamesException;
use App\Services\Universe\GetNameService;
use Illuminate\Support\Collection;

class ImportTypeService
{
    public function dispatchJobs()
    {
        $typeIds = $this->getTypeIds();
        $typeIds->chunk(1000)->each(
            fn ($chunkedTypeIds) => SyncTypes::dispatch($chunkedTypeIds)->onQueue('type_sync')
        );
    }

    /**
     * @throws FailedGetNamesException
     */
    public function updateType(Collection $typeIds)
    {
        $names = GetNameService::getNames($typeIds->toArray());
        $names->each(fn ($name) => $this->map($name)->save());
    }

    private function getTypeIds(): Collection
    {
        return MarketOrder::query()
            ->selectRaw('type_id')
            ->doesntHave('type')
            ->groupBy('type_id')
            ->get()
            ->pluck('type_id')
            ->values();
    }

    private function map($name): Type
    {
        $type = Type::query()->find($name['id']) ?? new Type();
        $type->id = $name['id'];
        $type->name = $name['name'];
        return $type;
    }
}
