<?php
/**
 * Created by PhpStorm.
 * User: Dcide
 * Date: 12/05/2016
 * Time: 15:43
 */

namespace App\Admin;

use App\Util\CustomRequest;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Template;

class ApiAction extends \App\Action\ApiAction{

    public function __construct(ContainerInterface $container, EntityManager $em) {
        parent::__construct($container, $em);

    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $resource = $request->getAttribute('resource');
        $resourceId = $request->getAttribute('resourceId');
        $type = $request->getAttribute('relation');

        if(!is_numeric($resourceId)){
            $relation = $resourceId;
        }else {
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
                if (!empty($resourceId)){
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
        $class = sprintf('App\Admin\Action\\%s\\%s', $resource, $resource.$action);
        if (!class_exists($class)) {
            return $this->throwJsonException(sprintf('Requested resource not found: %s', $class), 400);
        }
        try {
            $callable = new $class($this->em, $this->container);

            $contents = $request->withHeader('JSON', 'application/json')->getBody()->getContents();
            $customRequest = new CustomRequest($request);
            $customRequest->setContent(json_decode($contents, true));

            return $callable($customRequest, $response, $next);
        } catch (\Exception $e) {
            return $this->throwJsonException($e->getMessage(), $e->getCode());
        }
    }
}