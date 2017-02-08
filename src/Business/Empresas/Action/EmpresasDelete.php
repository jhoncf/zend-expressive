<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 15:07
 */

namespace Business\Empresas\Action;


use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * Class EmpresasDelete
 * @package Business\Empresas\Action
 */
class EmpresasDelete {
	/**
	 * @var \Business\Empresas\Entity\DCompaniesRepository
	 */
	private $repository;

	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Empresas\Entity\DCompanies');
	}

	/**
	 * @api {delete} /api/Empresas/:id Remove empresa
	 * @apiName EmpresasDelete
	 * @apiGroup Empresas
	 * @apiVersion 0.1.0
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "message": "success"
	 *     }:
	 *
	 * @apiError CompanyNotInserted The Company could not be created.
	 *
	 * @apiErrorExample Error-Response
	 *
	 *
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *        "error": {
	 *          "status": 500,
	 *          "title": "Internal Server Error",
	 *          "detail": "Não foi possível Remover a empresa",
	 *          "links": {
	 *              "related": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html"
	 *          }
	 *     }
	 */

	public function __invoke(CustomRequest $request, ResponseInterface $response, callable $next = null) {
		try {
			$id = $request->getAttribute('resourceId');

			$this->repository->delete($id);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['message' => 'success']);
	}
}