<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 06/07/16
 * Time: 11:22
 */

namespace Business\Produtos\Action;

use App\Util\CustomRequest;
use Business\Produtos\Entity\DProducts;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class ProdutosRead {
	/**
	 * @var \Business\Produtos\Entity\DProductsRepository
	 */
	private $repository;

	/**
	 * ProdutosAction constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Produtos\Entity\DProducts');
	}

	/**
	 * @api {get} /api/Produtos/:id Buscar Produto
	 * @apiName ProdutosRead
	 * @apiGroup Produtos
	 * @apiVersion 0.1.0
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result": [
	 *          {
	 *          "id": 1,
	 *          "order": 1,
	 *          "name": "Produto Teste",
	 *          "created": null,
	 *          "modified": null,
	 *          "isTemp": true
	 *          }
	 *      ],
	 *      "count": 1
	 *   }
	 *
	 * @apiError ProdutosNotFound
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

			/**
			 * @var $entity DProducts
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if (empty($entity)) {
				new JsonResponse([
					'error' => true,
					'message' => 'Produto inválido ou não encontrado'
				]);
			}

			$result = $this->repository->read($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage(), 200);
		}
		return new JsonResponse(['result' => $result]);
	}
}