<?php
/**
 * Created by PhpStorm.
 * User: guodongnie
 * Date: 2018/1/15
 * Time: 下午12:27
 */

return [
    'proxy' => [
        'grant_type' => env('OAUTH_GRANT_TYPE'),
        'client_id'  => env('OAUTH_CLIENT_ID'),
        'client_secret' => env('OAUTH_CLIENT_SECRET'),
        'scope'   => env('OAUTH_SCOPE', '*'),
    ],
];