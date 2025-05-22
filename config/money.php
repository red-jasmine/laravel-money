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
        'iso'     => ['CNY', 'USD', 'EUR'], //    'all' 选择全部
        'bitcoin' => false, //    'false' 不支持
        // 自定义货币
        'custom'  => [

        ],
    ],
];
