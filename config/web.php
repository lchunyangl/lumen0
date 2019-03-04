<?php
/**
 * Created by PhpStorm.
 * User: lilong
 * Date: 2019/3/1
 * Time: 16:22
 */
return [
    'proxy_url' => env('PROXY_URL', 'http://192.168.31.91:38081'),
    'cookie' => 'laravel_token',
    'aliases' => [
        \App\Facades\Proxy::class => 'proxy'
    ]
];