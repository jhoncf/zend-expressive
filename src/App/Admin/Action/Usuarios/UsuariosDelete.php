<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:49
 */

namespace App\Admin\Action\Usuarios;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class UsuariosDelete
 * @package Business\Usuarios\Action
 */
class UsuariosDelete {
	/**
	 * @var \App\Admin\Entity\UserUsersRepository
	 */
	private $repository;

	/**
	 * UsuariosDelete constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('App\Admin\Entity\UserUsers');
	}

	/**
	 * @api {delete} /api/Admin/Usuarios/:id Remover Administrador
	 * @apiName AdminUsuariosDelete
	 * @apiGroup Admin - Usuarios
	 * @apiVersion 0.1.0
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "message": "success"
	 *     }
	 *
	 * @apiError AdminUsuariosNotInserted
	 *
	 * @apiErrorExample Error-Response
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Não foi possível Remover o Administrador",
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
			$id = $request->getAttribute('resourceId');

			$this->repository->delete($id);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['message' => 'success']);
	}
}