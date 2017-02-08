<?php

namespace App\Action;

use App\Util\CustomRequest;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;

class HomePageAction {
	private $router;

	private $template;

	public function __construct(Router\RouterInterface $router, Template\TemplateRendererInterface $template = null) {
		$this->router = $router;
		$this->template = $template;
	}

	public function __invoke(CustomRequest $request, ResponseInterface $response, callable $next = null) {

		return new JsonResponse([
			$request->getContent()
		]);
	}
}
