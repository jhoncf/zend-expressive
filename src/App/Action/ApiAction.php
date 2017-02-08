<?php
/**
 * Created by PhpStorm.
 * User: Dcide
 * Date: 12/05/2016
 * Time: 15:43
 */

namespace App\Action;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Template;

class ApiAction {
    protected $container;
    protected $em;

    public function __construct(ContainerInterface $container, EntityManager $em) {
        $this->container = $container;
        $this->em = $em;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
        $resource = $request->getAttribute('resource');
        $resourceId = $request->getAttribute('resourceId');
        $type = $request->getAttribute('relation');

        if (!is_numeric($resourceId)) {
            $relation = $resourceId;
        }
        else {
            $relation = 'read';
        }
        $uriAction = ucfirst($relation);

        switch ($request->getMethod()) {
            case 'GET':
                if (!empty($resourceId)) {
                    $action = $uriAction;
                    break;
                }
                $action = 'Action';
                break;

            case 'POST':
                if (!empty($resourceId)) {
                    $action = $uriAction;
                    break;
                }
                $action = 'Create';
                break;

            case 'PUT':
            case 'PATCH':
                if (!$resourceId) {
                    return $this->throwJsonException('Missing resource id for request', 400);
                }
                $action = 'Update';
                break;
            case 'DELETE':
                if (!$resourceId) {
                    return $this->throwJsonException('Missing resource id for DELETE request', 400);
                }
                $action = 'Delete';
                break;
            default:
                return $this->throwJsonException('Unsupported request method', 405);
        }
        $class = sprintf('Business\\%s\\Action\\%s', $resource, $resource . $action);
        if (!class_exists($class)) {
            return $this->throwJsonException(sprintf('Requested resource not found: %s', $class), 400);
        }
        try {
            $callable = new $class($this->em, $this->container);

            $contents = $request->withHeader('JSON', 'application/json')->getBody()->getContents();
            $customRequest = new CustomRequest($request);
            $customRequest->setContent(json_decode($contents, true));

            return $callable($customRequest, $response, $next, $this->container);
        } catch (\Exception $e) {
            return $this->throwJsonException($e->getMessage(), $e->getCode());
        }
    }

    protected function throwJsonException($message, $status) {
        // Ensure a valid HTTP status
        if (!is_numeric($status) || ($status < 400) || ($status > 599)) {
            $status = 500;
        }
        $response = new JsonResponse([], $status);
        $errors = [
            'error' => [
                'status' => $response->getReasonPhrase() ? $status : 400,
                'title' => $response->getReasonPhrase() ?: 'Bad Request',
                'detail' => $message,
                'links' => [
                    'related' => 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'
                ]
            ]
        ];
        return new JsonResponse($errors, $response->getStatusCode());
    }
}