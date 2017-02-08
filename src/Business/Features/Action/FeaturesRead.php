<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 06/07/16
 * Time: 11:22
 */

namespace Business\Features\Action;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class FeaturesRead {
	/**
	 * @var \Business\Features\Entity\DFeaturesRepository
	 */
	private $repository;

	/**
	 * FeaturesAction constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Features\Entity\DFeatures');
	}

	/**
	 * @api {get} /api/Features/:id Buscar Feature
	 * @apiName FeaturesRead
	 * @apiGroup Features
	 * @apiVersion 0.1.0
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result": [
	 *              "id": 2,
	 *              "name": "Teste",
	 *              "type": "text",
	 *              "order": 0
	 *      ]
	 *   }
	 *
	 * @apiError FeatureNotFound The Feature could not be listed.
	 *
	 * @apiErrorExample Error-Response
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Erro",
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

			/**
			 * @var $entity \Business\Features\Entity\DFeatures
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if(empty($entity)){
				throw new DUsersExceptions('Permissão inválida ou não encontrado.', 400);
			}

			$result = $this->repository->read($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage(), 400);
		}
		return new JsonResponse($result);
	}
}