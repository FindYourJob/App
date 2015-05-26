<?php

namespace App\Model\Monster;

use App\Model\CrawlerModel;
use App\Model\DBManager;


class MonsterCrawler {
    
    // get a html page
    public function crawlAction($url)
    {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $res = curl_exec($ch);
      curl_close($ch);
      return $res;
    }
    
    // scan all the jobs offer from an url
    public function crawl($u)
    {
        
        $var = true;
        $url = $u;
        while($var){
            $result = CrawlerModel::crawl($url);
            if(!preg_match('#<a class=\'box nextLink fnt5\' href=\'(.*)\' rel=\'Suivant\'#', $result, $res)){
                $var = false;
            }
            preg_match_all('#href=(.*?)>#', $result, $urls);  
            foreach ($urls[0] as $element){
	        $element = preg_replace('#href="#', '', $element);
	        $element = preg_replace('#">#', '', $element);
                if ( preg_match('#jobPosition#', $element, $t)){
                   //echo $element;
                   $this->scrap($element);
                    die();
                }
            }
            $res = preg_replace('#<a class=\'box nextLink fnt5\' href=\'#', '', $res[0]);
            $res = preg_replace('#\' rel=\'Suivant\'#', '', $res);
            $url = $res;
            $var = false;
        }
        
    }

   //scrap a specific offer 
    public function scrap($url)
    {
        echo 'Scrap';
        $result = $this->crawlAction($url);
        //the regex only works for non specific template
        if(preg_match('#<h2>Outils#', $result, $t)){
            $fp_fichier = fopen('crawlfile', 'a');
            fputs($fp_fichier, $result);
            $this->scrapAction('crawlfile');
        } else 
            return;
    } 



    public function scrapAction($file)
    {
        $scrapper  = new MonsterScrapper();
        $scrapper->scrap(utf8_encode(file_get_contents($file)));
        $content = $scrapper->getAttributes();
        echo 'BDD';
        DBManager::getInstance()->insert($content);
        // To test change path
        /*$file = fopen(tempnam("/var/www/html/Back-end/web", "crawl"), 'a');
        $content = var_export($content, true );
        fputs($file, $content);
        fclose($file);*/
        //die();
    }
}

