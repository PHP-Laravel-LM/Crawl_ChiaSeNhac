<?php

namespace App\Http\Controllers;

use App\Criteria\SongCriteria;
use App\Repositories\SongRepository;
use Illuminate\Http\Request;
use App\Helpers\Crawler;

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

    public function saveSong(Request $request)
    {
        // Check if request is valid
        if (!$request->filled('url'))
            return json_encode([
                'status' => false
            ]);
        $urlSong = $request->input('url');
        // Save song to database
        $song = $this->songRepository->create([
            'url' => $urlSong
        ]);
        $result = [
            'status'    => true,
            'id'        => $song['id']
        ];
        return response(json_encode($result))->header('Content-Type', 'application/json');
    }

    public function crawlSong(Request $request)
    {
        // Check if url is valid
        if (!$request->filled('url')) 
            return json_encode([
                'status' => false
            ]);
        $urlSong = $request->input('url');
        // Using url to get info of song
        $crawler = new Crawler();
        $source = $crawler->getSourceFromURL($urlSong);
        $links = $crawler->getLinkSong($source);
        array_merge([
            'status'    => true
        ], $links);
        return response(json_encode($links))->header('Content-Type', 'application/json');
    }

    public function findSong(Request $request)
    {
        // Check if request is valid
        if (!$request->filled('name'))
            return json_encode([
                'status' => false
            ]);
        $nameSong = $request->input('name');
        $crawler = new Crawler();
        $searchResult = $crawler->findSong($nameSong);
        $result = [
            'status'    => true,
            'q'         => $searchResult[0]['q'],
            'href'      => $searchResult[0]['music']['data'][0]['music_link']
        ];
        return response(json_encode($result))->header('Content-Type', 'application/json');
    }

    public function getSong(Request $request, $id)
    {
        $song = $this->songRepository->find($id);
        return response(json_encode($song))->header('Content-Type', 'application/json');
    }
}