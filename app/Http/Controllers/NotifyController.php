<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Phattarachai\LineNotify\Facade\Line;
use App\Services\CrawlerService;

use App\Models\HtmlCrawler;

class NotifyController extends Controller
{

    public function notify(Request $request){

        $crawlerService = new CrawlerService();
        $crawler = $crawlerService->getOriginalData('https://www.gck99.com.tw');


        $data = $crawler;
        $res = '';
        $html_crawler = HtmlCrawler::all();
        foreach($data as $key => $value){
            if($html_crawler[$key]['price'] != $value['price']){
                $res .= $value['title'] . ': ' . $value['price'] . ' => ' . $html_crawler[$key]['price'] . "\n";
                $crawler = HtmlCrawler::find($key+1);
                $crawler->price = $value['price'];
                $crawler->save();
            }
        }

        if(trim($res) > ''){

            Line::send("\n" . $res);

        }

    }

}
