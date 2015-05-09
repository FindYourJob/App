<?php

namespace App\Model\Apec;

use App\Model\JobScrapper;


class ApecScrapper extends JobScrapper {

    public function __construct()
    {
        $this->editRegex('title', "#<title>([\s\S]+?) - Offre d'emploi#");
        $this->editRegex('url', "");
        $this->editRegex('date', "#<th>Date de publication :</th>[\r\n\t ]*?<td>([0-9]{2})/([0-9]{2})/([0-9]{4})</td>#");
        //$this->editRegex('date', "/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/");
        $this->editRegex('town', "#<th>Lieu :</th>[\r\n\t ]*?<td>(.+?)</td>#");
        $this->editRegex('skills', "");
        $this->editRegex('training', "");
        $this->editRegex('type', "");
        $this->editRegex('text', "#<div class=\"boxContentInside\">[\r\n\t ]*?<p>(.+?)</p>#");
        //$this->editRegex('company', "#<th valign=\"top\">Société :</th>[\r\n\t ]*?<td>[\r\n\t ]*?(?:<img[\s\S]/>[\r\n\t ]*?<br/>)(.+?)<br/>#");
        $this->editRegex('company', "#<th valign=\"top\">Société :</th>[\r\n\t ]*?<td>(?:[\r\n\t ]*?<img[\s\S]*?/>[\r\n\t ]*?<br />)[\r\n\t ]*?([a-zA-Z][\s\S]*?)[\r\n\t ]*?<br />#");
        $this->editRegex('crawler', "");
        $this->editRegex('technologies', "");
        $this->editRegex('wage', "#<th>Salaire :</th>[\r\n\t ]*?<td>(.+?)</td>#");
        $this->editRegex('id', "#<th>Référence Apec :</th>[\r\n\t ]*?<td>(.+?)</td>#");
    }

}