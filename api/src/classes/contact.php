<?php
/**
 * Created by PhpStorm.
 * User: wilfried
 * Date: 28/05/2016
 * Time: 23:27
 */

namespace macis\classes;

use Psr\Http\Message\RequestInterface;


class contact
{

    public static function search($container, RequestInterface $request)
    {
        $search = $request->getAttribute('route')->getArgument('search');
        $page = $request->getAttribute('route')->getArgument('page');

        $res = \Models\Contacts::search($search, $page);

        return $res;
    }


    public static function get($container, RequestInterface $request)
    {
        $id = $request->getAttribute('route')->getArgument('id');

        $res = \Models\Contacts::getById($id);

        return $res;
    }




}