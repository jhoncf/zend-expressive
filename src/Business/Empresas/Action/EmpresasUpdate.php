<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 01/07/16
 * Time: 10:44
 */

namespace Business\Empresas\Action;

use App\Util\CustomRequest;
use App\Util\FileUploader;
use Business\Empresas\Entity\DCompanies;
use Doctrine\ORM\EntityManager;
use Zend\Diactoros\Response\JsonResponse;
use App\Helper\EntityHelper;
use Psr\Http\Message\ResponseInterface;
;

class EmpresasUpdate {
	/**
	 * @var \Business\Empresas\Entity\DCompaniesRepository
	 */
	private $repository;

	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Empresas\Entity\DCompanies');
		$this->fileUploader = new FileUploader();
	}

	/**
	 * @api {put} /api/Empresas/:id Editar empresa
	 * @apiName EmpresasUpdate
	 * @apiGroup Empresas
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} name
	 * @apiParam {String} shortName
	 * @apiParam {String} imageUrl - Default: null - Ex: "uploads/images/5798bb4f05d79.jpeg"
	 * @apiParam {String} domain
	 * @apiParam {String} file[fileEncoded] - Default: null - Defina para novas imagens - Ex: Imagem no formato base64
	 * @apiParam {String} file[fileType] - Default: null - Defina para novas imagens - Ex: (image/jpeg, image/png)
	 *
	 * @apiParamExample {json} Request-Example:
	 *     {
	 *      "name": "Teste",
	 *      "shortName": "test",
	 *      "imageUrl": null,
	 *      "domain": "teste.com"
	 *      "file": {
	 *          "fileEncoded": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD..."
	 *          "fileType": "image/jpeg"
	 *       }
	 *     }
	 *
	 * @apiSuccess {String} json New JsonResponse.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *      "result": {"id": 4 },
	 *      "message": "success"
	 *     }:
	 *
	 * @apiError CompanyNotInserted The Company could not be created.
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
			/**
			 * @var $company DCompanies
			 */
			$company = $this->repository->find($request->getAttribute('resourceId'));
			$param = $request->getContent();
			$company = EntityHelper::setOptions($param, $company);

			if (isset($param['file'])) {
				$fileName = $this->fileUploader->base64toImage($param['file']['fileEncoded'], $param['file']['fileType']);
				if (!$fileName) {
					return new JsonResponse([
						'error' => 'true',
						'message' => 'Erro ao salvar a imagem.'
					]);
				}
				if ($company->getImageUrl()) {
					$this->fileUploader->deleteFile($company->getImageUrl());
				}

				$company->setImageUrl($fileName);
			}

			$param['id'] = $this->repository->update($company)->getId();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse([
			'result' => $param,
			'message' => 'success'
		]);
	}
}