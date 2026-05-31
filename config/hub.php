<?php

use Carbon\Carbon;

return [
    'application_opens_at' => env('HUB_APPLICATION_OPENS_AT')
        ? Carbon::parse(env('HUB_APPLICATION_OPENS_AT'), 'Africa/Abidjan')
        : null,

    'application_closes_at' => env('HUB_APPLICATION_CLOSES_AT')
        ? Carbon::parse(env('HUB_APPLICATION_CLOSES_AT'), 'Africa/Abidjan')
        : null,
];
