<?php

namespace App\Model\Apec;

use App\Model\JobScapper;


class ApecScrapper extends JobScapper {

    public function __construct()
    {
        parent::__construct();

        $this->editRegex('title', '/ma_super_regex/');
        $this->editRegex('url', '');
        $this->editRegex('date', '');
        $this->editRegex('town', '');
        $this->editRegex('skills', '');
        $this->editRegex('training', '');
        $this->editRegex('type', '');
        $this->editRegex('text', '');
        $this->editRegex('company', '');
        $this->editRegex('crawler', '');
        $this->editRegex('technologies', '');
        $this->editRegex('wage', '');
        $this->editRegex('id', '');
    }

}