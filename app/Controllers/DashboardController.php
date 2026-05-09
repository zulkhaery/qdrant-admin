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
        $estimatedBytes = $dimension * 4 * $totalVectors;
        $info = $qdrant->info();

        return view('home', [
            'title' => 'Dashboard',
            'stats' => [
                'qdrant_status' => 'Connected',
                'collections' => $collectionsCount,
                'total_vectors' => $totalVectors,
                'embedding_model' => $_ENV['EMBEDDING_MODEL'],
                'dimension' => $dimension,
                'estimated_storage' => $this->formatBytes($estimatedBytes),
                'qdrant_version' => $info['version'] ?? 'Unknown',
                'host' => $_ENV['QDRANT_HOST'],
            ]
        ]);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}