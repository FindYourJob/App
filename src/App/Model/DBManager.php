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
            'title',
            'url',
            'date',
            'town',
            'long',
            'lat',
            //'skills',
            //'training',
            'type',
            'company',
            //'crawler',
            //'technologies',
            'wage',
            'text'
        );

        var_dump($input);

        try {
            $queryString = 'INSERT INTO jobs VALUES ("", ';

            foreach($expected as $attr){
                //if (isset($input[$attr])) {
                    if($expected[0] != $attr)
                        $queryString.= ', ';
                    $queryString.= ':'.$attr;
                //}else{
                //    throw new \RuntimeException('DBManager: insert: Expected field \'' . $attr . '\' before insertion');
                //}
            }

            $queryString.=');';

            echo 'queryStr:'.$queryString;

            $query = $this->db->prepare($queryString);
            foreach($expected as $attr){
                $query->bindParam(':'.$attr, $input[$attr]['result']);
            }
            $query->execute();

        }catch(\Exception $e){
            $this->logError('insert', $e);
        }
    }

    public function select($queryString, $params = array()){

        try {

            $query = $this->db->prepare($queryString);
            if(!empty($params))
            foreach($params as $param => $value){
                $query->bindParam($param, $value);
            }
            $query->execute();

            $output = array();
            while($result = $query->fetch(\PDO::FETCH_ASSOC)){
                $output[] = $result;
            }

            return $output;

        }catch(\Exception $e){
            $this->logError('select', $e);
        }
    }

    public function insertTechno($name){

        try {

            $queryString = 'INSERT INTO technos VALUES ("", :name);';

            $query = $this->db->prepare($queryString);
            $query->bindParam(':name', $name);
            $query->execute();

        }catch(\Exception $e){
            $this->logError('insert', $e);
        }
    }

    public function getTechnos(){

        $output = array();

        try {
            $queryString = 'SELECT * FROM technos;';
            $query = $this->db->prepare($queryString);
            $query->execute();

            while($r = $query->fetch()){
                $output[$r['name']] = $r['idTechno'];
            }

        }catch(\Exception $e){
            $this->logError('insert', $e);
        }

        return $output;
    }


    public function populateCities(){
        // Augmentation de la time limit
        set_time_limit(800);

        $i=0;
        for($j=1; $j<=18; ++$j) {
            echo __DIR__.'/../../../resource/cities_FR.txt.'.sprintf("%03d", $j);
            $file = file(__DIR__.'/../../../resource/cities_FR.txt.'.sprintf("%03d", $j)) or die("Error while loading file !");
            foreach ($file as $line) {
                echo 'file!<br/>';
                $tmpArray = explode("\t", $line);
                $cityArray["name"] = $tmpArray[2];
                $cityArray["lat"] = $tmpArray[4];
                $cityArray["long"] = $tmpArray[5];
                echo "City name : " . $cityArray["name"] . " Latitude : " . $cityArray["lat"] . " Longitude : " . $cityArray["long"] . '<br/>';
                $i += $this->insertGeoLoc($cityArray);
            }
        }

        echo $i . " ville avec le même nom BITCH!!<br/><br/>";
    }

    private function tableExists($id)
    {
        $results = $this->db->query("SHOW TABLES LIKE '$id'");
        if(!$results) {
            die(print_r($this->db->errorInfo(), TRUE));
        }
        return $results->rowCount()>0;
    }

    private function cityExists($cityName){
        $stmt = $this->db->prepare('SELECT * FROM city' . substr($cityName, 0, 1) . " WHERE name=:cityName;" );
        if(!$stmt->execute(array("cityName" => $cityName))){
            die(print_r($this->db->errorInfo(), TRUE));
        }
        $results = $stmt->fetchAll();
        return count($results)>0;
    }

    private function insertGeoLoc($input){
        $i = 0;
        $tableName = 'city' . substr($input["name"], 0, 1) ;
        //print_r($tableName);
        if(!$this->tableExists($tableName)){
            $createString = "CREATE TABLE " . $tableName . " (\n";
            $createString .= "`name` VARCHAR(150),\n";
            $createString .= "`long` FLOAT(10,6),\n";
            $createString .= "`lat` FLOAT(10,6),\n";
            $createString .= "PRIMARY KEY(name));";
            print_r($createString);
            $this->db->query($createString);
        }
        if(!$this->cityExists($input["name"])){
            $queryString =  'INSERT INTO ' . $tableName . ' VALUES(:name,:long,:lat);';
            $query = $this->db->prepare($queryString);
            $query->execute($input);
        } else {
            ++$i;
            print_r($input["name"] . " already exists.");
        }
        return $i;
    }

    public function getGeoloc($cityName){
        $stmt = $this->db->prepare('SELECT `long`, `lat` FROM city' . substr($cityName, 0, 1) . ' WHERE name=:cityName ;');
        if(!$stmt->execute(array("cityName" => $cityName))){
            die(print_r($this->db->errorInfo(), TRUE));
        }
        $results = $stmt->fetchAll();
        return count($results) > 0 ? $results[0] : null;
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

