<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:49
 */

namespace Business\Perfis\Action;

use App\Helper\EntityHelper;
use App\Util\CustomRequest;
use Business\Perfis\Entity\DProfiles;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class PerfisUpdate
 * @package Business\Perfis\Action
 */
class PerfisUpdate {
	/**
	 * @var \Business\Perfis\Entity\DProfilesRepository
	 */
	private $repository;

	/**
	 * @var
	 */
	private $em;

	/**
	 * PerfisUpdate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Perfis\Entity\DProfiles');
		$this->em = $em;
	}

	/**
	 * @api {put} /api/Perfis/:id Editar Perfil
	 * @apiName PerfisUpdate
	 * @apiGroup Perfis
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} name
	 * @apiParam {String} description
	 * @apiParam {String} slug
	 * @apiParam {Number} order
	 * @apiParam {Boolean} hidden
	 * @apiParam {Number} productOrder
	 * @apiParam {Object} dPermission
	 *
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "name": "Perfis Teste",
	 *      "description": "Perfil description Edited",
	 *      "slug": "perfil_description_Edited",
	 *      "order": "1",
	 *      "product_order": "1",
	 *      "dPermissions": {
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
	 * @apiError CompanyNotInserted The Company could not be created.
	 *
	 * @apiErrorExample Error-Response
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Não foi possível Editar a empresa",
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
			 * @var $entity DProfiles
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if (empty($entity)) {
				return new JsonResponse([
					'result' => 'Usuário inválido ou não encontrado.',
					'message' => 'error'
				]);
			}

			$entity = EntityHelper::setOptions($param, $entity);

			if (isset($param['dPermission'])) {
				/**
				 * @var $permissionRepository \Business\Permissoes\Entity\DPermissionsRepository
				 */
				$permissionRepository = $this->em->getRepository('Business\Permissoes\Entity\DPermissions');
				$entity->clearDPermission();
				foreach ($param['dPermission'] as $key => $value) {
					/**
					 * @var $permissionEntity \Business\Permissoes\Entity\DPermissions
					 */
					$permissionEntity = $permissionRepository->find($value['id']);
					$entity->addDPermission($permissionEntity);
					$permissionEntity->addDProfile($entity);

				}
			}

			$result = $this->repository->update($entity);

		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse([
			'result' => $result,
			'message' => 'success'
		]);
	}
}