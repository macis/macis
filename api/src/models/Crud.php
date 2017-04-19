<?php
/**
 * Created by PhpStorm.
 * User: wilfried
 * Date: 27/08/2015
 * Time: 17:37
 */

namespace Models;

use PDO;
use PDOException;

abstract class Crud
{
    static $tablename = "table";
    static $idfield = "id";
    static $deletedfield = "deleted";
    static $sql_insert_special = ",`created` = now()"; // ,`date_created` = now()
    static $sql_update_special = ",`updated` = now()"; // ,`date_updated` = now()
    static $sql_delete_special = ",`deleted` = now()"; // ,`date_deleted` = now()
    static $sql_ownerid = ", `id_user` = :id_user";
    static $pdo_connector = "defaultDB";
    static $fields = array();


    /**
     * @return array|\Exception|PDOException
     */
    public static function getList() {
        $list = self::selectSimple();
        return $list;
    }



    // Internal functions

    /**
     * @param $values
     * @return bool|\Exception|PDOException|string
     */
    protected static function insert($values) {

        $ins = array();
        foreach ($values as $key => $value) {
            if (in_array($key, static::$fields)) {
                $ins[$key] = " `". $key . "` = :". $key . " ";
            }
        }


        $sql = "INSERT INTO `".static::$tablename."` SET ".implode(',',$ins)." ".static::$sql_ownerid." ".static::$sql_insert_special.";";

        if (!count($ins)) {
            return false;
        }

        try {
            $pdo = \DB\connectDB::getPDO();

            $sth = $pdo->prepare($sql);
            foreach ($ins as $f => $k) {
                $sth->bindValue(":".$f, $values[$f], self::guessType($values[$f]));
            }
            // $sth->bindValue(":id_user", \singletons\Me::getInstance()->user["id"], self::guessType(\singletons\Me::getInstance()->user["id"]));

            $sth->execute();
            $id = $pdo->lastInsertId();
            return $id;
        } catch (\PDOException $e) {
            return $e;
        }
    }

    /**
     * @param $id
     * @param $values
     * @return bool|\Exception|PDOException
     */
    protected static function update($id, $values) {

        $ins = array();
        foreach ($values as $key => $value) {
            if (in_array($key, static::$fields)) {
                $ins[$key] = " `". $key . "` = :". $key . " ";
            }
        }

        $sql = "UPDATE `".static::$tablename."` SET ".implode(',',$ins)." ".static::$sql_ownerid." ".static::$sql_update_special." where ".static::$idfield." = :id ;";

        // error_log(print_r($sql, true));

        if (!count($ins)) {
            return false;
        }

        try {
            $pdo = \DB\connectDB::getPDO();

            $sth = $pdo->prepare($sql);
            foreach ($ins as $f => $k) {
                $sth->bindValue(":".$f, $values[$f], self::guessType($values[$f]));
            }
            $sth->bindValue(":id", $id, self::guessType($id));
            // $sth->bindValue(":id_user", \singletons\Me::getInstance()->user["id"], self::guessType(\singletons\Me::getInstance()->user["id"]));
            $sth->execute();
            $count =$sth->rowCount();
            if ($count >= 1) {
                return true;
            }
        } catch (\PDOException $e) {
            // error_log(print_r($e, true));
            return $e;
        }

    }


    protected static function selectSimple($params = NULL, $fields = NULL) {
//      error_log("-- selectSimple --");
//      je verif les champs demandés
        $select = array();
        if ($fields == NULL) {
            $select = static::$fields;
        } else {
            foreach ($fields as $f) {
                if (in_array($f, static::$fields)) {
                    $select[] = $f;
                }
            }
        }

        // je verifie tout les champs de recherche
        $where = array();
        if ($params != NULL) {
            foreach ($params as $k => $v) {
                if (in_array($k, static::$fields)) {
                    $where[$k] = " `".$k."` = :".$k;
                }
            }
        }

        $sql = "SELECT ".implode(',',$select) ." FROM `".static::$tablename."`";
        if (count($where)) {
            $sql .= " WHERE ".implode(" AND ", $where);
        }

//        error_log(print_r($sql, true));
//        error_log(print_r($params, true));

        try {
            $pdo = \DB\connectDB::getPDO();
            $sth = $pdo->prepare($sql);
            foreach ($where as $f => $k) {
                $sth->bindValue(":".$f, $params[$f], self::guessType($params[$f]));
            }

            $sth->execute();
            $return = $sth->fetchAll(\PDO::FETCH_ASSOC);
            // error_log(print_r($return, true));
            return $return ;
        } catch (\PDOException $e) {
            return $e;
        }
    }


    /**
     * @param $value
     * @return bool|int
     */
    public static function guessType($value) {
        if(is_int($value))
            $param = PDO::PARAM_INT;
        elseif(is_bool($value))
            $param = PDO::PARAM_BOOL;
        elseif(is_null($value))
            $param = PDO::PARAM_NULL;
        elseif(is_string($value))
            $param = PDO::PARAM_STR;
        else
            $param = FALSE;
        return $param;
    }


}