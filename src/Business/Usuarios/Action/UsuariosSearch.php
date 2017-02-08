<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 10/10/16
 * Time: 10:51
 */

namespace Business\Usuarios\Action;


use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class UsuariosSearch {
	/**
	 * @var \Business\Usuarios\Entity\DUsersRepository
	 */
	private $repository;

	/**
	 * @api {get} /api/UsuariosSearch Buscar (Filtro) Usuários
	 * @apiName UsuariosSearch
	 * @apiGroup Usuarios
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} companyId
	 *
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "companyId": 7,
	 *      "newsletter": [
	 *              1,
	 *              2
	 *          ]
	 * }
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result": [
	 *              {
	 *                  "id": 3,
	 *                  "name": "Usuario Teste 3",
	 *                  "surname": "usuario",
	 *                  "email": "teste",
	 *                  "imageId": 12313,
	 *                  "created": {
	 *                      "date": "2016-07-07 15:53:53.000000",
	 *                      "timezone_type": 3,
	 *                      "timezone": "America/Sao_Paulo"
	 *                  },
	 *                  "modified": null,
	 *                  "blocked": false,
	 *                  "deleted": false,
	 *                  "deletedDate": null,
	 *                  "status": "migrated",
	 *              }
	 *      ],
	 *      "count": 1
	 *   }
	 *
	 * @apiError UsuariosNotFound The Usuarios could not be listed.
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
	 * UsuariosAction constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Usuarios\Entity\DUsers');
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

			$filter = [];

			if (isset($param['companyId'])) {
				$filter['dcomp.id'] = $param['companyId'];
			}

			if (isset($param['newsletter'])) {
				$filter['dnewsl.id'] = $param['newsletter'];
			}

			$companies = $this->repository->findByParams($filter);

		} catch (DUsersExceptions $e) {
			throw new DUsersExceptions($e->getMessage());
		}

		return new JsonResponse([
			'result' => $companies,
			'message' => 'success'
		]);
	}
}