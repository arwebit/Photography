<?php

namespace App\Photography;

use mysqli_sql_exception;


require_once './config/database.php';

/**
 * Description of DB
 *
 * @author Soumyanjan
 */
class DB
{

    private static $connection = null;
    private static $username = "";
    private static $password = "";
    private static $database = "";
    private static $host = "";

    public static function getConnection()
    {
        self::$host = HOSTNAME;
        self::$username = DB_USERNAME;
        self::$password = DB_PASSWORD;
        self::$database = DB_NAME;
        try {
            self::$connection = mysqli_connect(self::$host, self::$username, self::$password, self::$database);
        } catch (mysqli_sql_exception $e) {
            echo $e->getMessage();
        }
        return self::$connection;
    }
}
