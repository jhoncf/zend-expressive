<?php
/**
 * Created by PhpStorm.
 * User: Dcide
 * Date: 05/05/2016
 * Time: 10:23
 */

namespace App\Util;



trait PaginatorTrait {
    public function getPage()
    {
        return (int) (isset($_GET['p']) ? $_GET['p'] : 1) - 1;
    }
    public function getSearchParam()
    {
        return isset($_GET['s']) ? $_GET['s'] : null;
    }
}