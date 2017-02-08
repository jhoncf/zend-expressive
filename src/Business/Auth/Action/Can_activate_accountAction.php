<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 30/09/16
 * Time: 14:43
 */

namespace Business\Auth\Action;

use App\Util\AuthComponents;
use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;


/**
 * Class Can_activate_accountAction
 * @package Business\Auth\Action
 */
class Can_activate_accountAction {

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
	 * @var \Business\Usuarios\Entity\DUsersRepository
	 */
	private $repository;

	/**
	 * Can_activate_accountAction constructor.
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
			$params = $request->getContent();
			$resultUser = $this->repository->findByActivationKey($params['activation_key']);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['DUser' => ['id' => $resultUser['id']]]);
	}
}