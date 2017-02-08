<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 30/01/17
 * Time: 15:53
 */

namespace Business\Reports\Action;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class ReportsUsersPlans {
    /**
     * @var \Business\Usuarios\Entity\DUsersRepository
     */
    private $repository;

    /**
     * DLoginStatuses constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->repository = $em->getRepository('Business\Usuarios\Entity\DUsers');
    }

    /**
     * @api {GET} /api/Reports/UsersPlans
     * @apiName ReportsUsersPlans
     * @apiGroup Reports
     * @apiVersion 0.1.0
     *
     *
     * @apiSuccess {String} json New JsonResponse.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "result": [],
     *      "message": "success"
     *     }:
     *
     * @apiError ReportsLoginStatusError
     *
     * @apiErrorExample Error-Response
     *
     *     HTTP/1.1 404 Not Found
     *     {
     *        "error": {
     *          "status": 500,
     *          "title": "Internal Server Error",
     *          "detail": "Error",
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
            $result = $this->repository->findProfilesUsers();

        } catch (DUsersExceptions $e) {
            throw new \Exception($e->getMessage());
        }
        return new JsonResponse([
            "result" => $result,
            "message" => 'success'
        ]);
    }

    private function __output($output) {
        $resultOutput = [];
        return $resultOutput;
    }
}