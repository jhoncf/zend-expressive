<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:49
 */

namespace Business\Usuarios\Action;

use App\Helper\EntityHelper;
use App\Util\AuthComponents;
use App\Util\CustomRequest;
use Business\Usuarios\Entity\DUsers;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class UsuariosCreate
 * @package Business\Usuarios\Action
 */
class UsuariosCreate {

	/**
	 * @var \Business\Usuarios\Entity\DUsersRepository
	 */
	private $repository;

	/**
	 * @var
	 */
	private $em;

	/**
	 * @var AuthComponents
	 */
	private $authComponents;

	/**
	 * @var
	 */
	private $container;

	/**
	 * @api {post} /api/Usuarios Adicionar Usuário
	 * @apiName UsuariosCreate
	 * @apiGroup Usuarios
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} name
	 * @apiParam {String} surname
	 * @apiParam {String} password
	 * @apiParam {String} email
	 * @apiParam {Number} imageId
	 * @apiParam {String} activationKey
	 * @apiParam {Boolean} blocked
	 * @apiParam {Boolean} deleted
	 * @apiParam {Boolean} status
	 * @apiParam {Boolean} isTemp
	 *
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "name": "Usuario Teste",
	 *      "dCompany": {"id": 1},
	 *      "surname": "usuario",
	 *      "password": "teste",
	 *      "email": "teste@test.com",
	 *      "imageId": 12313,
	 *      "activationKey": "",
	 *      "blocked": false,
	 *      "deleted": false,
	 *      "status": "migrated",
	 *      "isTemp": false
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
	 * @apiError PlanoNotInserted
	 *
	 * @apiErrorExample Error-Response
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Não foi possível cadastrar a empresa",
	 *          "links": {
	 *              "related": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html"
	 *          }
	 *     }
	 */

	/**
	 * UsuariosCreate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em, $container = null) {
		$this->em = $em;
		$this->repository = $em->getRepository('Business\Usuarios\Entity\DUsers');

		if ($container != null) {
			$this->container = $container;
		} else {
			$this->container = require 'config/container.php';
		}

		$this->authComponents = new AuthComponents($this->container);
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

			if ($this->repository->findByEmail($param['email'])) {
				return new JsonResponse([
					'message' => 'Email já cadastrado.',
					'error' => true
				]);
			}

			$entity = new DUsers();

			if (isset($param['dCompany']) && $param['dCompany']['id'] != null) {
				$dCompanies = $this->em->getRepository('Business\Empresas\Entity\DCompanies')
				                       ->find($param['dCompany']['id']);

				$entity->addDCompany($dCompanies);
			}

			$entity->setCreated(new \DateTime("now"));
			/**
			 * @var $entity DUsers
			 */
			$entity = EntityHelper::setOptions($param, $entity);

			if (isset($param['dProfile'])) {
				/**
				 * @var $userProfilesRepository \Business\Perfis\Entity\DProfilesRepository
				 */
				$dProfilesRepository = $this->em->getRepository('Business\Perfis\Entity\DProfiles');
				foreach ($param['dProfile'] as $key => $value) {
					/**
					 * @var $dProfilesEntity \Business\Perfis\Entity\DProfiles
					 */
					$dProfilesEntity = $dProfilesRepository->find($value['id']);
					$entity->addDProfile($dProfilesEntity);
				}
			}

			if (isset($param['dNewsletter'])) {
				/**
				 * @var $userProfilesRepository \Business\Newsletter\Entity\DNewslettersRepository
				 */
				$dNewsletterRepository = $this->em->getRepository('Business\Newsletter\Entity\DNewsletters');
				foreach ($param['dNewsletter'] as $key => $value) {
					/**
					 * @var $dProfilesEntity \Business\Newsletter\Entity\DNewsletters
					 */
					$dNewsletterEntity = $dNewsletterRepository->find($value['id']);
					$entity->addDNewsletter($dNewsletterEntity);
				}
			}

			$result = $this->repository->save($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse([
			'result' => ['id' => $result],
			'message' => 'success'
		]);
	}
}