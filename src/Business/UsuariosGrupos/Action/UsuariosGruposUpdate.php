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
 * Class UsuariosGruposUpdate
 * @package Business\UsuariosGrupos\Action
 */
class UsuariosGruposUpdate {
	/**
	 * @var \Business\UsuariosGrupos\Entity\DUserGroupsRepository
	 */
	private $repository;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * UsuariosGruposUpdate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
		$this->repository = $em->getRepository('Business\UsuariosGrupos\Entity\DUserGroups');
	}

	/**
	 * @api {put} /api/UsuariosGrupos/:id Editar Grupo Usuário
	 * @apiName UsuariosGruposUpdate
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
	 *          "result": {
	 *              "id": 5
	 *          },
	 *          "message": "success"
	 *     }:
	 *
	 * @apiError CompanyNotInserted
	 *
	 * @apiErrorExample Error-Response
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Não foi possível Editar a empresa",
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
			$param = $request->getContent();

			/**
			 * @var $entity DUserGroups
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if(empty($entity)){
				throw new DUsersExceptions('Usuário inválido ou não encontrado.');
			}


			$entity = EntityHelper::setOptions($param, $entity);
			$result = $this->repository->update($entity);

		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['result' => $result, 'message' => 'success']);
	}
}