<?php

return [
    'application_opens_at'  => env('HUB_APPLICATION_OPENS_AT')
        ? \Carbon\Carbon::parse(env('HUB_APPLICATION_OPENS_AT'), 'Africa/Abidjan')
        : null,

    'application_closes_at' => env('HUB_APPLICATION_CLOSES_AT')
        ? \Carbon\Carbon::parse(env('HUB_APPLICATION_CLOSES_AT'), 'Africa/Abidjan')
        : null,
];
