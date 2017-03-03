<?php

$regex = '[a-zA-Z0-9]+';

return [
	'dependencies' => [
		'invokables' => [
			Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
		],
	],

	'routes' => [
		[
			'name' => 'home',
			'path' => '/',
			'middleware' => App\Action\HomePageAction::class,
			'allowed_methods' => ['GET'],
		],
		[
			'name' => 'Auth',
			'path' => '/api/Auth/{resource:[a-zA-Z\_]+}',
			'middleware' => App\Service\AuthService::class,
			'allowed_methods' => [
				'POST',
				'OPTIONS'
			]
		],
        [
            'name' => 'apiPosts',
            'path' => '/api/{resource:Posts}',
            'middleware' => App\Action\ApiAction::class,
            'allowed_methods' => [
                'GET'
            ],
        ],
		[
			'name' => 'api',
			'path' => '/api/{resource:[a-zA-Z]+}[/{resourceId:' . $regex . '}[/{relation:[a-z]+}]]',
			'middleware' => App\Action\ApiAction::class,
			'allowed_methods' => [
				'GET',
				'POST',
				'PUT',
				'PATCH',
				'DELETE'
			],
		],
	],
];
