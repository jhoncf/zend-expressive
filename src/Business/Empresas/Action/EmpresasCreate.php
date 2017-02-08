<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 29/06/16
 * Time: 16:37
 */

namespace Business\Empresas\Action;

use App\Helper\EntityHelper;
use App\Util\CustomRequest;
use App\Util\FileUploader;
use Business\Empresas\Entity\DCompanies;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class EmpresasCreate {

	/**
	 * @var \Business\Empresas\Entity\DCompaniesRepository
	 */
	private $repository;

	private $fileUploader;

	public function __construct(EntityManager $em) {
		$this->repository = $em->getRepository('Business\Empresas\Entity\DCompanies');
		$this->fileUploader = new FileUploader();
	}

	/**
	 * @api {post} /api/Empresas Adicionar empresa
	 * @apiName EmpresasCreate
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
	 *      "result": ["id": 4 ],
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
	 *          "detail": "Não foi possível cadastrar a empresa",
	 *          "links": {
	 *              "related": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html"
	 *          }
	 *     }
	 */

	public function __invoke(CustomRequest $request, ResponseInterface $response, callable $next = null) {
		try {
			$param = $request->getContent();
			$company = new DCompanies();

			if (isset($param['file'])) {
				$file = $this->fileUploader->base64toImage($param['file']['fileEncoded'], $param['file']['fileType']);
				if (!$file) {
					return new JsonResponse([
						'error' => 'true',
						'message' => 'Erro ao salvar a imagem.'
					]);
				}
				$company->setImageUrl($file);
			}

			$company = EntityHelper::setOptions($param, $company);

			$companies = $this->repository->save($company);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse([
			'result' => ['id' => $companies],
			'message' => 'success'
		]);
	}
}