<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 23/09/16
 * Time: 13:44
 */

namespace Business\Auth\Action;


use App\Util\AuthComponents;
use App\Util\CustomRequest;
use Business\Entities\DLoginStatuses;
use Business\Entities\DLoginStatusesRepository;
use Business\Usuarios\Entity\DUsersRepository;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class LoggedAction {
	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @var
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
	 * LoginAction constructor.
	 * @param ContainerInterface $container
	 * @param $em
	 */
	public function __construct(ContainerInterface $container, EntityManager $em) {
		$this->em = $em;
		$this->container = $container;
		$this->authComponents = new AuthComponents($container);
		$this->repository = $em->getRepository('Business\Usuarios\Entity\DUsers');
	}

	/**
	 * @api {post} /api/Auth/Logged Logged
	 * @apiName Logged
	 * @apiVersion 0.1.0
	 * @apiGroup Auth App Externo
	 *
	 * @apiParam {object} SessionData
	 * @apiParam {string} email
	 * @apiParam {string} sessionKey
	 * @apiParam {string} application
	 * @apiParam {string} applicationLocalSessionKey
	 *
	 * @apiParamExample {json} Request-Example:
	 * {
	 *  "SessionData": {
	 *      "email": "jhonatas@dcide.com.br",
	 *      "sessionKey": "0ecdf0e10cc338b89c5113258237cc18955db3292c87fc17d061403e6bd4f20e",
	 *      "application": "denergia_web",
	 *      "applicationLocalSessionKey":
	 *      "U*I000ADS9ALIKAOLAKOAOK"
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
	 *      "id": "612",
	 *      "name": "Jhonatas",
	 *      "surname": "Faria",
	 *      "fullName": "Jhonatas Faria",
	 *      "email": "jhonatas@dcide.com.br",
	 *      "Company": {
	 *              "id": "7",
	 *              "name": "HLF",
	 *              "shortName": "Treinamento",
	 *              "parentId": null
	 *              },
	 *      "Products": {
	 *              "dreports": "Dreports",
	 *              "denergia_web": "Pool Denergia"
	 *              },
	 *      "Permission": [
	 *              "dweb_forward_visualizar"
	 *              },
	 *      "error": false
	 *  }
	 *
	 * @apiError UserNotFound The id of the User was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error"=>[
	 *          "code"=> 1002,
	 *          "message"=>"Login não efetuado"
	 *          ]
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
			$params = $request->getContent()['SessionData'];
			$user = $this->repository->findByEmail($params['email']);
			$sessionTimeout = $this->container->get("config")['session']['lifetime'];

			if (!$user) {
				return $this->outputError();
			}

			/**
			 * @var $dLoginStatusesRepository DLoginStatusesRepository
			 */
			$dLoginStatusesRepository = $this->em->getRepository('Business\Entities\DLoginStatuses');
			$sessionResult = $dLoginStatusesRepository->findBySessionKey($params['sessionKey']);

			if (!$sessionResult || $sessionResult['active'] == false){
				return $this->outputError();
			}

			/**
			 * Renovando a data de expiração da sessão
			 *
			 * @var $dLoginStatusesEntity DLoginStatuses
			 */
			$dLoginStatusesEntity = $dLoginStatusesRepository->find($sessionResult['id']);

			/**
			 * Valida o tempo de expiração da sessão
			 */
			$dateNow = new \DateTime();
			$lastAccessHour = $sessionResult['lastAccess']->diff($dateNow)
			                                              ->format('%h');
			$lastAccessMinute = $sessionResult['lastAccess']->diff($dateNow)
			                                                ->format('%i');

			$lastAccessSeconds = $this->timeInSeconds($lastAccessHour, $lastAccessMinute);

			if ($lastAccessSeconds > $sessionTimeout) {
				$dLoginStatusesEntity->setActive(false);
				$dLoginStatusesRepository->update($dLoginStatusesEntity);

				return $this->outputError();
			}

			/**
			 * Atualiza a data de ultimo acesso
			 */
			$dLoginStatusesEntity->setLastAccess(new \DateTime());

			$dLoginStatusesRepository->update($dLoginStatusesEntity);

		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse($this->output($user, $params));
	}

	private function timeInSeconds($hours, $minutes) {
		return $hours * 60 * 60 + $minutes;
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

	private function outputError(){
		return new JsonResponse([
			'errors' => [
				'102' => 'Não está mais logado. Ou a sessão expirou ou os dados da sessão não batem com nenhuma sessão ativa.',
			],
			'error' => true
		]);
	}
}