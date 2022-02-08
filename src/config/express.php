<?php
return [
    /**
     * 快递100 相关参数配置
     * customer 快递100中的customer参数
     * api_key 快递100中的授权Key参数
     */
    'express_hundred' => [
        'customer' => env('EXPRESS_CUSTOMER', ''),
        'api_key' => env('EXPRESS_API_KEY',''),
    ],
];