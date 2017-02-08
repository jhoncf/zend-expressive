<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 06/07/16
 * Time: 11:22
 */

namespace Business\Permissoes\Action;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class PermissoesRead {
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
	 * @api {get} /api/Permissoes/:id Buscar Permissão
	 * @apiName PermissoesRead
	 * @apiGroup Permissoes
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
	 *                 "slug": "teste"
	 *          }
	 *      ]
	 *   }
	 *
	 * @apiError EmpresasNotFound The Companies could not be listed.
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

			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if (empty($entity)) {
				throw new DUsersExceptions('Permissão inválida ou não encontrado.', 400);
			}

			$result = $this->repository->read($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage(), 400);
		}
		return new JsonResponse(['result' => $result]);
	}

}