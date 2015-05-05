<?php
namespace Base;

use App\Model\CrawlerModel;

class CrawlerTest extends \PHPUnit_Framework_TestCase
{
    public function testIsURLCrawled()
    {
        $crawler  = new CrawlerModel();

        $sample = array(
            'https://cadres.apec.fr/MesOffres/RechercheOffres/ApecRechercheOffre.jsp?keywords=informatique',
            'https://cadres.apec.fr/offres-emploi-cadres/0_0_4_142883706W__________offre-d-emploi-developpeur-en-informatique-h-f.html?xtmc=informatique&xtnp=1&xtcr=5',
            'https://cadres.apec.fr/liste-offres-emploi-cadres/5_4____________offre-d-emploi.html',
        );

        foreach($sample as $in) {
            $output = $crawler->crawl($in);
            $this->assertFalse(empty($output));
        }
    }
}
