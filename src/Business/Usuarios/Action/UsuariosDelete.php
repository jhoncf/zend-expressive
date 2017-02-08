<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:49
 */

namespace Business\Usuarios\Action;


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
	 * @var \Business\Usuarios\Entity\DUsersRepository
	 */
	private $repository;

	/**
	 * UsuariosDelete constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Usuarios\Entity\DUsers');
	}

	/**
	 * @api {delete} /api/Usuarios/:id Remover Produto
	 * @apiName UsuariosDelete
	 * @apiGroup Usuarios
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
	 * @apiError ProdutoNotInserted
	 *
	 * @apiErrorExample Error-Response
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Não foi possível Remover o Produto",
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