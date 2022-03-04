<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $type_id
 * @property int start_hub
 * @property int $end_hub
 * @property double $start_hub_price
 * @property double $end_hub_price
 * @property double $hub2hub_margin
 * @property double $taxes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TradeOpportunity extends Model
{
    use HasFactory;

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function start_hub(): BelongsTo
    {
        return $this->belongsTo(Location::class,  'start_hub');
    }

    public function end_hub(): BelongsTo
    {
        return $this->belongsTo(Location::class,  'end_hub');
    }
}
