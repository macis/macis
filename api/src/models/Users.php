<?php
/**
 * Created by PhpStorm.
 * User: Wilfried
 * Date: 26/04/2017
 * Time: 16:05
 */

namespace Models;


class Users extends Crud
{
    static $tablename = "users";
    static $idfield = "id";
    static $deletedfield = "deleted";
    static $fields = array(
        'id',
        'username',
        'password',
        'authenticator',
        'id_organization',
        'title',
        'firstname',
        'lastname',
        'email'
    );

    public static function getByUsername($username)
    {
        try {
            $pdo = \DB\connectDB::getPDO();

            $sql = "SELECT u.id as id_user, o.id as id_organization, title, firstname, lastname, email, o.name as organization
              FROM users u
              JOIN organization o on o.id = u.id_organization
              WHERE username = :username ";
            $sth = $pdo->prepare($sql);
            $sth->bindParam(':username', $username, \PDO::PARAM_STR);
            $sth->execute();

            $user = $sth->fetchALL(\PDO::FETCH_ASSOC);
            $_SESSION['user'] = $user[0];
        } catch (\PDOException $e) {
            $container->logger->debug("PDO ERROR". $e->getMessage(), $e->getTrace()) ;
        }
    }

}
