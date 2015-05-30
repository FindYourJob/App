<?php
namespace App\Model;

class CrawlerModel
{
    public static function crawl($url, $params = array())
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

        if(is_array($params))
            $curl_params = $curl_params + $params;

        curl_setopt_array($curl, $curl_params);

        // Send the request & save response to $resp
        $output = curl_exec($curl);

        // Close request to clear up some resources
        curl_close($curl);

        return $output;
    }
}
