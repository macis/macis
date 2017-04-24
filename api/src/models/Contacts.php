<?php
/**
 * Created by PhpStorm.
 * User: wilfried
 * Date: 05/01/2017
 * Time: 13:31
 */

namespace Models;


class Contacts extends Crud
{

    static $tablename = "contacts";
    static $idfield = "id";
    static $deletedfield = "deleted";
    static $fields = array(
        'id',
        'social_number',
        'lastname',
        'birthname',
        'firstname',
        'title',
        'gender',
        'birthdate',
        'deathdate',
        'email',
        'address',
        'postal_code',
        'city',
        'country',
        'phone',
        'phone_mobile',
        'phone_pro',
        'job',
        'referal_medic',
        'social_collect',
        'social_insurance',
        'marital_status',
        'referal_person',
        'referal_person_phone',
        'comment',
        'history_medical',
        'history_surgical',
        'history_gynecological',
        'history_family',
        'allergy',
        'payment_status'
    );


    /**
     * @param $id
     * @return mixed
     */
    public static function getById($id) {
        if (!isset($id)) {
            return false;
        }
        $search = array('id' => $id);
        $list = self::selectSimple($search);
        return $list[0];
    }

    /**
     * @param $search
     * @return mixed
     */
    public static function search($search, $page, $limit = 20, $fields = "") {
        $search = strtolower(iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $search));
        $pdo = \DB\connectDB::getPDO();

        $res = array();
        // combien je veux de fiches par page
        $limit = (isset($limit) ? 20 : $limit);

        // récupère la page en cours
        if (!$page) {
            $page = 0;
        }

        // récupère les champs
        if (is_array($fields)) {
            foreach ($fields as $field) {
                if (in_array($field, self::$fields)) {
                    $fields_valid[] = $field;
                }
            }
            $fields = implode(" , ", $fields_valid);
        } else {
            $fields = " id, firstname, lastname, title ";
        }

        // construit la requête
        try {
            $sql = "SELECT SQL_CALC_FOUND_ROWS ";
            // $sql .= " * "; //id, firstname, lastname, title
            $sql .= $fields;
            $sql .= " FROM contacts ";
            if (!empty($search)) {
                $sql .= " WHERE MATCH(firstname,lastname) AGAINST (:search IN BOOLEAN MODE) ";
            }
            $sql .= " ORDER BY lastname ";
            $sql .= " LIMIT :start , :limit";
            $sth = $pdo->prepare($sql);

            $start = $page * $limit;
            $sth->bindParam(':start', $start, \PDO::PARAM_INT);
            $sth->bindParam(':limit', $limit, \PDO::PARAM_INT);
            if (!empty($search)) {
                $search = array_filter(explode(" ", $search));
                array_walk($search, function (&$item) {
                    $item = "+%" . $item . "%";
                });
                $search = implode(" ", $search);

                $sth->bindParam(':search', $search, \PDO::PARAM_STR);
            }
            $sth->execute();

            $res['list'] = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } catch( PDOException $Exception ) {
            echo "error";
            print_r($Exception);
        }

        try {
            // compte le nombre de résultats avant limit
            $sql = 'SELECT FOUND_ROWS() as nb';
            $sth = $pdo->prepare($sql);
            $sth->execute();
            $res['count'] = $sth->fetch()['nb'];
        } catch( PDOException $Exception ) {
            echo "error";
            print_r($Exception);
        }
        return $res;
    }

    /**
     * @param $id
     * @param $values
     * @return mixed
     */
    public static function put($id, $values) {
        $list = self::update($id, $values);
        return $list;
    }

    /**
     * @param $values
     * @return bool|\Exception|\PDOException|string
     */
    public static function post($values) {
        $id = self::insert($values);
        return $id;
    }

    public static function delete($id) {
        $values = array(self::$deletedfield => 'now()');
        $list = self::update($id, $values);
        return $list;
    }
}