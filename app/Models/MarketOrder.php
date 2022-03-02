<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $region_id
 * @property int $duration
 * @property bool $is_buy_order
 * @property Carbon $issued
 * @property int $location_id
 * @property int $min_volume
 * @property int $order_id
 * @property float $price
 * @property string $range
 * @property int $system_id
 * @property int $type_id
 * @property int $volume_remain
 * @property int $volume_total
 * @property Carbon $last_seen
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class MarketOrder extends Model
{
    use HasFactory;
    public $guarded = [
    ];

    public static function getBestSellPricesByRegionId(int $regionId): Collection
    {
        return self::query()
            ->selectRaw('type_id, MIN(price) as best_sell_price')
            ->where('is_buy_order', false)
            ->where('region_id', $regionId)
            ->groupBy('type_id')
            ->get();
    }

    public static function getBestSellPriceByTypeForRegion(int $typeId, int $regionId): MarketOrder
    {
        return self::query()
            ->selectRaw('MIN(price) as best_sell_price')
            ->where('is_buy_order', false)
            ->where('region_id', $regionId)
            ->where('type_id', $typeId)
            ->get()->first();
    }

    public static function getTypesByLocationId(int $locationId): Collection
    {
        return self::query()
            ->selectRaw('type_id, MIN(price) as best_sell_price')
            ->where('is_buy_order', false)
            ->where('location_id', $locationId)
            ->groupBy('type_id')
            ->get();
    }

    public static function getTypeByLocationId($typeId, $locationId): MarketOrder
    {
        return self::query()
            ->selectRaw('type_id, MIN(price) as best_sell_price')
            ->where('is_buy_order', false)
            ->where('location_id', $locationId)
            ->where('type_id', $typeId)
            ->groupBy('type_id')
            ->get()->first();
    }
}
