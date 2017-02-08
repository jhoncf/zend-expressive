<?php
/**
 * Created by PhpStorm.
 * User: Dcide
 * Date: 12/05/2016
 * Time: 16:08
 */

namespace App\Auth\Action;

use App\Admin\Entity\UserUsersRepository;
use App\Util\AuthComponents;
use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class LoginAction
 * @package App\Auth\Action
 */
class LoginAction {

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
	 * @api {post} /api/Auth/Login Login Admin
	 * @apiName LoginAdmin
	 * @apiVersion 0.1.0
	 * @apiGroup Auth Admin
	 *
	 * @apiParam {string} userName userName of the entry User.
	 * @apiParam {string} password Password of the entry User.
	 * @apiParam {string} application Product Name
	 *
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "userName": "teste@test.com",
	 *      "password": "P@ssw0rd",
	 *      "SessionData": {
	 *          "application" : "dusers_admin"
	 *      }
	 * }
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 * {
	 *      "userName": "jhonatas@dcide.com.br",
	 *      "name": "Jhonatas",
	 *      "email": "jhonatas@dcide.com.br",
	 *      "sessionKey": "la5qcqk04rqbhq3tk3r8u8ckn1"
	 * }
	 *
	 * @apiError UserNotFound The id of the User was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error"=>[
	 *          "code"=> 1002,
	 *          "message"=>"Login não efetuado"
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

			$user = $this->repository->findByUserName($params['userName']);

			if ($user === null || $this->authComponents->validPassword($params['password'], $user['password']) === false) {
				return new JsonResponse([
					'message' => 'Login ou senha inválidos',
					'success' => false,
					'error' => true
				]);
			}

			$this->authComponents->sessionStart()
			                     ->sessionWrite($user);

			$sessionData = $this->authComponents->sessionRead();

		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse([
			'result' => $sessionData,
			'success' => true,
			'error' => false
		]);
	}
}