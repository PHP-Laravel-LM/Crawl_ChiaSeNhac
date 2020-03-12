<?php

namespace App\Helpers\Crawler;

require_once './app/Helpers/simple_html_dom.php';

use App\Helpers\Crawler\Crawler;
use function App\Helper\str_get_html;

class CrawlerCSN extends Crawler
{

    public function findSong(string $name)
    {
        $urlRequest = 'https://chiasenhac.vn/search/real';
        return json_decode($this->getSourceFromURL($urlRequest, [
            'query' => [
                'q'         => $name,
                'type'      => 'json',
                'rows'      => 1,
                'view_all'  => 'false'
            ]
        ]), true);
    }

    public function getLinkDowloadSong(string $urlSong)
    {
        $info = [];
        $source = $this->getSourceFromURL($urlSong);
        $html = str_get_html($source);
        // Get info song
        $info['name'] = $html->find('.card-title')[2]->plaintext;
        // Get link song in block
        $block_downloadFile = $html->find('.download_item');
        $info['links'] = [];
        foreach ($block_downloadFile as $file) {
            $quality_block = $file->last_child();
            $quality = 'M4A 32kbps';
            if ($quality_block->tag === 'span') {
                $quality = $quality_block->plaintext;
            }
            $href = $file->href;
            array_push($info['links'], [
                'quality'   => $quality,
                'href'      => $href
            ]);
        }
        return $info;
    }
}