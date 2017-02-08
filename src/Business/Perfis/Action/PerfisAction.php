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
class PerfisAction {
	/**
	 * @var \Business\Perfis\Entity\DProfilesRepository
	 */
	private $repository;

	/**
	 * @api {get} /api/Perfis Todas os Perfis
	 * @apiName PerfisAction
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
	 *      ],
	 *      "count": 1
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
	 * PerfisAction constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Perfis\Entity\DProfiles');
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