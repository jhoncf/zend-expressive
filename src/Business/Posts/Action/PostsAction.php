<?php

namespace Business\Features\Action;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 08/02/17
 * Time: 13:27
 */
class PostsAction {

    /**
     * @var \Business\Entities\PostsRepository
     */
    private $repository;

    /**
     * @api {get} /api/Posts Todos Posts
     * @apiName PostsAction
     * @apiGroup Posts
     * @apiVersion 0.1.0
     * @apiSuccess {String} json New JsonResponse.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "result": [
     *          {
     *              "id": 1,
     *              "title": "Post Title Teste",
     *              "content": "text",
     *              "created_at": 0
     *          }
     *      ],
     *      "count": 1
     *   }
     *
     * @apiError FeaturesNotFound The Features could not be listed.
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
     * FeaturesAction constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->repository = $em->getRepository('Business\Entities\Posts');
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
            $param = $request->getParsedBody();
            $page = isset($param['page']) ? $param['page'] : 0;
            $limit = isset($param['limit']) ? $param['limit'] : 20;
            $companies = $this->repository->findAll($page, $limit);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return new JsonResponse($companies);
    }
}