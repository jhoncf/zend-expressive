<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:49
 */

namespace App\Admin\Action\Perfis;

use App\Helper\EntityHelper;
use App\Util\CustomRequest;
use Business\Perfis\Entity\DProfiles;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class PerfisUpdate
 * @package App\Admin\Action\Perfis
 */
class PerfisUpdate {
	/**
	 * @var \App\Admin\Entity\UserProfilesRepository
	 */
	private $repository;

	/**
	 * @var $em
	 */
	private $em;

	/**
	 * PerfisUpdate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('App\Admin\Entity\UserProfiles');
		$this->em = $em;
	}

	/**
	 * @api {put} /api/Admin/Perfis/:id Editar Perfil
	 * @apiName AdminPerfisUpdate
	 * @apiGroup Admin - Perfis
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} name
	 * @apiParam {String} description
	 * @apiParam {String} slug
	 *
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "name": "Perfil Teste",
	 *      "description": "Perfil",
	 *      "slug": "perfil",
	 *      "userPermission": {
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
	 *      "message": "success"
	 *     }:
	 *
	 * @apiError AdminPerfisUpdateNotInserted The AdminPerfisUpdate could not be created.
	 *
	 * @apiErrorExample Error-Response
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Não foi possível Editar o Perfil",
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
			$param = $request->getContent();

			/**
			 * @var $entity \App\Admin\Entity\UserProfiles
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if(empty($entity)){
				throw new DUsersExceptions('Perfil inválido(a) ou não encontrado(a).');
			}

			$entity = EntityHelper::setOptions($param, $entity);

			if (isset($param['userPermission'])) {
				/**
				 * @var $userPermissionsRepository \App\Admin\Entity\UserPermissionsRepository
				 */
				$userPermissionsRepository = $this->em->getRepository('App\Admin\Entity\UserPermissions');
				$entity->clearUserPermission();
				foreach ($param['userPermission'] as $key => $value) {
					/**
					 * @var $userProfilesEntity \App\Admin\Entity\UserPermissions
					 */
					$userProfilesEntity = $userPermissionsRepository->find($value['id']);
					$entity->addUserPermission($userProfilesEntity);
					$userProfilesEntity->addUserProfile($entity);
				}
			}

			$result = $this->repository->update($entity);

		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['result' => $result, 'message' => 'success', 'error' => false]);
	}
}