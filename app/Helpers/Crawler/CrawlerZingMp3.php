<?php

namespace App\Helpers\Crawler;

use App\Helpers\Crawler\Crawler;
use App\Helpers\Crawler\CrawlerCSN;

class CrawlerZingMp3 extends Crawler
{

    public function getLinkDowloadSong(string $urlSong)
    {
        $info = [];
        $csnUrl = '';
        // Get name song from source url
        $namePattern = '/<title>(.+)<\/title>/';
        $source = $this->getSourceFromURL($urlSong);
        preg_match($namePattern, $source, $nameMatches);
        if (!isset($nameMatches) || sizeof($nameMatches) == 0) {
            return $info;
        }
        // Get csn url from name song
        $csnCrawler = new CrawlerCSN();
        $resultFind = $csnCrawler->findSong($nameMatches[1]);
        if (!isset($resultFind)) {
            return $info;
        }
        $csnUrl = $resultFind[0]['music']['data'][0]['music_link'];
        // Get link download from csn url
        if (strlen($csnUrl) > 0) {
            $info = $csnCrawler->getLinkDowloadSong($csnUrl);
        }
        return $info;
    }
}
