<?php

namespace App\Model;

class TechnoToujoursPareil {

    /**
     * @var TechnoToujoursPareil
     */
    private static $instance;

    private $technos = array();

    private function __construct()
    {
        $this->loadTechnos();
    }

    public static function getInstance()
    {
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadTechnos()
    {
        $this->technos = DBManager::getInstance()->getTechnos();
    }

    public function whatTechnosExist($string){
        $matches = array();

        $words = preg_split('#[.,;?!\s]#', $string);

        if(!empty($words)){
            foreach($words as $v){
                $v = strtolower($v);
                if(isset($this->technos[$v]))
                    $matches[] = array($this->technos[$v] => $v);
            }
        }

        return $matches;
    }
}

