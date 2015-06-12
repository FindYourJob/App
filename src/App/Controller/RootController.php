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

        foreach($result as $key => $value){
            foreach($value as $k => $v)
                if($k == 'technos')
                    $result[$key][$k] = json_decode($v);
        }

        return $this->app->json($result);
    }

    public function getJobAdvertsWithTechnosAction($limit)
    {
        $DB = DBManager::getInstance();
        $result = $DB->select('SELECT * FROM jobs WHERE technos != `` LIMIT :limit', array(':limit' => $limit));
        return $this->app->json($result);
    }

    public function getStatsEntrepriseTechnoAction($limit)
    {
        $DB = DBManager::getInstance();
        $result = $DB->select('SELECT * FROM jobs GROUP BY company LIMIT :limit', array(':limit' => $limit));
        return $this->app->json($result);
    }

    public function getJobAdvertsLocatedAction($limit)
    {
        $DB = DBManager::getInstance();
        $result = $DB->select("SELECT * FROM jobs WHERE `long` is not null AND `lat` is not null LIMIT :limit", array(':limit' => $limit));
        return $this->app->json($result);
    }

    public function getNbAdvertsByCompanyAction($limit)
    {
        $DB = DBManager::getInstance();
        $result = $DB->select("SELECT company, COUNT(id) as nbAdverts FROM jobs GROUP BY company ORDER BY nbAdverts ASC LIMIT :limit", array(':limit' => $limit));
        return $this->app->json($result);
    }

    public function getNbAdvertsByTechnosAction($limit)
    {
        $DB = DBManager::getInstance();
        $result = $DB->select("SELECT name, COUNT(idTechno) as nbAdverts FROM linkedToTechno GROUP BY idTechno ORDER BY nbAdverts ASC LIMIT :limit", array(':limit' => $limit));
        return $this->app->json($result);
    }

    public function getNbAdvertsCompanyByTechnosAction($company, $limit)
    {
        $DB = DBManager::getInstance();
        $result = $DB->select("SELECT name, COUNT(idTechno) as nbAdverts FROM linkedToTechno ltt, jobs j WHERE ltt.idJob = j.id AND j.company = :company GROUP BY idTechno ORDER BY nbAdverts ASC LIMIT :limit", array(':company' => array($company, \PDO::PARAM_STR), ':limit' => $limit));
        return $this->app->json($result);
    }

    public function getNbAdvertsCompanyByWagesAction($company, $min, $max, $pad, $limit)
    {
        $DB = DBManager::getInstance();
        $result = $DB->select("SELECT COUNT(id) as nbAdverts FROM jobs WHERE company = :company AND wage >= :min AND wage <= :max GROUP BY wage div :pad ORDER BY nbAdverts ASC LIMIT :limit", array(':company' => array($company, \PDO::PARAM_STR), ':min' => array($min, \PDO::PARAM_INT), ':max' => array($max, \PDO::PARAM_INT), ':pad' => array($pad, \PDO::PARAM_INT), ':limit' => $limit));
        return $this->app->json($result);
    }

    public function getAverageWagesByCompanyAction($limit)
    {
        $DB = DBManager::getInstance();
        $result = $DB->select("SELECT company, AVG(wage) as wage FROM jobs WHERE id NOT IN (SELECT id FROM jobs WHERE wage = 0) GROUP BY company ORDER BY wage ASC LIMIT :limit", array(':limit' => $limit));
        return $this->app->json($result);
    }

    public function populateCitiesAction()
    {
        $DB = DBManager::getInstance();
        $DB->populateCities();
        return $this->render('populate');
    }

}
