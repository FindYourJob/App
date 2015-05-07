<?php
namespace Base;

use App\Model\Apec\ApecScrapper;

class ApecScrapperTest extends \PHPUnit_Framework_TestCase
{
    public function testApecRegexIsFilled()
    {
        $scrapper  = new ApecScrapper();



        foreach($scrapper->getAttributes() as $attr) {

            $this->assertFalse(empty($attr['regex']));
        }
    }

    public function testApecIsScrapped()
    {
        $scrapper  = new ApecScrapper();

        $sample = array(
            'text src'
        );

        foreach($sample as $in) {
            $scrapper->scrap($in);
            $attr = $scrapper->getAttributes();
            $this->assertFalse(empty($attr));

            foreach($attr as $a){
                $this->assertFalse(empty($a['result']));
            }
        }
    }
}
