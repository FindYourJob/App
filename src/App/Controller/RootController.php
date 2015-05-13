<?php
namespace App\Controller;

use App\Model\Apec\ApecScrapper;
use App\Model\DBManager;
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
        $crawler  = new CrawlerModel();
        $content = $crawler->crawl('https://cadres.apec.fr/MesOffres/RechercheOffres/ApecRechercheOffre.jsp?keywords=informatique');
        return $this->render('crawl', array('content' => $content));
    }

    public function scrapAction()
    {
        $scrapper  = new ApecScrapper();
        $scrapper->scrap(utf8_encode(file_get_contents('./sample.html')));
        $content = $scrapper->getAttributes();
        var_dump($content);
        return $this->render('scrap', array('content' => $content));
    }

    public function connectAction()
    {
        $DB = DBManager::getInstance();
        return $this->render('connect');
    }
}
