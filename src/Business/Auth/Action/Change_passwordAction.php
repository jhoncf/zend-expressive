<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 29/09/16
 * Time: 17:33
 */

namespace Business\Auth\Action;


use App\Util\AuthComponents;
use App\Util\CustomRequest;
use Business\Usuarios\Entity\DUsers;
use Business\Usuarios\Entity\DUsersRepository;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class Change_passwordAction
 * @package Business\Auth\Action
 */
class Change_passwordAction {

	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var AuthComponents
	 */
	private $authComponents;

	/**
	 * @var DUsersRepository
	 */
	private $repository;

	/**
	 * @api {post} /api/Auth/Change_password ChangePassword
	 * @apiName ChangePassword
	 * @apiVersion 0.1.0
	 * @apiGroup Auth App Externo
	 *
	 * @apiParam {object} SessionData
	 * @apiParam {string} email
	 * @apiParam {string} sessionKey
	 * @apiParam {string} newPassword
	 * @apiParam {string} oldPassword
	 * @apiParam {string} application
	 * @apiParam {string} applicationLocalSessionKey
	 *
	 * @apiParamExample {json} Request-Example:
	 * {
	 *  "SessionData": {
	 *      "email": "teste@email.com",
	 *      "sessionKey": "0ecdf0e10cc338b89c5113258237cc18955db3292c87fc17d061403e6bd4f20e",
	 *      "newPassword": "senhaNova",
	 *      "oldPassword": "senhaAntiga",
	 *      "application": "aplicação",
	 *      "applicationLocalSessionKey": "applicationLocalSessionKey"
	 *     }
	 * }
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *    {
	 *      "sessionKey": "0ecdf0e10cc338b89c5113258237cc18955db3292c87fc17d061403e6bd4f20e",
	 *      "loginStatus": "logged",
	 *      "User": {
	 *          "id": "612",
	 *          "name": "Usuario",
	 *          "surname": "Teste",
	 *          "fullName": "Usuario Teste",
	 *          "email": "teste@dcide.com.br",
	 *          "Company": {
	 *                  "id": "7",
	 *                  "name": "Empresa Teste",
	 *                  "shortName": "Treinamento",
	 *                  "parentId": null
	 *              },
	 *          "Products": {
	 *                  "dreports": "Dreports",
	 *                  "denergia_web": "Pool Denergia"
	 *              },
	 *          "Permission": [
	 *                  "dweb_forward_visualizar"
	 *              },
	 *      "error": false
	 *  }
	 *
	 * @apiError UserNotFound The id of the User was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error"=> true
	 *     }
	 */

	/**
	 * Change_passwordAction constructor.
	 * @param ContainerInterface $container
	 * @param EntityManager $em
	 */
	public function __construct(ContainerInterface $container, EntityManager $em) {
		$this->em = $em;
		$this->container = $container;
		$this->authComponents = new AuthComponents($container);
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
			$params = $request->getContent()['SessionData'];

			$resultUser = $this->repository->findByEmail($params['email']);

			/**
			 * @var $userEntity DUsers
			 */
			$userEntity = $this->repository->find($resultUser['id']);

			$authHandle = new AuthComponents($this->container);
			$oldPasswordEncrypted = $authHandle->hash($params['oldPassword']);

			if ($userEntity->getPassword() != $oldPasswordEncrypted) {
				return new JsonResponse([
					'error' => false
				]);
			}

			$newEncryptedPassword = $authHandle->hash($params['newPassword']);
			$userEntity->setPassword($newEncryptedPassword);
			$this->repository->update($userEntity);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse($this->output($resultUser, $params));
	}

	/**
	 * @param $userData
	 * @param $sessionData
	 * @return array
	 */
	private function output($userData, $sessionData) {
		$output = [
			'sessionKey' => $sessionData['sessionKey'],
			'loginStatus' => 'logged',
			'User' => [
				'id' => $userData['id'],
				'name' => $userData['name'],
				'surname' => $userData['surname'],
				'fullName' => $userData['name'] . ' ' . $userData['surname'],
				'email' => $userData['email'],
				'Company' => [],
				'Products' => [],
				'Permission' => []
			],
			'error' => false
		];

		/**
		 * Verifica se o usuário está vinculado à uma empresa
		 */

		if (isset($userData['dCompanies'][0])) {
			$output['User']['Company'] = [
				'id' => $userData['dCompanies'][0]['id'],
				'name' => $userData['dCompanies'][0]['name'],
				'shortName' => $userData['dCompanies'][0]['shortName'],
				'parentId' => isset($userData['dCompanies'][0]['parentId']) ? $userData['dCompanies'][0]['parentId'] : null,
			];

			/**
			 * Extrai as informações de produtos disponíveis para o usuario
			 */
			foreach ($userData['dCompanies'][0]['dPlan'] as $key => $value) {
				$output['User']['Products'][$value['dProduct']['slug']] = $value['dProduct']['name'];
			}
		}

		/**
		 * Extrai as informações de permissão do usuário no formato final
		 */
		foreach ($userData['dProfile'] as $dProfileKey => $dProfileValue) {
			foreach ($dProfileValue['dPermission'] as $dPermissionKey => $dPermissionValue) {
				$output['User']['Permission'][] = $dPermissionValue['slug'];
			}
		}

		$output['User']['Permission'] = array_values(array_unique($output['User']['Permission']));

		return $output;
	}

}