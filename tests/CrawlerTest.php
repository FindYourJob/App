<?php

class CrawlerTest extends PHPUnit_Framework_TestCase
{
    public function testIsURLCrawled()
    {
        //Test sample
        $input = array(
            'https://cadres.apec.fr/MesOffres/RechercheOffres/ApecRechercheOffre.jsp?keywords=informatique',
            'https://cadres.apec.fr/offres-emploi-cadres/0_0_0_140634812W__________offre-d-emploi-chef-de-projet-informatique-industrielle-h-f.html?xtmc=informatique&xtnp=1&xtcr=1',
        );

        $crawler = new Crawler();

        foreach($input as $in){
            $output = $crawler->crawl($in);
            $this->assertEquals(false, empty($output));
        }
    }
}