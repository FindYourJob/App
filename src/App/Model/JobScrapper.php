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
        'title'          => array('regex' => '', 'result' => ''),
        'url'            => array('regex' => '', 'result' => ''),
        'date'           => array('regex' => '', 'result' => ''),
        'town'           => array('regex' => '', 'result' => ''),
        'skills'         => array('regex' => '', 'result' => ''),
        'training'       => array('regex' => '', 'result' => ''),
        'type'           => array('regex' => '', 'result' => ''),
        'text'           => array('regex' => '', 'result' => ''),
        'company'        => array('regex' => '', 'result' => ''),
        'crawler'        => array('regex' => '', 'result' => ''),
        'technologies'   => array('regex' => '', 'result' => ''),
        'wage'           => array('regex' => '', 'result' => ''),
        'id'             => array('regex' => '', 'result' => ''),
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
}
