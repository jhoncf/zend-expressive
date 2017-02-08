<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 13/02/2017
 * Time: 19:56
 */

namespace App\Helper;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class InputFilter {

    private $container;
    
    private $config;
    
    public function __construct($container, $config) {
        
        $this->config = $config;
        $this->container = $container;
    }
    
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
        
        $param = $request->getParsedBody();
        
        foreach ($this->config as $input){

            if(!isset($param[$input]) || empty($param[$input])){
            $error[]= $input;
            }
        }
        
        if (isset($error) || !empty($error)){
            return new JsonResponse([
                "error" => [
                    "fields" => $error,
                    "message" => "Campos não preenchidos."
                ]
            ]);
        }else{
            return $next($request,$response);
        }
    }
}