<?php
/**
 * Created by PhpStorm.
 * User: wilfried
 * Date: 05/01/2017
 * Time: 13:41
 */

namespace DB;


class connectDB
{
    private static $pdo;

    public static function getPDO() {

        // $config = Config::get();
        $config = require __DIR__ . '/../settings.php';
        $config = $config['settings'];

//        print_r($config);die();
        // if (!$config) { }

        try {
            self::$pdo = new \PDO(
                "mysql:dbname=".$config['db']['dbname'].";host=".$config['db']['host'].";charset=".$config['db']['charset'],
                $config['db']['user'],
                $config['db']['pass']);

        } catch (\PDOException $e) {
            print_r($e);
        }

        return self::$pdo;
    }
}