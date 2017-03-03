<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 16/02/17
 * Time: 11:50
 */

namespace Business\Posts\Action;


use App\Util\CustomRequest;
use Business\Entities\Post;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class PostsCreate{
    /**
     * @var \Business\Entities\PostRepository
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
        $this->repository = $em->getRepository('Business\Entities\Post');
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
            $entity = new Post();
            $entity->setTitle('Hue');
            $entity->setContent('Huezera');
            $entity->setCreatedAt(new \DateTime());

            $companies = $this->repository->save($entity);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return new JsonResponse($companies);
    }
}