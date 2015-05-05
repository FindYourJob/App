<?php
namespace App\Controller;

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
}
