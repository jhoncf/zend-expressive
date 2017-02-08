<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:44
 */

namespace App\Admin\Action\Perfis;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class PerfisAction
 * @package Business\Perfis\Action
 */
class PerfisRead {
	/**
	 * @var \App\Admin\Entity\UserProfilesRepository
	 */
	private $repository;

	/**
	 * @api {get} /api/Admin/Permissoes/:id Buscar Perfil
	 * @apiName AdminPerfisRead
	 * @apiGroup Admin - Perfis
	 * @apiVersion 0.1.0
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result": [
	 *          {
	 *              "id": 1,
	 *              "name": "Teste",
	 *              "description": "description",
	 *              "slug": null,
	 *              "userPermission": {
	 *                  ["id": 1],
	 *                  ["id": 2]
	 *               }
	 *          }
	 *      ]
	 *   }
	 *
	 * @apiError AdminPerfisNotFound The AdminPerfis could not be listed.
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
	 * PerfisRead constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('App\Admin\Entity\UserProfiles');
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
			/**
			 * @var $entity \App\Admin\Entity\UserProfiles
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if (empty($entity)) {
				return new JsonResponse([
					'message' => 'Perfil inválido(a) ou não encontrado(a).',
					'error' => 'true'
				]);
			}

			$result = $this->repository->read($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}
		return new JsonResponse($result);
	}
}