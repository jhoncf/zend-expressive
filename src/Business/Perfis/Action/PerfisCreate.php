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
use Business\Perfis\Entity\DProfilesRepository;
use Business\Permissoes\Entity\DPermissionsRepository;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class PerfisCreate
 * @package Business\Perfis\Action
 */
class PerfisCreate {
	/**
	 * @var \Business\Perfis\Entity\DProfilesRepository
	 */
	private $repository;

	private $em;

	/**
	 * PerfisCreate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
		$this->repository = $em->getRepository('Business\Perfis\Entity\DProfiles');
	}

	/**
	 * @api {post} /api/Perfis Adicionar perfil
	 * @apiName PerfisCreate
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
	 *      "result": ["id": 4 ],
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
	 *          "detail": "Não foi possível cadastrar a empresa",
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
			 * @var $entity \Business\Perfis\Entity\DProfiles
			 */
			$entity = new DProfiles();
			$entity = EntityHelper::setOptions($param, $entity);

			if (isset($param['dPermissions'])) {
				/**
				 * @var $permissionRepository \Business\Permissoes\Entity\DPermissionsRepository
				 */
				$permissionRepository = $this->em->getRepository('Business\Permissoes\Entity\DPermissions');
				foreach ($param['dPermissions'] as $key => $value) {
					/**
					 * @var $permissionEntity \Business\Permissoes\Entity\DPermissions
					 */
					$permissionEntity = $permissionRepository->find($value['id']);
					$entity->addDPermission($permissionEntity);
					$permissionEntity->addDProfile($entity);
				}
			}

			$result = $this->repository->save($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse([
			'result' => ['id' => $result],
			'message' => 'success'
		]);
	}
}