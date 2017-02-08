<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 24/01/17
 * Time: 16:29
 */

namespace Business\Reports\Action;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class ReportsLoginStatus {
    /**
     * @var \Business\Entities\DLoginStatusesRepository
     */
    private $repository;

    /**
     * DLoginStatuses constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->repository = $em->getRepository('Business\Entities\DLoginStatuses');
    }

    /**
     * @api {GET} /api/Reports/LoginStatus
     * @apiName ReportsLoginStatus
     * @apiGroup Reports
     * @apiVersion 0.1.0
     *
     * @apiSuccess {String} json New JsonResponse.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "result": [ {
     *                  "id": "1",
     *                  "active": false,
     *                  "started": {
     *                      "date": "2016-09-26 11:30:48.000000",
     *                      "timezone_type": 3,
     *                      "timezone": "America/Sao_Paulo"
     *                  },
     *                  "lastAccess": {
     *                      "date": "2016-09-26 11:30:48.000000",
     *                      "timezone_type": 3,
     *                      "timezone": "America/Sao_Paulo"
     *                  },
     *                  "sessionKey": "8d3b7fb4fda4852674b188878622bd4a1337994b68a1b40c0b60ad550869234f",
     *                  "ip": "192.168.0.42",
     *                  "clientIp": "192.168.0.42",
     *                  "dUser": {
     *                      "id": 612,
     *                      "name": "Jhonatas",
     *                      "surname": "Faria",
     *                      "email": "jhonatas@dcide.com.br",
     *                      "imageId": null,
     *                  "created": {
     *                      "date": "2015-03-23 08:36:38.000000",
     *                      "timezone_type": 3,
     *                      "timezone": "America/Sao_Paulo"
     *                      },
     *                  "modified": {
     *                      "date": "2017-01-10 11:27:23.000000",
     *                      "timezone_type": 3,
     *                      "timezone": "America/Sao_Paulo"
     *                  },
     *                  "activationKey": "580f69e1b93ad",
     *                  "blocked": false,
     *                  "deleted": false,
     *                  "deletedDate": null,
     *                  "status": "migrated",
     *                  "incorrectLoginAttempts": 0,
     *                  "isTemp": false
     *                  }
     *      }],
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
            if ($request->getMethod() !== 'GET') {
                return new JsonResponse([
                    'error' => [
                        'title' => 'Bad Request',
                        'detail' => 'Unsupported request method',
                    ]
                ]);
            }
            $params = $request->getQueryParams();

            $dateRange = [];
            $status = isset($params['status']) ? filter_var($params['status'], FILTER_VALIDATE_BOOLEAN) : null;

            if (isset($params['endDate']) && $params['startDate']) {
                $dateRange = [
                    'endDate' => $params['endDate'],
                    'startDate' => $params['startDate']
                ];
            }
            $result = $this->repository->findByStatus($status, $dateRange);

        } catch (DUsersExceptions $e) {
            throw new \Exception($e->getMessage());
        }
        return new JsonResponse([
            "result" => $this->__output($result),
            "message" => 'success'
        ]);
    }

    private function __output($output) {
        $resultOutput = [];

        if (empty($output)) {
            return [];
        }
        foreach ($output as $key => $value) {
            $resultOutput[$key] = $value;
            if (isset($value['dUser']['dCompanies'][0])) {
                $resultOutput[$key]['dUser']['dCompanies'] = $value['dUser']['dCompanies'][0];
            }
            else {
                $resultOutput[$key]['dUser']['dCompanies'] = '';

            }
        }
        return $resultOutput;
    }
}