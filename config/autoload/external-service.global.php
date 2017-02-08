<?php
/**
 * Created by PhpStorm.
 * User: Dcide
 * Date: 13/05/2016
 * Time: 10:59
 */


return [
    "App\Service\LoginService" =>[
        'host' => 'dusers_fmb',
        'port' => 80,
        'path' => '/api/login',
        'protocol' => 'http',
    ],
    "App\Service\LogoutService" =>[
        'host' => 'dusers_fmb',
        'port' => 80,
        'path' => '/api/logout',
        'protocol' => 'http',
    ],
    "GITLAB" =>[
       
    ]

];