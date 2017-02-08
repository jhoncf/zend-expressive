<?php
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

return [
	'dependencies' => [
		'invokables' => [
			Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
		],
		'factories' => [
			Application::class => ApplicationFactory::class,
			Helper\UrlHelper::class => Helper\UrlHelperFactory::class,
			Zend\Expressive\Application::class => Zend\Expressive\Container\ApplicationFactory::class,
			Doctrine\Common\Cache\Cache::class => App\Container\DoctrineRedisCacheFactory::class,
			Doctrine\ORM\EntityManager::class => App\Container\DoctrineFactory::class,

			App\Action\HomePageAction::class => App\Action\HomePageFactory::class,
			App\Action\ApiAction::class => App\Action\ApiFactory::class,
			App\Service\AuthService::class => App\Service\ServiceFactory::class,
		    App\Service\AuthorizationService::class => App\Service\ServiceFactory::class,
		]
	]
];
