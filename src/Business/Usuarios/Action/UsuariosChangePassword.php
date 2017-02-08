<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 30/09/16
 * Time: 10:15
 */

namespace Business\Usuarios\Action;

use App\Util\CustomRequest;
use App\Util\SMTPMailer;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class UsuariosChangePassword
 * @package Business\Usuarios\Action
 */
class UsuariosChangePassword {

	/**
	 * @var \Business\Usuarios\Entity\DUsersRepository
	 */
	private $repository;

	/**
	 * @var mixed
	 */
	private $container;

	/**
	 * @var
	 */
	private $config;

	/**
	 * UsuariosChangePassword constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Usuarios\Entity\DUsers');
		$this->container = require 'config/container.php';

		$this->config = $this->container->get("config")['Apps'];

	}

	/**
	 * @api {post} /api/Usuarios Enviar troca de senha
	 * @apiName UsuariosChangePassword
	 * @apiGroup Usuarios
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} id
	 *
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "id": 123
	 * }
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *   {
	 *      "message": "Alteração de senha enviada.",
	 *      "error": false
	 *   }
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
			 * @var $entity \Business\Usuarios\Entity\DUsers
			 */
			$entity = $this->repository->find($param['id']);


			if (empty($entity)) {
				return new JsonResponse([
					'message' => 'Usuário inválido ou não encontrado.',
					'error' => true
				]);
			}

			$activationKey = uniqid();

			$entity->setPassword('Aa1' . uniqid());
			$entity->setActivationKey($activationKey);
			$this->repository->update($entity);

			$twig = new \Twig_Environment(new \Twig_Loader_Filesystem('templates/mail'), array(
				'cache' => 'data/cache/compilation_cache'
			));
			$body = $twig->render('change_password.html', ['activationKey' => $activationKey, 'url'=> $this->config['PoolDenergiaUrl']]);

			$mail = new SMTPMailer();

			if (!$mail->sendMail($entity->getEmail(), $entity->getName(), 'Mudança de senha - Dcide', $body)) {
				return new JsonResponse([
					'message' => 'Messagem não foi enviada.',
					'error' => false
				]);
			}
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage(), 400);
		}

		return new JsonResponse([
			'message' => 'Alteração de senha enviada.',
			'error' => false
		]);
	}
}