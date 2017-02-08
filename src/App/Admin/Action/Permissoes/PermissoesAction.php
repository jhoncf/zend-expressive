<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:44
 */

namespace App\Admin\Action\Permissoes;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class PermissoesAction
 * @package Business\Permissoes\Action
 */
class PermissoesAction {
	/**
	 * @var \App\Admin\Entity\UserPermissionsRepository
	 */
	private $repository;

	/**
	 * PermissoesAction constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('App\Admin\Entity\UserPermissions');
	}

	/**
	 * @api {get} /api/Admin/Permissoes Todas os Permissões
	 * @apiName AdminPermissoesAction
	 * @apiGroup Admin - Permissoes
	 * @apiVersion 0.1.0
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result": [
	 *          {
	 *              "id": 2,
	 *              "name": "Teste",
	 *              "description": "Descrição teste",
	 *              "slug": "teste"
	 *          }
	 *      ],
	 *      "count": 1
	 *   }
	 *
	 * @apiError AdminPermissoesNotFound The Companies could not be listed.
	 *
	 * @apiErrorExample Error-Response
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Erro",
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
			$param = $request->getParsedBody();
			$page = isset($param['page']) ? $param['page'] : 0;
			$limit = isset($param['limit']) ? $param['limit'] : 20;
			$companies = $this->repository->findAll($page, $limit);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}
		return new JsonResponse($companies);
	}
}