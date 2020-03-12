<?php

namespace App\Helpers\Crawler;

require './vendor/autoload.php';

use GuzzleHttp\Client;
use App\Helpers\Crawler\CrawlerZingMp3;
use App\Helpers\Crawler\CrawlerCSN;

abstract class Crawler
{
    private $client = null;

    public function __construct()
    {
        $this->client = new Client();
    }

    public static function routeCrawler(string $url)
    {
        $patternZing = '/zingmp3/';
        $patternCsn = '/chiasenhac/';
        if (preg_match($patternZing, $url)) {
            return new CrawlerZingMp3();
        }
        if (preg_match($patternCsn, $url)) {
            return new CrawlerCSN();
        }
    }

    protected function getSourceFromURL(string $urlSong, $params = [])
    {
        $request = $this->client->request('GET', $urlSong, $params);
        $response = $request->getBody();
        return $response->getContents();
    }

    abstract function getLinkDowloadSong(string $urlSong);
}
