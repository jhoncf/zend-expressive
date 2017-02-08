<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 15/07/16
 * Time: 14:00
 */

namespace App\Auth\Action;


use App\Util\AuthComponents;
use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class LogoutAction {
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
	 * @api {post} /api/Auth/Logout Logout Admin
	 * @apiName LogoutAction
	 * @apiVersion 0.1.0
	 * @apiGroup Auth Admin
	 *
	 * @apiParam {string} userName userName of the entry User.
	 * @apiParam {string} sessionKey Password of the entry User.
	 * @apiParam {Object} SessionData
	 * @apiParam {string} application
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
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *  {
	 *      "message": "Logout successful",
	 *      "error": false
	 *      "success": true
	 *  }
	 *
	 * @apiError UserNotFound The id of the User was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error"=>[
	 *          "code"=> 500,
	 *          "message"=> "Usuário não está logado"
	 *          ]
	 *     }
	 */

	/**
	 * @param CustomRequest $request
	 * @param ResponseInterface $response
	 * @param callable|null $next
	 * @return JsonResponse
	 * @throws \Exception
	 */
	public function __invoke(CustomRequest $request, ResponseInterface $response, callable $next = null) {
		try {
			$params = $request->getContent();
			$this->authComponents->sessionStart();
			if (!$this->authComponents->sessionStatus($params)) {
				return new JsonResponse([
					'message' => 'Usuário não está logado',
					'error' => true
				]);
			}
			$this->authComponents->sessionDestroy();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse([
			'message' => 'Logout efetuado com sucesso',
			'success' => true
		]);
	}
}