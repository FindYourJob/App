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
            $this->scrapAction($result);
        } else 
            return;
    }

    public function scrapAction($string)
    {
        $scrapper  = new MonsterScrapper();
        $scrapper->scrap(utf8_encode($string));
        $content = $scrapper->getAttributes();
        DBManager::getInstance()->insert($content);
    }
}

