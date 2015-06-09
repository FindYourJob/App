<?php
namespace App\Model;

class CrawlerModel
{

    static $scrapper;

    public function __construct($s)
    {
       self::$scrapper = $s;
    }

    public function crawl($url)
    {
        
        // is cURL installed yet?
        if (!function_exists('curl_init')){
            die('Sorry cURL is not installed!');
        }

        // Get cURL resource
        $curl = curl_init();

        // Set some options - we are passing in a useragent too here
        $curl_params = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Mozilla',
            CURLOPT_SSL_VERIFYPEER => false,
            //CURLOPT_PROXY => 'proxyweb.utc.fr:3128'
        );

        curl_setopt_array($curl, $curl_params);

        // Send the request & save response to $resp
        $output = curl_exec($curl);

        // Close request to clear up some resources
        curl_close($curl);

        return $output;
    }

    public function scrap($url)
    {
        echo 'Scrap<br/>';
        $result = $this->crawl($url);
        $this->scrapAction($result, $url);
    }

    public function scrapAction($string, $url)
    {
        self::$scrapper->scrap($string);
        self::$scrapper->setAttr('url', $url);
    }

    public function getScrapper()
    {
        return self::$scrapper;
    }

    public function insertIntoBdd($content)
    {
        echo 'BDD<br/>';
        BManager::getInstance()->insert($content);
    }
    
    public function insertIntoFile($c)
    {
        $content = $c; 
        $file = fopen(tempnam("/var/www/html/Back-end/web", "crawl"), 'a');
        $content = var_export($content, true );
        fputs($file, $content);
        fclose($file);
    }




}
