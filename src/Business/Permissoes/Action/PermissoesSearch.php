<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 09/08/16
 * Time: 15:02
 */

namespace Business\Permissoes\Action;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class PermissoesSearch {
	/**
	 * @var \Business\Permissoes\Entity\DPermissionsRepository
	 */
	private $repository;

	/**
	 * PermissoesAction constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Permissoes\Entity\DPermissions');
	}

	/**
	 * @api {post} /api/Permissoes/Search Buscar (Filtro) Permissão
	 * @apiName PermissoesSearch
	 * @apiGroup Permissoes
	 * @apiVersion 0.1.0
	 *
	 * @apiParamExample {json} Request-Example:
	 *      {
	 *      "params": {
	 *              "productId":1
	 *          }
	 *      }
	 *
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
	 *              "slug": "teste",
	 *              "product": {
	 *                  "id": 1,
	 *                  "name": "Produto Teste 1",
	 *                  "url": "http://www.google.com.br",
	 *                  "slug": "permissao_teste",
	 *                  "imageUrl": null
	 *              }
	 *          }
	 *      ]
	 *   }
	 *
	 * @apiError PermissoesNotFound
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
			$param = $request->getContent();

			if (isset($param['params']['productId'])) {
				$result = $this->repository->findByProductId($param['params']['productId']);

				/**
				 * @error Nenhum resultado encontrado.
				 */
				if (empty($result)) {
					return new JsonResponse([
						'error' => true,
						'message' => 'Nenhum resultado encontrado.'
					]);
				}
			} else {
				$result = $this->repository->findAll();
			}

		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage(), 400);
		}
		return new JsonResponse([
			'result' => $result,
			'count' => count($result)
		]);
	}

}