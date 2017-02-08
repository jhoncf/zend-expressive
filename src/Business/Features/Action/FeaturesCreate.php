<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:49
 */

namespace Business\Features\Action;

use App\Helper\EntityHelper;
use App\Util\CustomRequest;
use Business\Features\Entity\DFeatures;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class FeaturesCreate
 * @package Business\Features\Action
 */
class FeaturesCreate {

	/**
	 * @var \Business\Features\Entity\DFeaturesRepository
	 */
	private $repository;

	/**
	 * @var
	 */
	private $em;

	/**
	 * @api {post} /api/Features/:id Adicionar Feature
	 * @apiName FeaturesCreate
	 * @apiGroup Features
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} name
	 * @apiParam {String="text","boolean"} type
	 * @apiParam {Number} dProductId
	 * @apiParam {Number} order
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
	 * @apiError FeatureNotInserted The Company could not be created.
	 *
	 * @apiErrorExample Error-Response
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Não foi possível cadastrar a Feature",
	 *          "links": {
	 *              "related": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html"
	 *          }
	 *     }
	 */

	/**
	 * FeaturesCreate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
		$this->repository = $em->getRepository('Business\Features\Entity\DFeatures');
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

			$entity = new DFeatures();
			$product = $this->em->getRepository('Business\Produtos\Entity\DProducts')->find($param['dProductId']);

			$entity->setDProduct($product);
			$entity = EntityHelper::setOptions($param, $entity);

			$companies = $this->repository->save($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['result' => ['id' => $companies], 'message' => 'success']);
	}
}