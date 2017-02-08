<?php

namespace App\Auth\Action;

use App\Admin\Entity\UserUsersRepository;
use App\Util\AuthComponents;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 15/07/16
 * Time: 14:02
 */
class LoginStatusAction {

	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @var
	 */
	private $em;

	/**
	 * @var AuthComponents
	 */
	private $authComponents;

	/**
	 * @var UserUsersRepository
	 */
	private $repository;

	/**
	 * LoginAction constructor.
	 * @param ContainerInterface $container
	 * @param $em
	 */
	public function __construct(ContainerInterface $container, EntityManager $em) {
		$this->em = $em;
		$this->container = $container;
		$this->authComponents = new AuthComponents($container);
		$this->repository = $em->getRepository('App\Admin\Entity\UserUsers');
	}

	/**
	 * @api {post} /api/Auth/LoginStatus Login Admin Status
	 * @apiName LoginStatus
	 * @apiVersion 0.1.0
	 * @apiGroup Auth Admin
	 *
	 * @apiParam {string} userName
	 * @apiParam {string} sessionKey
	 *
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "userName": "teste@test.com",
	 *      "sessionKey": "33mfjqhfj2if7opnla9sja8lc7",
	 *      "SessionData": {
	 *          "application": "dusers_admin"
	 *      }
	 * }
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 *
	 * @apiSuccessExample Success-Response:
	 *   HTTP/1.1 200 OK
	 *   {
	 *       "message": "Status not logged",
	 *       "error": true,
	 *       "status": false
	 *   }
	 *
	 * @apiError UserNotFound The id of the User was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error"=>[
	 *          "code"=> 1002,
	 *          ]
	 *     }
	 */

	/**
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param callable|null $next
	 * @return JsonResponse
	 * @throws \Exception
	 */
	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
		try {
			$params = $request->withHeader('JSON', 'application/json')
			                  ->getBody()
			                  ->getContents();
			$params = json_decode($params, true);

			$sessionStatus = $this->authComponents->sessionStart()
			                                      ->sessionStatus($params);

			if (!$sessionStatus) {
				return new JsonResponse([
					'message' => 'Status not logged',
					'error' => true,
					'status' => false
				]);
			}

			$sessionData = $this->authComponents->sessionRead();

		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
		return new JsonResponse([
			'result' => $sessionData,
			'message' => 'Status logged',
			'error' => false,
			'status' => true
		]);

	}

}