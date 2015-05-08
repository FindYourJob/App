<?php

namespace App\Model\Apec;

use App\Model\JobScrapper;


class ApecScrapper extends JobScrapper {

    public function __construct()
    {
        $this->editRegex('title', "/ma_super_regex/");
        $this->editRegex('url', "");
        $this->editRegex('date', "/<span class=\"html-tag\">&lt;td&gt;<\/span>([0-9]{2})\/([0-9]{2})\/([0-9]{4})<span class=\"html-tag\">&lt;\/td&gt;<\/span>/");
        //$this->editRegex('date', "/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/");
        $this->editRegex('town', "");
        $this->editRegex('skills', "");
        $this->editRegex('training', "");
        $this->editRegex('type', "");
        $this->editRegex('text', "");
        $this->editRegex('company', "");
        $this->editRegex('crawler', "");
        $this->editRegex('technologies', "");
        $this->editRegex('wage', "");
        $this->editRegex('id', "");
    }

}