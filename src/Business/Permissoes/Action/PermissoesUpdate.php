<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:49
 */

namespace Business\Permissoes\Action;

use App\Helper\EntityHelper;
use App\Util\CustomRequest;
use Business\Permissoes\Entity\DPermissions;
use Business\Produtos\Entity\DProducts;
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
	 * @var \Business\Permissoes\Entity\DPermissionsRepository
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
		$this->repository = $em->getRepository('Business\Permissoes\Entity\DPermissions');
	}

	/**
	 * @api {put} /api/Permissoes/:id Editar Permissão
	 * @apiName PermissoesUpdate
	 * @apiGroup Permissoes
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
			 * @var $entity DPermissions
			 */

			$product = $this->em->getRepository('Business\Produtos\Entity\DProducts')->find($param['dProductId']);

			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if(empty($entity)){
				throw new DUsersExceptions('Perfil inválido ou não encontrado.');
			}

			$entity->setDProduct($product);

			$entity = EntityHelper::setOptions($param, $entity);
			$result = $this->repository->update($entity);

		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['result' => $result, 'message' => 'success']);
	}
}