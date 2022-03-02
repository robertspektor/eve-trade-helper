<?php

declare(strict_types=1);

return [
    'token' => env('EVE_TOKEN'),
    'esi_url' => env('EVE_ESI_URL'),
    'trade_hubs' => [
        10000002 => 60003760, // JITA
        10000043 => 60008494, // AMARR
        10000032 => 60011866 // DODIXIE
    ]
];
