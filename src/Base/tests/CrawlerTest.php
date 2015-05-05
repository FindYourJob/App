<?php
namespace Base;

use App\Model\CrawlerModel;

class CrawlerTest extends \PHPUnit_Framework_TestCase
{
    public function testIsURLCrawled()
    {
        $crawler  = new CrawlerModel();;
        $output = $crawler->crawl('blabla');

        $this->assertFalse(empty($output));
    }
}
