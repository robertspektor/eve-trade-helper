<?php

declare(strict_types=1);

namespace App\Services\Universe;


use App\Services\Universe\Exceptions\FailedGetNamesException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class GetNameService
{
    const URL = '/universe/names';

    /**
     * @param array<int> $ids
     * @throws FailedGetNamesException
     */
    public static function getNames(array $ids): Collection
    {
        $url = config('eve.esi_url') . self::URL;
        $response = Http::post($url, $ids);
        if ($response->status() !== 200) {
            throw new FailedGetNamesException($response->body());
        }
        return $response->collect();
    }
}
