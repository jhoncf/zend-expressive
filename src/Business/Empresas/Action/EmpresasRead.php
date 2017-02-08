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

class EmpresasRead {

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
	 * @api {get} /api/Empresas/:id Buscar empresa
	 * @apiName EmpresasRead
	 * @apiGroup Empresas
	 * @apiVersion 0.1.0
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result":
	 *          {
	 *              "id": 1,
	 *              "name": "Teste",
	 *              "shortName": null,
	 *              "imageId": null,
	 *              "created": null,
	 *              "modified": null,
	 *              "domain": null
	 *          }
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

			/**
			 * @var $entity \Business\Empresas\Entity\DCompanies
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if (empty($entity)) {
				return new JsonResponse([
					'message' => 'Empresa inválida ou não encontrada',
					'error' => 'true'
				]);
			}

			$result = $this->repository->read($entity);

			if(!$result){
				return new JsonResponse([
					'error' => true,
				    'message' => 'Empresa não encontrada'
				]);
			}
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage(), 400);
		}
		return new JsonResponse($result);
	}
}