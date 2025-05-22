<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel money
     |--------------------------------------------------------------------------
     */
    'locale'            => config('app.locale', 'en_US'),
    'defaultCurrency'   => config('app.currency', 'CNY'),
    'defaultFormatter'  => null,
    'defaultSerializer' => null,
    'currencies'        => [
        'iso'     => ['CNY','USD'], //    'all' 选择全部
        'bitcoin' => false, //    'false' 不支持
        'custom'  => [
            'MJF' => [
                'name'                => 'MJF',
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
