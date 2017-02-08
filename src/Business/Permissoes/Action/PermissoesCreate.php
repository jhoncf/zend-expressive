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
 * Class PermissoesCreate
 * @package Business\Permissoes\Action
 */
class PermissoesCreate {

	/**
	 * @var \Business\Permissoes\Entity\DPermissionsRepository
	 */
	private $repository;

	/**
	 * @var
	 */
	private $em;

	/**
	 * @api {post} /api/Permissoes/:id Adicionar Permissões
	 * @apiName PermissoesCreate
	 * @apiGroup Permissoes
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} name
	 * @apiParam {Number} dProductId
	 * @apiParam {String} description
	 * @apiParam {String} slug
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
	 * PermissoesCreate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
		$this->repository = $em->getRepository('Business\Permissoes\Entity\DPermissions');
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

			$entity = new DPermissions();
			$product = $this->em->getRepository('Business\Produtos\Entity\DProducts')->find($param['dProductId']);

			$entity->setDProduct($product);
			$entity = EntityHelper::setOptions($param, $entity);

			$companies = $this->repository->save($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['result' => ['id' => $companies], 'message' => 'success']);
	}
}