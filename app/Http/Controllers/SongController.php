<?php

namespace App\Http\Controllers;

use App\Helpers\Crawler\Crawler;
use App\Helpers\Crawler\CrawlerCSN;
use App\Repositories\SongRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SongController extends Controller
{

    protected $songRepository;

    public function __construct(SongRepository $songRepository)
    {
        $this->songRepository = $songRepository;
    }

    public function index()
    {
        return false;
    }

    public function crawlSong(Request $request)
    {
        // Check if url is valid
        if (!$request->filled('url')) {
            return json_encode([
                'status' => false
            ]);
        }
        $urlSong = $request->input('url');
        // Get type url (csn or zingmp3) and start download
        $links = Cache::store('file')->get($urlSong, []);
        if (sizeof($links) == 0) {
            $zingPattern = '/zingmp3/';
            $csnPattern = '/chiasenhac/';
            $crawler = Crawler::routeCrawler($urlSong);
            $links = $crawler->getLinkDowloadSong($urlSong);
            Cache::store('file')->add($urlSong, $links, 120);
        }
        $result = array_merge([
            'status'    => true
        ], $links);
        return response(json_encode($result))->header('Content-Type', 'application/json');
    }

    public function findSong(Request $request)
    {
        // Check if request is valid
        if (!$request->filled('name')) {
            return json_encode([
                'status' => false
            ]);
        }
        $nameSong = $request->input('name');
        $crawler = new CrawlerCSN();
        $searchResult = $crawler->findSong($nameSong);
        $result = [
            'status'    => true,
            'data'      => [
                'q'         => $searchResult[0]['q'],
                'href'      => $searchResult[0]['music']['data'][0]['music_link']
            ]
        ];
        return response(json_encode($result))->header('Content-Type', 'application/json');
    }

    public function getSong(Request $request, $id)
    {
        $song = $this->songRepository->find($id);
        return response(json_encode($song))->header('Content-Type', 'application/json');
    }

    public function saveSong(Request $request)
    {
        // Check if request is valid
        if (!$request->filled('url')) {
            return json_encode([
                'status' => false
            ]);
        }
        $urlSong = $request->input('url');
        // Save song to database
        $song = $this->songRepository->create([
            'url' => $urlSong
        ]);
        $result = [
            'status'    => true,
            'data'        => [
                'id' => $song['id']
            ]
        ];
        return response(json_encode($result))->header('Content-Type', 'application/json');
    }
}
