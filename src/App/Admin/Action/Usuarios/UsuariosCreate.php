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
use App\Util\AuthComponents;
use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class UsuariosCreate
 * @package App\Admin\Action\Usuarios
 */
class UsuariosCreate {

	/**
	 * @var \App\Admin\Entity\UserUsersRepository
	 */
	private $repository;

	/**
	 * @var
	 */
	private $em;

	/**
	 * @var
	 */
	private $container;

	/**
	 * @api {post} /api/Admin/Usuarios Adicionar Administrador
	 * @apiName AdminUsuariosCreate
	 * @apiGroup Admin - Usuarios
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} name
 	 * @apiParam {String} username
	 * @apiParam {String} surname
	 * @apiParam {String} password
	 * @apiParam {String} email
	 * @apiParam {Object} perfis
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
	 *      "result": ["id": 2 ],
	 *      "message": "success"
	 *     }:
	 *
	 * @apiError AdminUsuariosCreate
	 *
	 * @apiErrorExample Error-Response
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Não foi possível cadastrar o Administrador",
	 *          "links": {
	 *              "related": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html"
	 *          }
	 *     }
	 */

	/**
	 * UsuariosCreate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em, ContainerInterface $container) {
		$this->em = $em;
		$this->repository = $em->getRepository('App\Admin\Entity\UserUsers');
		$this->container = $container;
	}

	/**
	 * @param CustomRequest $request
	 * @param ResponseInterface $response
	 * @param callable|null $next
	 * @return JsonResponse
	 * @throws \Exception
	 */
	public function __invoke(CustomRequest $request, ResponseInterface $response, callable $next = null) {
		try {
			$param = $request->getContent();

			$authHandle = new AuthComponents($this->container);

			$param['password'] = $authHandle->hashPassword($param['password']);

			/**
			 * @var $entity \App\Admin\Entity\UserUsers
			 */
			$entity = new UserUsers();

			$entity = EntityHelper::setOptions($param, $entity);

			if (isset($param['userProfile'])) {
				/**
				 * @var $userProfilesRepository \App\Admin\Entity\UserProfilesRepository
				 */
				$userProfilesRepository = $this->em->getRepository('App\Admin\Entity\UserProfiles');
				foreach ($param['userProfile'] as $key => $value) {
					/**
					 * @var $userProfilesEntity \App\Admin\Entity\UserProfiles
					 */
					$userProfilesEntity = $userProfilesRepository->find($value['id']);
					$entity->addUserProfile($userProfilesEntity);
					$userProfilesEntity->addUserUser($entity);
				}
			}

			$result = $this->repository->save($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['result' => ['id' => $result], 'message' => 'success']);
	}
}