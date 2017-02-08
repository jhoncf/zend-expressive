<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 06/07/16
 * Time: 11:22
 */

namespace Business\Usuarios\Action;

use App\Util\CustomRequest;
use Business\Usuarios\Entity\DUsers;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class UsuariosRead {
	/**
	 * @var \Business\Usuarios\Entity\DUsersRepository
	 */
	private $repository;

	/**
	 * UsuariosAction constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Usuarios\Entity\DUsers');
	}

	/**
	 * @api {get} /api/Usuarios/:id Buscar Usuário
	 * @apiName UsuariosRead
	 * @apiGroup Usuarios
	 * @apiVersion 0.1.0
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *            {
	 *             "result": [
	 *              {
	 *                  "id": 3,
	 *                  "name": "Usuario Teste 3",
	 *                  "surname": "usuario",
	 *                  "password": "teste",
	 *                  "email": "teste",
	 *                  "imageId": 12313,
	 *                  "created": {
	 *                      "date": "2016-07-07 15:53:53.000000",
	 *                      "timezone_type": 3,
	 *                      "timezone": "America/Sao_Paulo"
	 *                  },
	 *                  "modified": null,
	 *                  "activationKey": "",
	 *                  "blocked": false,
	 *                  "deleted": false,
	 *                  "deletedDate": null,
	 *                  "status": "migrated",
	 *                  "incorrectLoginAttempts": 0,
	 *                  "isTemp": false
	 *              }
	 *          ]
	 *         }
	 *
	 * @apiError UsuariosNotFound
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
			 * @var $entity DUsers
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
		return new JsonResponse([
			"result" => $this->__output($result),
		]);
	}

	private function __output($result) {
		$output = [
			'id' => $result['id'],
			'email' => $result['email'],
			'name' => $result['name'],
			'surname' => $result['surname'],
			'fullname' => $result['name'] . ' ' . $result['surname'],
			'blocked' => $result['blocked'],
			'created' => $result['created'],
            'telefone' => $result['telefone'],
			'modified' => $result['modified'],
			'dCompanies' => isset($result['dCompanies'][0]) ? $result['dCompanies'][0] : '',
			'dNewsletter' => $result['dNewsletter'],
			'dProfile' => $result['dProfile']
		];
		return $output;
	}
}