<?php

namespace App\Model\Monster;

use App\Model\CrawlerModel;
use App\Model\DBManager;


class MonsterCrawler {
    
    // scan all the jobs offer from an url
    public function crawl($u)
    {
        $resume_at = 12;
        $limit = 13;
        $i = 0;

        $var = true;
        $url = $u;
        while($var){
            $result = CrawlerModel::crawl($url);
            if(!preg_match('#<a class=\'box nextLink fnt5\' href=\'(.*)\' rel=\'Suivant\'#', $result, $res)){
                $var = false;
            }
            preg_match_all('#href=(.*?)>#', $result, $urls);  
            if($i >= $resume_at)
            foreach ($urls[0] as $element){
	        $element = preg_replace('#href="#', '', $element);
	        $element = preg_replace('#">#', '', $element);
                if ( preg_match('#jobPosition#', $element, $t)){
                   $this->scrap($element);
                }
            }
            $res = preg_replace('#<a class=\'box nextLink fnt5\' href=\'#', '', $res[0]);
            $res = preg_replace('#\' rel=\'Suivant\'#', '', $res);
            $url = $res;
            ++$i;
            if($i>=$limit)
                $var = false;
        }
        
    }

   //scrap a specific offer 
    public function scrap($url)
    {
        $result = CrawlerModel::crawl($url);
        //the regex only works for non specific template
        if(preg_match('#<h2>Outils#', $result, $t)){
            $this->scrapAction($result, $url);
        } else 
            return;
    } 



    public function scrapAction($result, $url)
    {
        $scrapper  = new MonsterScrapper();
        $scrapper->scrap($result);
        $scrapper->setAttr('url', $url);
        $scrapper->setAttr('date', $this->getDate($scrapper->getAttributes()['date']['result']));
        $scrapper->setAttr('wageMin', $this->getSalary($scrapper->getAttributes()['wageMin']['result']));
        $content = $scrapper->getAttributes();   
        echo 'BDD';
        DBManager::getInstance()->insert($content);
        //To test change path
        /*$file = fopen(tempnam("/var/www/html/Back-end/web", "crawl"), 'a');
        $content = var_export($content, true );
        fputs($file, $content);
        fclose($file);*/
        //die();
    }


    public function getDate($stringDate)
    {
        if($stringDate != null){
            $date = new \DateTime(strftime('%Y/%m/%d', strtotime($stringDate)));
            $date->sub(new \DateInterval('P30D'));
    	    return $date->format('Y/m/d');  
        } else {
            return null;
        }   	
    }

    public function getSalary($stringSalary){
    	if(!empty($stringSalary)){
	        $sal = str_replace(array("\xC2\xA0"), " ", $stringSalary);
	        if(preg_match('#([1-9][0-9]{1,2}),[0-9]{1,2} -#',$sal, $minSal)){
	            return $minSal[1] . "000,00";
	        } else if (preg_match("#([1-9][0-9][\r\n\t\s]+[0-9]*,[0-9]{1,2}) -#", $sal, $minSal)){
	            return $minSal[1];
	        } else {
	            return null;
	        }
	    } else {
	    	return null;
	    }
    }
}

