<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:44
 */

namespace App\Admin\Action\Usuarios;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class UsuariosAction
 * @package App\Admin\Action
 */
class UsuariosAction {
	/**
	 * @var \App\Admin\Entity\UserUsers
	 */
	private $repository;

	/**
	 * @api {get} /api/Admin/Usuarios Todos os Administradores
	 * @apiName AdminUsuariosAction
	 * @apiGroup Admin - Usuarios
	 * @apiVersion 0.1.0
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result": [
	 *          {
	 *              "id": 1,
	 *              "username": "teste",
	 *              "password": "teste",
	 *              "surname": "teste",
	 *              "name": "Teste",
	 *              "email": "teste",
	 *              "created": null,
	 *              "modified": null
	 *          }
	 *       ],
	 *      "count": 1
	 *     }
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
	public function __construct(EntityManager $em, $container = null) {
		$this->repository = $em->getRepository('App\Admin\Entity\UserUsers');
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