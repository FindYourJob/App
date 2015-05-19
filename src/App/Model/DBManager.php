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

    /**
     * @var \PDO
     */
    private $db;

    private function __construct()
    {
        $this->connect();

    }

    public static function getInstance()
    {
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect()
    {
        require __DIR__.'/serverConfig.php';
        /**
         * @var string $db_host
         * @var string $db_base
         * @var string $db_user
         * @var string $db_pass
         */

        $this->db = new \PDO('mysql:host='.$db_host.';dbname='.$db_base.';charset=utf8', $db_user, $db_pass);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function insert($input){
        $expected = array(
            'id',
            'title',
            'url',
            'date',
            'town',
            'postalCode',
            'skills',
            //'training',
            'type',
            'company',
            //'crawler',
            //'technologies',
            'wage',
            'text'
        );

        try {
            $queryString = 'INSERT INTO mytable VALUES (';

            foreach($expected as $attr){
                if (!isset($input[$attr])) {
                    if($expected[0] != $attr)
                        $queryString.= ', ';
                    $queryString.= ':'.$attr;
                }else{
                    throw \RuntimeException('DBManager: insert: Expected field \'' . $attr . '\' before insertion');
                }
            }

            $queryString.=');';

            $query = $this->db->query($queryString);
            foreach($expected as $attr){
                $query->bindParam(':'.$attr, $input[$attr]);
            }
            $query->execute();

        }catch(\Exception $e){
            $this->logError('insert', $e);
        }
    }

    public function select($what, $from, $where = '', $extra = '', $params = array()){

        try {
            $queryString = 'SELECT ';

            if(empty($what) || empty($from))
                throw new \RuntimeException('DBManager: select: $what and $from must not be empty');

            foreach($what as $attr){
                if($what[0] != $attr)
                    $queryString.= ', ';
                $queryString.= $attr;
            }

            $queryString.=' FROM '.$from;

            if(!empty($where))
                $queryString.= ' WHERE '.$where;

            $queryString.= ' '.$extra;

            $query = $this->db->query($queryString);
            foreach($params as $param => $value){
                $query->bindParam(':'.$param, $value);
            }
            $query->execute();

            $output = array();
            if($result = $query->fetch(\PDO::FETCH_ASSOC)){
                $output[] = $result;
            }

            return $output;

        }catch(\Exception $e){
            $this->logError('select', $e);
        }
    }

    /**
     * Logs errors happening in database context
     * @param string $param
     * @param \Exception $exception
     * @throws \Exception
     */
    private function logError($param, \Exception $exception){
        //As a debug, we throw the exception instead of logging it
        echo 'Param: '.$param;
        throw $exception;
    }

}

