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
 * Class PlanosUpdate
 * @package Business\Planos\Action
 */
class PlanosUpdate {
	/**
	 * @var \Business\Planos\Entity\DPlansRepository
	 */
	private $repository;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * PlanosUpdate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
		$this->repository = $em->getRepository('Business\Planos\Entity\DPlans');
	}

	/**
	 * @api {put} /api/Planos/:id Editar Plano
	 * @apiName PlanosUpdate
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
	 *          "result": {
	 *              "id": 4
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

			$product = $this->em->getRepository('Business\Produtos\Entity\DProducts')->find($param['dProduct']['id']);
			unset($param['dProduct']);

			/**
			 * @var $entity DPlans
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if(empty($entity)){
				throw new DUsersExceptions('Plano inválido ou não encontrado.');
			}

			$entity->setDProduct($product);

			$entity = EntityHelper::setOptions($param, $entity);
			$result = $this->repository->update($entity);

		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['result' => $result, 'message' => 'success']);
	}
}