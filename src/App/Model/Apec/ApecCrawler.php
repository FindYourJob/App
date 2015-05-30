<?php

namespace App\Model\Apec;

use App\Model\CrawlerModel;
use App\Model\DBManager;


class ApecCrawler {


    // scan all the jobs offer from an url
    public function crawl($u)
    {
        $limit = 5;
        $i = 0;
        $var = true;
        $url = $u;
        while($var){
            //echo 'URL:'.$url.'<br/>';
            $result = CrawlerModel::crawl($url);
            if(!preg_match('#<a href="([^"]*?)" class="lastItem">Suivante</a>#', $result, $res)){
                $var = false;
            }
            //var_dump($res);
            preg_match_all('#href="/offres-emploi-cadres/(.*?)"#', $result, $urls);
            foreach ($urls[0] as $element){
                $element = preg_replace('#href="#', '', $element);
                $element = preg_replace('#"#', '', $element);
                if ( preg_match('#xtcr#', $element, $t)){
                    //echo $element;
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

