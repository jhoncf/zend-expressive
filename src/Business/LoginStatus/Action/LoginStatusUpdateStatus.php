<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 27/01/17
 * Time: 10:12
 */

namespace Business\LoginStatus\Action;

use App\Util\CustomRequest;
use Business\Entities\DLoginStatuses;
use Doctrine\ORM\EntityManager;
use Exceptions\DUsersExceptions;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class LoginStatusUpdateStatus {

    /**
     * @var \Business\Entities\DLoginStatusesRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * LoginStatusUpdateStatus constructor.
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->em = $em;
        $this->container = $container;
        $this->repository = $em->getRepository('Business\Entities\DLoginStatuses');
    }

    /**
     * @api {GET} /api/LoginStatus/UpdateStatus Atualizar Status
     * @apiName LoginStatusUpdateStatus
     * @apiGroup LoginStatus
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
     * @apiError FeatureNotInserted The Company could not be created.
     *
     * @apiErrorExample Error-Response
     *
     *     HTTP/1.1 404 Not Found
     *     {
     *        "error": {
     *          "status": 500,
     *          "title": "Internal Server Error",
     *          "detail": "Não foi possível Editar a Feature",
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
            $sessionResult = $this->repository->findByStatus(true);
            $sessionTimeout = $this->container->get("config")['session']['lifetime'];

            if (empty($sessionResult)) {
                throw new DUsersExceptions('Informação nválida ou não encontrado.');
            }

            foreach ($sessionResult as $key => $value){
                /**
                 * @var $dLoginStatusesEntity DLoginStatuses
                 */
                $dLoginStatusesEntity = $this->repository->find($value['id']);

                /**
                 * Valida o tempo de expiração da sessão
                 */
                $dateNow = new \DateTime();
                $lastAccessHour = $value['lastAccess']->diff($dateNow)->format('%h');
                $lastAccessMinute = $value['lastAccess']->diff($dateNow)->format('%i');

                $lastAccessSeconds = $this->timeInSeconds($lastAccessHour, $lastAccessMinute);

                if ($lastAccessSeconds > $sessionTimeout) {
                    $dLoginStatusesEntity->setActive(false);
                    $this->repository->update($dLoginStatusesEntity);
                }
            }

        } catch (DUsersExceptions $e) {
            throw new \Exception($e->getMessage());
        }

        return new JsonResponse([
            'message' => 'success'
        ]);
    }

    private function timeInSeconds($hours, $minutes) {
        return $hours * 60 * 60 + $minutes;
    }
}