<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 16:49
 */

namespace Business\Produtos\Action;

use App\Helper\EntityHelper;
use App\Util\CustomRequest;
use App\Util\FileUploader;
use Business\Produtos\Entity\DProducts;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class ProdutosUpdate
 * @package Business\Produtos\Action
 */
class ProdutosUpdate {
	/**
	 * @var \Business\Produtos\Entity\DProductsRepository
	 */
	private $repository;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var FileUploader
	 */
	private $fileUploader;
	/**
	 * ProdutosUpdate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
		$this->fileUploader = new FileUploader();
		$this->repository = $em->getRepository('Business\Produtos\Entity\DProducts');
	}

	/**
	 * @api {put} /api/Produtos/:id Editar Produto
	 * @apiName ProdutosUpdate
	 * @apiGroup Produtos
	 * @apiVersion 0.1.0
	 *
	 * @apiParam {String} name
	 * @apiParam {String} url
	 * @apiParam {String} slug
	 * @apiParam {String} imageUrl
	 * @apiParam {Boolean} canAccessUserList
	 * @apiParam {Number} order
	 * @apiParam {String} file[fileEncoded] - Default: null - Defina para novas imagens - Ex: Imagem no formato base64
	 * @apiParam {String} file[fileType] - Default: null - Defina para novas imagens - Ex: (image/jpeg, image/png)
	 *
	 * @apiParamExample {json} Request-Example:
	 *    {
	 *      "name": "Produto Teste 3",
	 *      "url": "Descrição teste",
	 *      "slug": "permissao_teste",
	 *      "imageUrl": 'image.jpeg',
	 *      "canAccessUserList": false ,
	 *      "order": 0,
	 *      "file": {
	 *          "fileEncoded": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD..."
	 *          "fileType": "image/jpeg"
	 *          }
	 *    }
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

			/**
			 * @var $entity DProducts
			 */
			$entity = $this->repository->find($request->getAttribute('resourceId'));

			if (empty($entity)) {
				return new JsonResponse([
					'result' => false,
					'message' => 'Produto inválido ou não encontrado',
					'error' => true
				]);
			}

			if (isset($param['file'])) {
				$fileName = $this->fileUploader->base64toImage($param['file']['fileEncoded'], $param['file']['fileType']);
				if (!$fileName) {
					return new JsonResponse([
						'error' => true,
						'message' => 'Erro ao salvar a imagem.'
					]);
				}
				if ($entity->getImageUrl()) {
					$this->fileUploader->deleteFile($entity->getImageUrl());
				}

				$entity->setImageUrl($fileName);
			}

			$entity = EntityHelper::setOptions($param, $entity);
			$result = $this->repository->update($entity);

		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse([
			'result' => $result,
			'message' => 'success'
		]);
	}
}