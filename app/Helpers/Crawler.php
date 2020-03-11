<?php

namespace App\Helpers;

require './vendor/autoload.php';
require './app/Helpers/simple_html_dom.php';

use GuzzleHttp\Client;

use function App\Helper\str_get_html;

class Crawler
{
    private $client = null;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getSourceFromURL(string $urlSong, $params = [])
    {
        $request = $this->client->request('GET', $urlSong, $params);
        $response = $request->getBody();
        return $response->getContents();
    }

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

    public function getLinkSong($source) 
    {
        $info = [];
        $html = str_get_html($source);
        // Get info song
        $info['name'] = $html->find('.card-title')[2]->plaintext;
        // Get link song in block
        $block_downloadFile = $html->find('.download_item');
        $info['links'] = [];
        foreach ($block_downloadFile as $file)
        {
            $quality_block = $file->last_child();
            $quality = 'unknown';
            if ($quality_block->tag === 'span')
                $quality = $quality_block->plaintext;
            $href = $file->href;
            array_push($info['links'], [
                'quality'   => $quality,
                'href'      => $href
            ]);
        }
        return $info;
    }
}