<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:44
 */

namespace Business\Perfis\Action;

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
	 * @var \Business\Perfis\Entity\DProfilesRepository
	 */
	private $repository;

	/**
	 * PerfisAction constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Perfis\Entity\DProfiles');
	}

	/**
	 * @api {get} /api/Perfis Buscar o Perfil
	 * @apiName PerfisRead
	 * @apiGroup Perfis
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
	 *              "order": null,
	 *              "created": null,
	 *              "modified": {
	 *                "date": "2016-07-05 17:15:14.000000",
	 *                "timezone_type": 3,
	 *                "timezone": "America/Sao_Paulo"
	 *              },
	 *              "hidden": false,
	 *              "productOrder": null,
	 *              "isTemp": true,
	 *              "deleted": false,
	 *              "deletedDate": null,
	 *              "dPermission": {
	 *                  [
	 *                      {"id": 1, "name": "Permissao Teste"},
	 *                      {"id": 2, "name": "Permissao Teste 2"},
	 *                  ]
	 *              }
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

			/**
			 * @var $entity \Business\Perfis\Entity\DProfiles
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if (empty($entity)) {
				throw new DUsersExceptions('Perfil inválido(a) ou não encontrado(a).', 400);
			}

			$result = $this->repository->read($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage(), 400);
		}
		return new JsonResponse($result);
	}
}