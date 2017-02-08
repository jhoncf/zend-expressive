<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:44
 */

namespace Business\UsuariosGrupos\Action;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class UsuariosGruposAction
 * @package Business\UsuariosGrupos\Action
 */
class UsuariosGruposAction {
	/**
	 * @var \Business\UsuariosGrupos\Entity\DUserGroupsRepository
	 */
	private $repository;

	/**
	 * @api {get} /api/UsuariosGrupos Todos os Grupos de Usuário
	 * @apiName UsuariosGruposAction
	 * @apiGroup UsuariosGrupos
	 * @apiVersion 0.1.0
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result": [
	 *              {
	 *                      "id": 1,
	 *                      "name": "Grupo Teste",
	 *                      "order": "0",
	 *                      "dCompanyId": 1
	 *              }
	 *          ],
	 *      "count": 1
	 *   }
	 *
	 * @apiError UsuariosGruposNotFound The UsuariosGrupos could not be listed.
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
	 * UsuariosGruposAction constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\UsuariosGrupos\Entity\DUserGroups');
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
			$result = $this->repository->findAll($page, $limit);

			if(empty($result)){
				throw new DUsersExceptions('Nenhum dado encontrado.');
			}
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}
		return new JsonResponse($result);
	}
}