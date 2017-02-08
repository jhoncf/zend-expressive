<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:49
 */

namespace Business\Planos\Action;

use App\Helper\EntityHelper;
use App\Util\CustomRequest;
use Business\Planos\Entity\DPlans;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class PlanosCreate
 * @package Business\Planos\Action
 */
class PlanosCreate {

	/**
	 * @var \Business\Planos\Entity\DPlansRepository
	 */
	private $repository;

	/**
	 * @var
	 */
	private $em;

	/**
	 * @api {post} /api/Planos/:id Adicionar Plano
	 * @apiName PlanosCreate
	 * @apiGroup Planos
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} name
	 * @apiParam {Number} order
	 * @apiParam {Boolean} is_temp
	 * @apiParam {Object} dProduct - {'dProduct': ['id': '1']
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result": ["id": 4 ],
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
	 * PlanosCreate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
		$this->repository = $em->getRepository('Business\Planos\Entity\DPlans');
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
			$product = $this->em->getRepository('Business\Produtos\Entity\DProducts')->find($param['dProduct']['id']);

			unset($param['dProduct']);
			$entity = new DPlans();
			$entity = EntityHelper::setOptions($param, $entity);

			$entity->setCreated(new \DateTime("now"));
			$entity->setDProduct($product);

			$result = $this->repository->save($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['result' => ['id' => $result], 'message' => 'success']);
	}
}