<?php
/**
 * Created by PhpStorm.
 * User: Dcide
 * Date: 13/05/2016
 * Time: 11:03
 */

namespace App\Service;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Exceptions\DUsersExceptions;

class AuthService {

	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @var
	 */
	private $config;

	/**
	 * @var $em EntityManager
	 */
	private $em;

	/**
	 * LoginService constructor.
	 * @param ContainerInterface $container
	 * @param $config
	 */
	public function __construct(ContainerInterface $container, $config) {
		$this->container = $container;
		$this->em = $container->get(EntityManager::class);
		$this->config = $config;
	}

	/**
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param callable|null $next
	 * @return JsonResponse
	 */

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
		try {
			$resource = ucfirst($request->getAttribute('resource'));

			$contents = $request->withHeader('JSON', 'application/json')
			                    ->getBody()
			                    ->getContents();

			$param = json_decode($contents, true);
			$customRequest = new CustomRequest($request);
			$customRequest->setContent($param);

			if ($resource == 'Login') {
				if ((!isset($param['userName']) && empty($param['userName'])) && (!isset($param['email']) && empty($param['email']))) {
					throw new DUsersExceptions('Campo userName/email obrigatório');
				}

				if (!isset($param['password']) && empty($param['password'])) {
					throw new DUsersExceptions('Campo password obrigatório');
				}

				if (!isset($param["SessionData"]['application']) && empty($param["SessionData"]['application'])) {
					throw new DUsersExceptions('Campo application precisa ser definido');
				}

			}

			$origin = isset($param["SessionData"]['application'])? $param["SessionData"]['application'] : null;


			if (isset($origin) && $origin != "dusers_admin" || $origin == null) {
				$class = sprintf('Business\\Auth\\Action\\%s', $resource . 'Action');
				$callable = new $class($this->container, $this->em);
				return $callable($customRequest, $response);
			}

			$class = sprintf('App\\Auth\\Action\\%s', $resource . 'Action');

			if (!class_exists($class)) {
				return $this->throwJsonException(sprintf('Requested resource not found: %s', $class), 400);
			}

			$callable = new $class($this->container, $this->em);
			return $callable($customRequest, $response);
		} catch (\Exception $e) {
			return $this->throwJsonException($e->getMessage(), $e->getCode());
		}
	}

	/**
	 * @param $message
	 * @param $status
	 * @return JsonResponse
	 */

	protected function throwJsonException($message, $status) {
		// Ensure a valid HTTP status
		if (!is_numeric($status) || ($status < 400) || ($status > 599)) {
			$status = 500;
		}
		$response = new JsonResponse([], $status);
		$errors = [
			'error' => [
				'status' => $response->getReasonPhrase() ? $status : 400,
				'title' => $response->getReasonPhrase() ?: 'Bad Request',
				'detail' => $message,
				'links' => ['related' => 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html']
			]
		];
		return new JsonResponse($errors, $response->getStatusCode());
	}
}