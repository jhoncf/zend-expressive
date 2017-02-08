<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:49
 */

namespace App\Admin\Action\Usuarios;

use App\Admin\Entity\UserUsers;
use App\Helper\EntityHelper;
use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use App\Util\AuthComponents;
use Exceptions\DUsersExceptions;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class UsuariosUpdate
 * @package Business\Usuarios\Action
 */
class UsuariosUpdate {
	/**
	 * @var \App\Admin\Entity\UserUsersRepository
	 */
	private $repository;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var
	 */
	private $container;

	/**
	 * UsuariosUpdate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em, ContainerInterface $container) {
		$this->em = $em;
		$this->repository = $em->getRepository('App\Admin\Entity\UserUsers');
		$this->container = $container;
	}

	/**
	 * @api {put} /api/Admin/Usuarios/:id Editar Administrador
	 * @apiName AdminUsuariosUpdate
	 * @apiGroup Admin - Usuarios
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} name
	 * @apiParam {String} username
	 * @apiParam {String} surname
	 * @apiParam {String} password
	 * @apiParam {String} email
	 *
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "name": "Usuario Teste",
	 *      "username": "usuario",
	 *      "surname": "usuario",
	 *      "password": "teste",
	 *      "email": "teste@test.com",
	 *      "userProfile": {
	 *          ["id": 1],
	 *          ["id": 2]
	 *       }
	 * }
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *          "result": {
	 *              "id": 5
	 *          },
	 *          "message": "success"
	 *     }:
	 *
	 * @apiError AdminUsuariosNotInserted
	 *
	 * @apiErrorExample Error-Response
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Não foi possível Editar o Administrador",
	 *          "links": {
	 *              "related": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html"
	 *          }
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
			$authHandle = new AuthComponents($this->container);

			$param = $request->getContent();

			/**
			 * @var $userEntity UserUsers
			 */
			$userEntity = $this->repository->find($request->getAttribute('resourceId'));

			if (empty($userEntity)) {
				return new JsonResponse([
					'result' => 'Usuário inválido ou não encontrado.',
					'message' => 'error'
				]);
			}

			if (isset($param['password'])) {
				$param['password'] = $authHandle->hashPassword($param['password']);
			}

			$userEntity = EntityHelper::setOptions($param, $userEntity);

			if (isset($param['userProfile'])) {
				/**
				 * @var $userProfilesRepository \App\Admin\Entity\UserProfilesRepository
				 */
				$userProfilesRepository = $this->em->getRepository('App\Admin\Entity\UserProfiles');
				$userEntity->clearUserProfile();
				foreach ($param['userProfile'] as $key => $value) {
					/**
					 * @var $userProfileEntity \App\Admin\Entity\UserProfiles
					 */
					$userProfileEntity = $userProfilesRepository->find($value['id']);
					$userEntity->addUserProfile($userProfileEntity);
					$userProfileEntity->addUserUser($userEntity);
				}
			}

			$result = $this->repository->update($userEntity);

		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse([
			'result' => $result,
			'message' => 'success'
		]);
	}
}