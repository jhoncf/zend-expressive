<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 06/07/16
 * Time: 11:22
 */

namespace App\Admin\Action\Usuarios;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class UsuariosRead {
	/**
	 * @var \App\Admin\Entity\UserUsersRepository
	 */
	private $repository;

	/**
	 * UsuariosAction constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('App\Admin\Entity\UserUsers');
	}

	/**j_queue_testes2.dev.fmb
	 * @api {get} /api/Admin/Usuarios/:id Buscar Administrador
	 * @apiName AdminUsuariosRead
	 * @apiGroup Admin - Usuarios
	 * @apiVersion 0.1.0
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *            {
	 *             "result": [
	 *              {
	 *                  "id": 1,
	 *                  "username": "teste",
	 *                  "password": "teste",
	 *                  "surname": "teste",
	 *                  "name": "Teste",
	 *                  "email": "teste",
	 *                  "created": null,
	 *                  "modified": null,
	 *                  "perfis": {
	 *                      ["id":1, "name": "Teste", "description": "description", "slug": null]
	 *                      ["id":2, "name": "Teste2", "description": "description2", "slug": null]
	 *                    }
	 *              }
	 *             ]
	 *         }
	 *
	 * @apiError AdminUsuariosNotFound
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
			 * @var $entity \App\Admin\Entity\UserUsers
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if (empty($entity)) {
				return new JsonResponse([
					'message' => 'Usuário inválido ou não encontrado.',
					'error' => true
				]);
			}

			$result = $this->repository->read($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage(), 400);
		}
		return new JsonResponse($result);
	}
}