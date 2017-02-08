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
 * Class ProdutosCreate
 * @package Business\Produtos\Action
 */
class ProdutosCreate {

	/**
	 * @var \Business\Produtos\Entity\DProductsRepository
	 */
	private $repository;

	/**
	 * @var
	 */
	private $em;

	/**
	 * @var FileUploader
	 */
	private $fileUploader;
	/**
	 * @api {post} /api/Produtos Adicionar Produto
	 * @apiName ProdutosCreate
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
	 *      }
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
	 * ProdutosCreate constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
		$this->repository = $em->getRepository('Business\Produtos\Entity\DProducts');
		$this->fileUploader = new FileUploader();
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
			$entity = new DProducts();
			$param = $request->getContent();

			if (isset($param['file'])) {
				$file = $this->fileUploader->base64toImage($param['file']['fileEncoded'], $param['file']['fileType']);
				if (!$file) {
					return new JsonResponse([
						'error' => 'true',
						'message' => 'Erro ao salvar a imagem.'
					]);
				}
				$entity->setImageUrl($file);
			}

			$entity = EntityHelper::setOptions($param, $entity);

			$result = $this->repository->save($entity);
		} catch (DUsersExceptions $e) {
			throw new \Exception($e->getMessage());
		}

		return new JsonResponse(['result' => ['id' => $result], 'message' => 'success']);
	}
}