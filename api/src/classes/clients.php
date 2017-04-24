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

    /**
     * @param $page
     * @param $search
     * @return mixed
     *
     */
    public static function search($page, $search)
    {
        //  $fields = array ('id','firstname');
        $res = \Models\Contacts::search($search, $page, 100, $fields);
        return $res;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function get($id)
    {
        $res = \Models\Contacts::getById($id);
        return $res;
    }

    /**
     * @param $id
     * @param $values
     * @return mixed
     */
    public static function put($id, $values)
    {
        $res = \Models\Contacts::put($id, $values);
        return $res;
    }

    /**
     * @param $values
     * @return bool|\Exception|\PDOException|string
     */
    public static function post($values)
    {
        $res = \Models\Contacts::post($values);
        return $res;
    }

}