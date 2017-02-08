<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 29/06/16
 * Time: 11:16
 */

namespace Business\Empresas\Action;

use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class EmpresasAction {

	/**
	 * @var \Business\Empresas\Entity\DCompaniesRepository
	 */
	private $repository;

	/**
	 * EmpresasAction constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Empresas\Entity\DCompanies');
	}

	/**
	 * @api {get} /api/Empresas Todas as empresas
	 * @apiName EmpresasAction
	 * @apiGroup Empresas
	 * @apiVersion 0.1.0
	 * @apiHeader {String} Authorization-Key Token do usuário
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result": [
	 *          {
	 *              "id": 1,
	 *              "name": "Teste",
	 *              "shortName": null,
	 *              "imageUrl": null,
	 *              "created": null,
	 *              "modified": null,
	 *              "domain": null
	 *          }
	 *      ],
	 *      "total": 1
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
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param callable|null $next
	 * @return JsonResponse
	 * @throws \Exception
	 */
	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
		try {
			$result = $this->repository->findAll();
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}
		return new JsonResponse([
			'result' => $result,
			'total' => $this->repository->getTotal()
		]);
	}
}