<?php
/**
 * Created by PhpStorm.
 * User: wilfried
 * Date: 28/05/2016
 * Time: 23:27
 */

namespace macis\classes;

class clients
{

    public static function search($page, $search)
    {
        $res = \Models\Contacts::search($search, $page);
        return $res;
    }


    public static function get($id)
    {
        $res = \Models\Contacts::getById($id);
        return $res;
    }




}