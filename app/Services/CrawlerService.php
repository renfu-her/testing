<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use DOMDocument;
use DOMXPath;

use App\Models\HtmlCrawler;

class CrawlerService
{


    public function getOriginalData(string $url)
    {

        $httpClient = new Client();

        $response = $httpClient->get($url);

        $htmlString = (string) $response->getBody();

        // HTML is often wonky, this suppresses a lot of warnings
        libxml_use_internal_errors(true);

        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);

        $xpath = new DOMXPath($doc);

        $table = $xpath->evaluate('//*[@id="box-1"]/div[3]/div/div[@class="caption-yellow"]');
        $table2 = $xpath->evaluate('//*[@id="box-1"]/div[3]/div/div[@class="column-yellow"]');

        $res = [];
        $idx = 0;
        foreach($table as $row => $value)
        {
            if($row < 5){
                $title = $value->textContent;
                preg_match_all('!\d+!', $table2[$row]->textContent, $matches);
                $price = $matches[0][0];

                $data = [
                    'title' => $title,
                    'price' => $price
                ];
                array_push($res, $data);
            }
        }

        $table = $xpath->evaluate('//*[@id="box-1"]/div[4]/div/div[@class="caption-blue"]');
        $table2 = $xpath->evaluate('//*[@id="box-1"]/div[4]/div/div[@class="column-blue"]');

        $idx = 0;
        foreach($table as $row => $value)
        {
            if($row < 4){
                $title = $value->textContent;
                preg_match_all('!\d+!', $table2[$row]->textContent, $matches);
                $price = $matches[0][0];

                $data = [
                    'title' => $title,
                    'price' => $price
                ];
                array_push($res, $data);
            }
        }

        // initialize data array
        $html_crawler = HtmlCrawler::all();
        if($html_crawler->count() == 0){
            foreach($res as $key => $value){
                $html = new HtmlCrawler();
                $html->title= $value['title'];
                $html->price = $value['price'];
                $html->save();
            }
        }

        return $res;
    }
}
