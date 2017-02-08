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
use Business\Produtos\Entity\DProducts;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class FeaturesUpdate
 * @package Business\Features\Action
 */
class FeaturesUpdate {
	/**
	 * @var \Business\Features\Entity\DFeaturesRepository
	 */
	private $repository;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * FeaturesUpdate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
		$this->repository = $em->getRepository('Business\Features\Entity\DFeatures');
	}

	/**
	 * @api {put} /api/Features/:id Editar Feature
	 * @apiName FeaturesUpdate
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
	 *          "detail": "Não foi possível Editar a Feature",
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

			$product = $this->em->getRepository('Business\Produtos\Entity\DProducts')->find($param['dProductId']);

			if(empty($product)){
				throw new DUsersExceptions('Produto inválido ou não encontrado.');
			}

			/**
			 * @var $entity DFeatures
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			$entity->setDProduct($product);

			if(empty($entity)){
				throw new DUsersExceptions('Feature inválido ou não encontrado.');
			}

			$entity = EntityHelper::setOptions($param, $entity);
			$result = $this->repository->update($entity);

		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['result' => $result, 'message' => 'success']);
	}
}