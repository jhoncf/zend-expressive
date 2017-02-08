<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:49
 */

namespace App\Admin\Action\Permissoes;

use App\Helper\EntityHelper;
use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class PermissoesUpdate
 * @package Business\Permissoes\Action
 */
class PermissoesUpdate {
	/**
	 * @var \App\Admin\Entity\UserPermissionsRepository
	 */
	private $repository;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * PermissoesUpdate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
		$this->repository = $em->getRepository('App\Admin\Entity\UserPermissions');
	}

	/**
	 * @api {put} /api/Admin/Permissoes/:id Editar Permissão
	 * @apiName AdminPermissoesUpdate
	 * @apiGroup Admin - Permissoes
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} name
	 * @apiParam {String} description
	 * @apiParam {String} slug
	 * @apiParam {Number} dProductId
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
			 * @var $entity \App\Admin\Entity\UserPermissions
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if(empty($entity)){
				throw new DUsersExceptions('Permissão inválido(a) ou não encontrado(a).');
			}

			$entity = EntityHelper::setOptions($param, $entity);
			$result = $this->repository->update($entity);

		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['result' => $result, 'message' => 'success']);
	}
}