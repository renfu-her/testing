<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CrawlerWeb extends Command
{
    protected $signature = 'CrawlerWeb';
    protected $description = '爬網頁排程';

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::get('/notify');
    }
}
