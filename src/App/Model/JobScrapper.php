<?php

namespace App\Model;

abstract class JobScrapper {
    //Extracted attributes
    protected $jobAttributes = array(
        'title'          => array('regex' => '', 'result' => '', 'compulsory' => false),
        'url'            => array('regex' => '', 'result' => '', 'compulsory' => false),
        'date'           => array('regex' => '', 'result' => '', 'compulsory' => false),
        'town'           => array('regex' => '', 'result' => '', 'compulsory' => false),
        'skills'         => array('regex' => '', 'result' => '', 'compulsory' => false),
        'training'       => array('regex' => '', 'result' => '', 'compulsory' => false),
        'type'           => array('regex' => '', 'result' => '', 'compulsory' => false),
        'text'           => array('regex' => '', 'result' => '', 'compulsory' => false),
        'company'        => array('regex' => '', 'result' => '', 'compulsory' => false),
        'technos'        => array('regex' => '', 'result' => '', 'compulsory' => false),
        'wage'           => array('regex' => '', 'result' => '', 'compulsory' => false),
        'crawler'        => array('regex' => '', 'result' => '', 'compulsory' => false),
        'id'             => array('regex' => '', 'result' => '', 'compulsory' => false),
        'experience'     => array('regex' => '', 'result' => '', 'compulsory' => false),
    );

    public function scrap($input){
        $this->clearAttributes();

        //true: OK (default)
        //false: NOK (a regex is empty || a result is empty)
        $returnCode = true;

        foreach($this->jobAttributes as $attr => $val){
            if($attr == 'crawler') {
                if(empty($val['result']))
                    $returnCode = false;

                continue;
            }

            if($attr == 'technos')
                continue;

            if(empty($val['regex']) && $val['compulsory']) {
                $returnCode = false;
            }
            elseif(!empty($val['regex'])) {
                preg_match($val['regex'], $input, $this->jobAttributes[$attr]['result']);
                if(empty($this->jobAttributes[$attr]['result'])){
                    $returnCode = false;
                }else{
                    $this->jobAttributes[$attr]['result'] = html_entity_decode(strip_tags($this->jobAttributes[$attr]['result'][1]));
                }
            }
        }

        $this->scrapTechnos();

        if(!empty($this->jobAttributes['town']['result'])){
            $geoloc = DBManager::getInstance()->getGeoloc($this->jobAttributes['town']['result']);

            $this->jobAttributes['long'] = array('result' => $geoloc['long']);
            $this->jobAttributes['lat'] = array('result' => $geoloc['lat']);
        }

        return $returnCode;
    }

    public function clearAttributes(){
        foreach($this->jobAttributes as $attr => $val){
            $this->jobAttributes[$attr]['result'] = '';
        }
    }

    public function getAttributes(){
        return $this->jobAttributes;
    }

    protected function editRegex($attr, $regex){
        $this->jobAttributes[$attr]['regex'] = $regex;
    }

    protected function imScrapper($name){
        $this->jobAttributes['crawler']['result'] = $name;
    }

    public function setAttr($attr, $val){
        $this->jobAttributes[$attr]['result'] = $val;
    }
    
    protected function scrapTechnos(){
        $this->jobAttributes['technos']['result'] = TechnoToujoursPareil::getInstance()->whatTechnosExist($this->jobAttributes['text']['result']);
    }
}
