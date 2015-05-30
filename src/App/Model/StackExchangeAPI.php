<?php

namespace App\Model;

class StackExchangeAPI {

    protected $limit = 70000;

    public function getTechnos(){
        $page_number = 1;
        $technos_all = array();

        do{

            //$apiURL = "https://api.stackexchange.com/2.2/tags?page=".$page_number."&order=desc&sort=popular&site=stackoverflow&key=)OF05SNKLyoktuYp)Row9Q((";
            $apiURL = "https://api.stackexchange.com/2.2/tags?page=".$page_number."&order=desc&sort=popular&site=stackoverflow";

            $tmp = CrawlerModel::crawl($apiURL, array(CURLOPT_ENCODING => 'gzip'));

            $data = json_decode($tmp, true);

            $technos = $data['items'];


            $technos_all = array_merge($technos_all, $technos);

            var_dump($technos_all);
            var_dump(end($technos)["count"]);

            $page_number++;

        } while (end($technos)["count"] > $this->limit);

        foreach ($technos_all as $t)
        {
            echo ($t['name']." et ".$t['count']."<br/>");
            // insertion dans la BDD possible à ce niveau juste en faisant un insert de "$t['name']".
            // avec un ID autoincrémenté.

            DBManager::getInstance()->insertTechno($t['name']);
        }
    }
}
