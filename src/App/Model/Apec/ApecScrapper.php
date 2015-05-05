<?php

namespace App\Model\Apec;

use App\Model\JobScapper;


class ApecScrapper extends JobScapper {

    public function __construct()
    {
        parent::__construct();

        $this->editRegex('jobTitle', '/ma_super_regex/');
        $this->editRegex('jobUrl', '');
        $this->editRegex('jobDate', '');
        $this->editRegex('jobTown', '');
        $this->editRegex('jobSkills', '');
        $this->editRegex('jobTraining', '');
        $this->editRegex('jobType', '');
        $this->editRegex('jobText', '');
        $this->editRegex('jobCompany', '');
        $this->editRegex('jobCrawler', '');
        $this->editRegex('jobTechnologies', '');
        $this->editRegex('jobWage', '');
        $this->editRegex('jobId', '');
    }

}