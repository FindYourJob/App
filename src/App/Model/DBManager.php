<?php

namespace App\Model;
/**
 * Class DBManager : Singleton for Database-handling
 * @package App\Model
 */
class DBManager {

    /**
     * @var DBManager
     */
    private static $instance;

    private function __construct()
    {
        // Your "heavy" initialization stuff here

    }

    public static function getInstance()
    {
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    //Called by DBManager::connect();
    public static function connect()
    {
        require __DIR__.'/serverConfig.php';

        $db = new PDO('mysql:host='.$db_host.';dbname='.$db_base.';charset=utf8', $db_user, $db_pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}

