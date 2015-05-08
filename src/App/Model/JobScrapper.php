<?php

namespace App\Model;

class JobScrapper {
    //Extracted attributes
    protected $jobAttributes = array(
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
        'id'             => array('regex' => '', 'result' => '')
    );


    public function scrap($input){
        $this->clearAttributes();

        //true: OK (default)
        //false: NOK (a regex is empty || a result is empty)
        $returnCode = true;

        foreach($this->jobAttributes as $attr => $val){
            if(empty($val['regex'])) {
                $returnCode = false;
            }
            else {
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
}