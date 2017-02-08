<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 15/07/16
 * Time: 14:00
 */

namespace Business\Auth\Action;

use App\Util\AuthComponents;
use App\Util\CustomRequest;
use Business\Entities\DLoginStatuses;
use Business\Entities\DLoginStatusesRepository;
use Business\Usuarios\Entity\DUsersRepository;
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
	 * @var DUsersRepository
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
		$this->repository = $em->getRepository('Business\Usuarios\Entity\DUsers');
	}

	/**
	 * @api {post} /api/Auth/Logout Logout
	 * @apiName LogoutAction
	 * @apiVersion 0.1.0
	 * @apiGroup Auth App Externo
	 *
	 * @apiParam {string} userName userName of the entry User.
	 * @apiParam {string} sessionKey Password of the entry User.
	 *
	 * @apiParamExample {json} Request-Example:
	 *  {"SessionData":{
	 *      "userName": "teste@test.com",
	 *      "sessionKey": "33mfjqhfj2if7opnla9sja8lc7",
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
			$params = $request->getContent()['SessionData'];

			/**
			 * @var $dLoginStatusesRepository DLoginStatusesRepository
			 */
			$dLoginStatusesRepository = $this->em->getRepository('Business\Entities\DLoginStatuses');
			$sessionResult = $dLoginStatusesRepository->findBySessionKey($params['sessionKey']);

			/**
			 * Alterando status da sessão do usuário
			 *
			 * @var $dLoginStatusesEntity DLoginStatuses
			 */
			$dLoginStatusesEntity = $dLoginStatusesRepository->find($sessionResult['id']);

			$dLoginStatusesEntity->setActive(false);
			$dLoginStatusesRepository->update($dLoginStatusesEntity);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse([
			'error' => false
		]);
	}
}