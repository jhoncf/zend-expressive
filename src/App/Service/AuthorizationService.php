<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 21/07/16
 * Time: 16:07
 */

namespace App\Service;


use App\Util\AuthComponents;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class AuthorizationService {
	private $container;

	private $authComponents;

	public function __construct(ContainerInterface $container, $config) {
		$this->container = $container;
		$this->authComponents = new AuthComponents($container);
	}

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {

		$auth = $request->getHeader('authorization-key');
		$server_params = $request->getServerParams();

		if (strpos($server_params['REQUEST_URI'], "/api/Auth") !== false ||strpos($server_params['REQUEST_URI'], "/api/LoginStatus/UpdateStatus") !== false) {
			return $next($request, $response);
		}

		if (!isset($auth[0])) {
			return new JsonResponse(array(
				"error" => [
					"code" => 1500,
					"message" => "Invalid Session!"
				]
			));
		}

		$loginStatus = $this->authComponents->sessionStart()
		                                    ->isLogged($auth[0]);

		if (!$loginStatus) {
			return new JsonResponse(array("error" => "Invalid Session!"));
		}

		if ($auth[0] == $this->authComponents->sessionRead()['sessionKey']) {
			return $next($request, $response);
		} else {
			return new JsonResponse(array(
				"error" => [
					"code" => 1500,
					"message" => "Invalid Session!"
				]
			));
		}
	}
}