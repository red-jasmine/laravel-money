<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel money
     |--------------------------------------------------------------------------
     */
    'locale'            => config('app.locale', 'en_US'),
    'defaultCurrency'   => config('app.currency', 'USD'),
    'defaultFormatter'  => null,
    'defaultSerializer' => null,
    'currencies'        => [
        'iso'     => 'all',
        'bitcoin' => 'all',
        'custom'  => [
            'MY1' => [
                'name'                => 'MY1',
                'code'                => 999,
                'minorUnit'           => 2,
                'subunit'             => 100,
                'symbol'              => 'R',
                'symbol_first'        => true,
                'decimal_mark'        => '.',
                'thousands_separator' => ',',
            ],
        ],
    ],
];
