<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
}
