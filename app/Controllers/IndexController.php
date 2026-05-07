<?php

namespace App\Controllers;

use App\Services\QdrantService;
use App\Services\EmbeddingService;

class IndexController
{
    public function dashboard()
    {
        $qdrant = new QdrantService();
        $embedding = new EmbeddingService();
        $collections = $qdrant->collections();
        $collectionsCount = count($collections['result']['collections'] ?? []);
        $totalVectors = 0;

        foreach ($collections['result']['collections'] ?? [] as $collection) {
            $info = $qdrant->collectionInfo($collection['name']);
            $totalVectors += $info['result']['points_count'] ?? 0;
        }

        $dimension = $embedding->dimension();
        $info = $qdrant->info();

        return view('home', [
            'title' => 'Dashboard',
            'stats' => [
                'qdrant_status' => 'Connected',
                'collections' => $collectionsCount,
                'total_vectors' => $totalVectors,
                'embedding_model' => 'nomic-embed-text',
                'dimension' => $dimension,
                'qdrant_version' => $info['version'] ?? 'Unknown',
                'host' => $_ENV['QDRANT_HOST'],
            ]
        ]);
    }

    public function collections()
    {
        $qdrant = new QdrantService();
        $collections = $qdrant->collections();
        $items = [];

        foreach ($collections['result']['collections'] ?? [] as $collection) {
            $info = $qdrant->collectionInfo($collection['name']);
            $items[] = [
                'name' => $collection['name'],
                'vectors' => $info['result']['points_count'] ?? 0,
                'dimension' => $info['result']['config']['params']['vectors']['size'] ?? '-',
                'distance' =>  $info['result']['config']['params']['vectors']['distance'] ?? '-',
                'status' => $info['result']['status']  ?? 'unknown',
            ];
        }

        return view('collections', [
            'title' => 'Collections',
            'collections' => $items
        ]);
    }

    public function viewData()
    {
        $qdrant = new QdrantService();
        $collections = $qdrant->collections();
        $collectionName = $_GET['collection'] ?? null;
        $points = [];

        if ($collectionName) {
            $response = $qdrant->scroll(
                $collectionName
            );

            $points = $response['result']['points'] ?? [];
        }

        return view('view-data', [
            'title' => 'View Data',
            'collections' => $collections['result']['collections']  ?? [],
            'selectedCollection' => $collectionName,
            'points' => $points
        ]);
    }

    public function inputData()
    {
        return view('input-data', ['title' => 'Input Data']);
    }

    public function searchVector()
    {
        return view('home', [
            'title' => 'Search Vector',
        ]);
    }

    public function hybridSearch()
    {
        return view('home', [
            'title' => 'Hybrid Search'
        ]);
    }

    public function payloadExplorer()
    {
        return view('home', [
            'title' => 'Payload Explorer'
        ]);
    }

    public function embeddingTest()
    {
        return view('home', [
            'title' => 'Embedding Test'
        ]);
    }

    public function collectionSchema()
    {
        return view('home', [
            'title' => 'Collection Schema'
        ]);
    }
}