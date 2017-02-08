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
 * Class UsuariosUpdate
 * @package Business\Usuarios\Action
 */
class UsuariosUpdate {
	/**
	 * @var \Business\Usuarios\Entity\DUsersRepository
	 */
	private $repository;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var AuthComponents
	 */
	private $authComponents;

	/**
	 * UsuariosUpdate constructor.
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
	 * @api {put} /api/Usuarios/:id Editar Usuário
	 * @apiName UsuariosUpdate
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
	 *      "dCompanyId": 1,
	 *      "surname": "usuario",
	 *      "password": "teste",
	 *      "email": "teste@test.com",
	 *      "imageId": 12313,
	 *      "activationKey": "",
	 *      "blocked": false,
	 *      "deleted": false,
	 *      "status": "migrated",
	 *      "isTemp": false
	 *  }
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
			 * @var $entity DUsers
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if (empty($entity)) {
				throw new DUsersExceptions('Usuário inválido ou não encontrado.');
			}

			if(is_array($param['dCompany']) && !isset($param['dCompany']['id'])){
				$entity->clearDCompany();
			}
			if(isset($param['dCompany']['id'])){
				$entity->clearDCompany();

				$dCompanies = $this->em->getRepository('Business\Empresas\Entity\DCompanies')
				                       ->find($param['dCompany']['id']);

				if (empty($dCompanies)) {
					throw new DUsersExceptions('Empresa inválida ou não encontrado.');
				}
				$entity->addDCompany($dCompanies);
			}

			$entity = EntityHelper::setOptions($param, $entity);

			if (isset($param['dProfile'])) {
				/**
				 * @var $userProfilesRepository \App\Admin\Entity\UserProfilesRepository
				 */
				$dProfilesRepository = $this->em->getRepository('Business\Perfis\Entity\DProfiles');
				$entity->clearDProfile();
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
				$entity->clearDNewsletter();
				foreach ($param['dNewsletter'] as $key => $value) {
					/**
					 * @var $dProfilesEntity \Business\Newsletter\Entity\DNewsletters
					 */
					$dNewsletterEntity = $dNewsletterRepository->find($value['id']);
					$entity->addDNewsletter($dNewsletterEntity);
				}
			}

			if($entity->getBlocked() == true){
				/**
				 * @var $dLoginStatusRepository \Business\Entities\DLoginStatusesRepository
				 */
				$dLoginStatusRepository = $this->em->getRepository('Business\Entities\DLoginStatuses');

				$dLoginStatusRepository->kickSessionByUserId($entity->getId());
			}
			$result = $this->repository->update($entity);

		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse([
			'result' => $result,
			'message' => 'success'
		]);
	}
}