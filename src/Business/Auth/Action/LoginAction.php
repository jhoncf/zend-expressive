<?php
/**
 * Created by PhpStorm.
 * User: Dcide
 * Date: 12/05/2016
 * Time: 16:08
 */

namespace Business\Auth\Action;

use App\Util\AuthComponents;
use App\Util\CustomRequest;
use App\Util\SMTPMailer;
use Business\Entities\DLoginStatuses;
use Business\Usuarios\Entity\DUsers;
use Business\Usuarios\Entity\DUsersRepository;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class LoginAction {

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

	private $sessionKey;

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
	 * @api {post} /api/Auth/Login Login
	 * @apiName Login
	 * @apiVersion 0.1.0
	 * @apiGroup Auth App Externo
	 *
	 * @apiParam {string} email userName of the entry User.
	 * @apiParam {string} password Password of the entry User.
	 * @apiParam {Object} SessionData
	 *
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "email":"teste@email.com",
	 *      "password":"senhaTeste",
	 *      "SessionData": {
	 *          "application":"denergia_web",
	 *          "applicationLocalSessionKey":"U*I000ADS9ALIKAOLAKOAOK"
	 *      }
	 * }
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *              "sessionKey": "2e961d8f310c701109f00b960c1cd66a4a1c7d3e990fae0ca0c1b94719273256",
	 *              "loginStatus": "logged",
	 *              "User": {
	 *                  "id": "612",
	 *                  "name": "Teste",
	 *                  "surname": "TesteSobrenome",
	 *                  "fullName": "Teste Testesobrenome",
	 *                  "email": "teste@email.com",
	 *                  "Company": {
	 *                      "id": "7",
	 *                      "name": "Empresa Teste",
	 *                      "shortName": "Teste",
	 *                      "parentId": null
	 *                  },
	 *                  "Products": {
	 *                      "dreports": "Dreports",
	 *                      "denergia_web": "Pool Denergia"
	 *                  },
	 *                  "Permission": [
	 *                     "dweb_forward_visualizar",
	 *                     "dweb_climate"
	 *                  ]
	 *              },
	 *              "error": false
	 *     }
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
			$params = $request->getContent();

			/**
			 * @var $user DUsers
			 */
			$user = $this->repository->findByEmail($params['email']);

			if ($user === false) {
				return new JsonResponse([
					'errors' => [
						'101' => 'Nome de usuário ou senha inválidos.'
					],
					'error' => true
				]);
			}

			/**
			 * Verifica se usuário está bloqueado
			 */
			if ($user['blocked']) {
				return new JsonResponse([
					'errors' => [
						'101' => 'Usuário bloqueado.',
						'108' => 'Usuário bloqueado.'
					],
					'error' => true
				]);
			}

			/**
			 * @var $userEntity \Business\Usuarios\Entity\DUsers
			 */
			$userEntity = $this->repository->find($user['id']);

			/**
			 * Valida senha
			 */
			if ($this->authComponents->validPassword($params['password'], $user['password']) === false) {

				/**
				 * soma as tentativas de login
				 */
				$userEntity->setIncorrectLoginAttempts($userEntity->getIncorrectLoginAttempts() + 1);

				/**
				 * se for mais de 3 tentativas bloqueia a conta
				 */
				if (($userEntity->getIncorrectLoginAttempts() + 1) > 3) {
					$userEntity->setBlocked(true);
					$twig = new \Twig_Environment(new \Twig_Loader_Filesystem('templates/mail'), array(
						'cache' => 'data/cache/compilation_cache'
					));
					$body = $twig->render('blocked_user.html', array('toName' => $userEntity->getName()));

					$mail = new SMTPMailer();
					$mail->sendMail($userEntity->getEmail(), $userEntity->getName(), 'Bloqueio de usuário - Dcide', $body, true);
				}

				/**
				 * Salva o status bloqueado do usuario
				 */
				$this->repository->update($userEntity);

				return new JsonResponse([
					'errors' => [
						'101' => 'Nome de usuário ou senha inválidos.'
					],
					'error' => true
				]);
			}

			/**
			 * Redefine o número de tentativas de login
			 */
			$userEntity->setIncorrectLoginAttempts(0);
			$this->repository->update($userEntity);

			$this->createSessionKey();
			$this->createLoginSessionStatus($userEntity);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse($this->output($user));
	}

	/**
	 * @param DUsers $userEntity
	 * @return DLoginStatuses
	 */
	private function createLoginSessionStatus(DUsers $userEntity) {
		/**
		 * @var $dLoginStatusesRepository \Business\Entities\DLoginStatusesRepository
		 */
		$dLoginStatusesRepository = $this->em->getRepository('Business\Entities\DLoginStatuses');

		$remoteIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$dLoginStatuses = new DLoginStatuses();
		$dLoginStatuses->setDUser($userEntity);
		$dLoginStatuses->setSessionKey($this->createSessionKey());
		$dLoginStatuses->setStarted(new \DateTime);
		$dLoginStatuses->setLastAccess(new \DateTime);
		$dLoginStatuses->setActive(true);
		$dLoginStatuses->setIp($remoteIp);
		$dLoginStatuses->setClientIp($remoteIp);

		/**
		 * Criando a sessão
		 */
		return $dLoginStatusesRepository->save($dLoginStatuses);
	}

	private function createSessionKey() {
		return $this->sessionKey = hash('sha256', rand());
	}

	private function output($userData) {
		$output = [
			'sessionKey' => $this->sessionKey,
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