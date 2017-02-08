<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:44
 */

namespace Business\Usuarios\Action;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class UsuariosAction
 * @package Business\Usuarios\Action
 */
class UsuariosAction {
	/**
	 * @var \Business\Usuarios\Entity\DUsersRepository
	 */
	private $repository;

	/**
	 * @api {get} /api/Usuarios Todos os Usuarios
	 * @apiName UsuariosAction
	 * @apiGroup Usuarios
	 * @apiVersion 0.1.0
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result": [
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
	 *      ],
	 *      "count": 1
	 *   }
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
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Usuarios\Entity\DUsers');
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
			$result = $this->repository->findAll();
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}
		return new JsonResponse([
			"result" => $this->__output($result['result']),
			"count" => count($result['result'])
		]);
	}

	private function __output($result) {
		$output = [];

		foreach ($result as $key => $value) {
			$output[] = [
				'id' => $value['id'],
				'email' => $value['email'],
				'name' => $value['name'],
				'surname' => $value['surname'],
				'fullname' => $value['name'] . ' ' . $value['surname'],
				'blocked' => $value['blocked'],
                'telefone' => $value['telefone'],
                'created' => $value['created'],
				'modified' => $value['modified'],
				'dCompanies' => isset($value['dCompanies'][0]) ? $value['dCompanies'][0] : '',
				'dNewsletter' => $value['dNewsletter']
			];
		}
		return $output;
	}
}