<?php
namespace App\Controller;

use App\Model\Apec\ApecCrawler;
use App\Model\Apec\ApecScrapper;
use App\Model\DBManager;
use App\Model\Monster\MonsterCrawler;
use App\Model\StackExchangeAPI;
use App\Model\TechnoToujoursPareil;
use Base\Controller\Controller;
use App\Model\CrawlerModel;

class RootController extends Controller
{
    public function helloAction($name)
    {
        return $this->render('hello', array('name' => $name));
    }

    public function crawlAction()
    {
        $crawler  = new MonsterCrawler();
        $crawler->crawl('http://offres.monster.fr/rechercher/Informatique-Technologies_4?pg=1');
        return $this->render('crawl', array('content' => ''));
    }

    public function apecCrawlAction()
    {
        $crawler  = new ApecCrawler();
        $crawler->crawl('https://cadres.apec.fr/MesOffres/RechercheOffres/ApecRechercheOffre.jsp?keywords=informatique');
        return $this->render('crawl', array('content' => ''));
    }

    public function scrapAction()
    {
        $scrapper  = new ApecScrapper();
        $scrapper->scrap(utf8_encode(file_get_contents('./sample.html')));
        $content = $scrapper->getAttributes();
        var_dump($content);
        return $this->render('scrap', array('content' => $content));
    }

    public function scrapTechnosAction()
    {
        $api = new StackExchangeAPI();
        $api->getTechnos();
        return $this->render('okay');
    }

    public function getTechnosAction()
    {
        $string = "Bonjour j'aime bien le C++ et le javascript";
        $result = TechnoToujoursPareil::getInstance()->whatTechnosExist($string);
        return $this->app->json($result);
    }

    public function connectAction()
    {
        $DB = DBManager::getInstance();
        return $this->render('connect');
    }

    public function getJobAdvertsAction($limit)
    {
        $DB = DBManager::getInstance();
        $result = $DB->select('SELECT * FROM jobs LIMIT :limit', array(':limit' => $limit));
        return $this->app->json($result);
    }

    public function getJobAdvertsWithTechnosAction($limit)
    {
        $DB = DBManager::getInstance();
        $result = $DB->select('SELECT * FROM jobs WHERE technos != `` LIMIT :limit', array(':limit' => $limit));
        return $this->app->json($result);
    }

    public function getJobAdvertsLocatedAction($limit)
    {
        $DB = DBManager::getInstance();
        $result = $DB->select("SELECT * FROM jobs WHERE `long` is not null AND `lat` is not null LIMIT :limit", array(':limit' => $limit));
        return $this->app->json($result);
    }

    public function populateCitiesAction()
    {
        $DB = DBManager::getInstance();
        $DB->populateCities();
        return $this->render('populate');
    }

}
