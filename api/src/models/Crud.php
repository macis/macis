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
    static $sql_insert_special = "`created` = now()"; // ,`date_created` = now()
    static $sql_update_special = "`updated` = now()"; // ,`date_updated` = now()
    static $sql_delete_special = "`deleted` = now()"; // ,`date_deleted` = now()
    static $sql_owner_field = "id_user";
    static $sql_owner_value = "10";
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

        $sql = "INSERT INTO `:tablename` SET ".implode(',',$ins)."  ,".static::$sql_insert_special." , `:sql_owner_field` = :sql_owner_value;";

        if (!count($ins)) {
            return false;
        }

        try {
            $pdo = \DB\connectDB::getPDO();

            $sql = str_replace(":tablename",self::$tablename, $sql );
            $sql = str_replace(":sql_owner_field",static::$sql_owner_field, $sql );

            $sth = $pdo->prepare($sql);
            foreach ($ins as $f => $k) {
                $sth->bindValue(":".$f, $values[$f], self::guessType($values[$f]));
            }

            $sth->bindValue(":sql_owner_value", static::$sql_owner_value, self::guessType(static::$sql_owner_value));

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

        $sql = "UPDATE `:tablename` SET :ins ,:sql_update_special where :idfield = :id and `:sql_owner_field` = :sql_owner_value; ;";

        // if this is a delete
        if (isset($values['deleted'])) {
            unset($ins['deleted']);
            $sql = "UPDATE `:tablename` SET :sql_delete_special where :idfield = :id and `:sql_owner_field` = :sql_owner_value;;";
        }


        try {
            $pdo = \DB\connectDB::getPDO();

            $sql = str_replace(":tablename",static::$tablename, $sql );
            $sql = str_replace(":idfield",static::$idfield, $sql );
            $sql = str_replace(":sql_owner_field",static::$sql_owner_field, $sql );
            $sql = str_replace(":sql_update_special",static::$sql_update_special, $sql );
            $sql = str_replace(":sql_delete_special",static::$sql_delete_special, $sql );
            $sql = str_replace(":ins",implode(',',$ins), $sql );

            $sth = $pdo->prepare($sql);
            foreach ($ins as $f => $k) {
                $sth->bindValue(":".$f, $values[$f], self::guessType($values[$f]));
            }
            $sth->bindValue(":id", $id, self::guessType($id));
            $sth->bindValue(":sql_owner_value", static::$sql_owner_value, self::guessType(static::$sql_owner_value));
            $sth->execute();

            $count = $sth->rowCount();
            if ($count >= 1) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            return $e;
        }

    }

    /**
     * @param null $params
     * @param null $fields
     * @return array|\Exception|PDOException
     */
    protected static function selectSimple($params = NULL, $fields = NULL) {
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

        $sql = "SELECT :select FROM `:tablename`";
        if (count($where)) {
            $sql .= " WHERE :where";
        }

        try {
            $pdo = \DB\connectDB::getPDO();

            $sql = str_replace(":select",implode(',',$select), $sql );
            $sql = str_replace(":tablename",static::$tablename, $sql );
            $sql = str_replace(":where",implode(" AND ", $where), $sql );

            $sth = $pdo->prepare($sql);
            foreach ($where as $f => $k) {
                $sth->bindValue(":".$f, $params[$f], self::guessType($params[$f]));
            }

            $sth->execute();
            $return = $sth->fetchAll(\PDO::FETCH_ASSOC);
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
