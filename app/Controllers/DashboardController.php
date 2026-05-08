<?php
namespace App\Controllers;

use App\Services\QdrantService;
use App\Services\EmbeddingService;

class DashboardController
{
    public function index()
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
}