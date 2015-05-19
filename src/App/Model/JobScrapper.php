<?php

namespace App\Model;

abstract class JobScrapper {
    //Extracted attributes
    protected $jobAttributes = array(
        'title'          => array('regex' => '', 'result' => '', 'compulsory' => true),
        'url'            => array('regex' => '', 'result' => '', 'compulsory' => true),
        'date'           => array('regex' => '', 'result' => '', 'compulsory' => true),
        'town'           => array('regex' => '', 'result' => '', 'compulsory' => false),
        'skills'         => array('regex' => '', 'result' => '', 'compulsory' => false),
        'training'       => array('regex' => '', 'result' => '', 'compulsory' => false),
        'type'           => array('regex' => '', 'result' => '', 'compulsory' => false),
        'text'           => array('regex' => '', 'result' => '', 'compulsory' => true),
        'company'        => array('regex' => '', 'result' => '', 'compulsory' => false),
        'technologies'   => array('regex' => '', 'result' => '', 'compulsory' => false),
        'wage'           => array('regex' => '', 'result' => '', 'compulsory' => false),
        'crawler'        => array('regex' => '', 'result' => '', 'compulsory' => true)
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

            if(empty($val['regex']) && $val['compulsory']) {
                $returnCode = false;
            }
            elseif(!empty($val['regex'])) {
                preg_match($val['regex'], $input, $this->jobAttributes[$attr]['result']);

                if(empty($this->jobAttributes[$attr]['result'])){
                    $returnCode = false;
                }else{
                    unset($this->jobAttributes[$attr]['result'][0]);
                }
            }
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
}