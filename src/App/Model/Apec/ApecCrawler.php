<?php

namespace App\Model\Apec;

use App\Model\CrawlerModel;
use App\Model\DBManager;


class ApecCrawler extends CrawlerModel {

    public function __construct(){
        parent:: __construct(new ApecScrapper);
    } 


    // scan all the jobs offer from an url
    public function crawlMain($u)
    {
        $limit = 1;
        $i = 0;
        $var = true;
        $url = $u;
        while($var){
            //echo 'URL:'.$url.'<br/>';
            $result = $this->crawl($url);
            if(!preg_match('#<a href="([^"]*?)" class="lastItem">Suivante</a>#', $result, $res)){
                $var = false;
            }
            //var_dump($res);
            preg_match_all('#href="/offres-emploi-cadres/(.*?)"#', $result, $urls);
            foreach ($urls[0] as $element){
                $element = preg_replace('#href="#', '', $element);
                $element = preg_replace('#"#', '', $element);
                if ( preg_match('#xtcr#', $element, $t)){
                    $this->scrap('https://cadres.apec.fr'.$element);
                    //die();
                }
            }
            $res = preg_replace('#<a href="#', '', $res[0]);
            $res = preg_replace('#" class="lastItem">Suivante</a>#', '', $res);
            //echo 'RES:'.$res;
            $url = 'https://cadres.apec.fr'.$res;
            ++$i;
            if($i>$limit)
                $var = false;
        }

    }



    public function scrapAction($string, $url)
    {
        parent::scrapAction($string, $url);
        $scrapper = $this->getScrapper();
        $scrapper->setAttr('wageMin', $this->getSalaryMin($scrapper->getAttributes()['wage']['result']));
        $this->insertIntoBdd($scrapper->getAttributes());
        //$this->insertIntoFile($this->getScrapper()->getAttributes());
    }

    public function getSalaryMin($salary)
    {
        if(preg_match("#(^|Entre )([1-9][0-9])(\\s|K|0{3})#", $salary, $minsal)){
            return $minsal[2] . "000,00";
        } 
        return null;
    }
}

