<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 */
class Region extends Model
{
    use HasFactory;

    /**
     * @return Collection
     */
    public static function getRegionsForMarketOrderSync(): Collection
    {
        return self::query()->where('sync_market_orders', true)->get();
    }
}
