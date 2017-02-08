<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 13/02/2017
 * Time: 10:07
 */

namespace App\Util;

use Locale;
use Zend\Expressive\Router\RouteResult;

class LocalizationMiddleware {
    public function __invoke($request, $response, $next)
    {
        $locale = $request->getAttribute('locale', 'pt_BR');
        Locale::setDefault($locale);
        return $next($request, $response);
    }
}