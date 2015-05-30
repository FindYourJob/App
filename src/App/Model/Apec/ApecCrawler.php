<?php

namespace App\Model\Apec;

use App\Model\CrawlerModel;
use App\Model\DBManager;


class ApecCrawler {

    
    // scan all the jobs offer from an url
    public function crawl($u)
    {
        
        $var = true;
        $url = $u;
        while($var){
            $result = CrawlerModel::crawl($url);
            if(!preg_match('#<a href="/offres-emploi-cadres/(.*)"#', $result, $res)){
                $var = false;
            }
            preg_match_all('#href="/offres-emploi-cadres/(.*?)"#', $result, $urls);
            foreach ($urls[0] as $element){
	        $element = preg_replace('#href="#', '', $element);
	        $element = preg_replace('#"#', '', $element);
                var_dump($element);
                if ( preg_match('#xtcr#', $element, $t)){
                   //echo $element;
                   $this->scrap('https://cadres.apec.fr'.$element);
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
        echo 'Scrap<br/>';
        $result =  CrawlerModel::crawl($url);

        //the regex only works for non specific template
        $fp_fichier = fopen('crawlfile', 'a');
        fputs($fp_fichier, $result);
        $this->scrapAction('crawlfile');
    } 



    public function scrapAction($file)
    {
        $scrapper  = new ApecScrapper();
        $scrapper->scrap(utf8_encode(file_get_contents($file)));
        $content = $scrapper->getAttributes();
        echo 'BDD<br/>';
        DBManager::getInstance()->insert($content);
        // To test change path
        /*$file = fopen(tempnam("/var/www/html/Back-end/web", "crawl"), 'a');
        $content = var_export($content, true );
        fputs($file, $content);
        fclose($file);*/
        //die();
    }
}

