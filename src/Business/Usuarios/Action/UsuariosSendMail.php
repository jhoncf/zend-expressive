<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 21/09/16
 * Time: 17:53
 */

namespace Business\Usuarios\Action;


use App\Util\CustomRequest;
use App\Util\SMTPMailer;
use Business\Usuarios\Entity\DUsers;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class UsuariosSendMail {

	/**
	 * @var \Business\Usuarios\Entity\DUsersRepository
	 */
	private $repository;

	/**
	 * @var $config
	 */
	private $config;
	/**
	 * @api {post} /api/Usuarios/SendMail Enviar email de nova senha
	 * @apiName UsuariosSendMail
	 * @apiGroup Usuarios
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {int} id
	 *
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "id": "123"
	 * }
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "error": false,
	 *      "message": "Messagem enviada."
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
	 * UsuariosAction constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Usuarios\Entity\DUsers');
		$this->container = require 'config/container.php';
		$this->config = $this->container->get("config")['Apps'];
	}

	public function __invoke(CustomRequest $request, ResponseInterface $response, callable $next = null) {
		try {
			$param = $request->getContent();

			/**
			 * @var $entityUser DUsers
			 */
			$entityUser = $this->repository->find($param['id']);
			$twig = new \Twig_Environment(new \Twig_Loader_Filesystem('templates/mail'), array(
				'cache' => 'data/cache/compilation_cache'
			));

			$activationKey = uniqid();

			$body = $twig->render('new_password.html', array(
				'activationKey' => $activationKey,
				'url' => $this->config['PoolDenergiaUrl']
			));

			$mail = new SMTPMailer();

			if (!$mail->sendMail($entityUser->getEmail(), $entityUser->getName(), 'Novo usuário - Dcide', $body)) {
				return new JsonResponse([
					'message' => 'Messagem não enviada.',
					'error' => true
				]);
			}

			$entityUser->setActivationKey($activationKey);
			$this->repository->update($entityUser);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage(), 400);
		}

		return new JsonResponse([
			'message' => 'Messagem enviada.',
			'error' => false
		]);
	}
}