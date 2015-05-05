<?php

namespace App\Model;


class JobScrapper {
    //Extracted attributes
    protected $jobAttributes = array(
        'jobTitle'          => array('regex' => '', 'result' => ''),
        'jobUrl'            => array('regex' => '', 'result' => ''),
        'jobDate'           => array('regex' => '', 'result' => ''),
        'jobTown'           => array('regex' => '', 'result' => ''),
        'jobSkills'         => array('regex' => '', 'result' => ''),
        'jobTraining'       => array('regex' => '', 'result' => ''),
        'jobType'           => array('regex' => '', 'result' => ''),
        'jobText'           => array('regex' => '', 'result' => ''),
        'jobCompany'        => array('regex' => '', 'result' => ''),
        'jobCrawler'        => array('regex' => '', 'result' => ''),
        'jobTechnologies'   => array('regex' => '', 'result' => ''),
        'jobWage'           => array('regex' => '', 'result' => ''),
        'jobId'             => array('regex' => '', 'result' => '')
    );


    public function scrap($input){
        foreach($this->jobAttributes as $attr => $val){
            preg_match($val['regex'], $input, $val['result']);
        }

        return true;
    }

    protected function editRegex($attr, $regex){
        $this->jobAttributes[$attr]['regex'] = $regex;
    }
}