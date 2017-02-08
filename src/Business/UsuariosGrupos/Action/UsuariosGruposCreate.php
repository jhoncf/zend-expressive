<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:49
 */

namespace Business\UsuariosGrupos\Action;

use App\Helper\EntityHelper;
use App\Util\CustomRequest;
use Business\UsuariosGrupos\Entity\DUserGroups;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class UsuariosGruposCreate
 * @package Business\UsuariosGrupos\Action
 */
class UsuariosGruposCreate {

	/**
	 * @var \Business\UsuariosGrupos\Entity\DUserGroupsRepository
	 */
	private $repository;

	/**
	 * @var
	 */
	private $em;

	/**
	 * @api {post} /api/UsuariosGrupos Adicionar Grupo de Usuário
	 * @apiName UsuariosGruposCreate
	 * @apiGroup UsuariosGrupos
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} name
	 * @apiParam {Number} order
	 * @apiParam {Number} dCompanyId
	 *
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "name": "Grupo Teste",
	 *      "dCompanyId": 1,
	 *      "order": 0
	 * }
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result": ["id": 2 ],
	 *      "message": "success"
	 *     }:
	 *
	 * @apiError UsuariosGruposInserted
	 *
	 * @apiErrorExample Error-Response
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Não foi possível cadastrar o grupo de usuário",
	 *          "links": {
	 *              "related": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html"
	 *          }
	 *     }
	 */

	/**
	 * UsuariosGruposCreate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
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
			$param = $request->getContent();

			$entity = new DUserGroups();
			$entity = EntityHelper::setOptions($param, $entity);

			$result = $this->repository->save($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['result' => ['id' => $result], 'message' => 'success']);
	}
}