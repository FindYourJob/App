<?php
namespace App\Model;

class CrawlerModel
{
    public function crawl($url)
    {
        $output = '';

        // is cURL installed yet?
        if (!function_exists('curl_init')){
            die('Sorry cURL is not installed!');
        }

        // Get cURL resource
        $curl = curl_init();

        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Mozilla',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_PROXY => "proxyweb.utc.fr:3128"
        ));

        // Send the request & save response to $resp
        $output = curl_exec($curl);

        // Close request to clear up some resources
        curl_close($curl);

        return $output;
    }
}
