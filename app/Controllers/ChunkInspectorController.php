<?php

namespace App\Controllers;

use App\Services\QdrantService;
use App\Services\EmbeddingService;

class ChunkInspectorController
{
    public function index()
    {
        $qdrant = new QdrantService();
        $embedding = new EmbeddingService();

  
        $collectionsResponse = $qdrant->collections();

        $collections = $collectionsResponse['result']['collections'] ?? [];

   
        $selectedCollection = $_GET['collection'] ?? null;

        $collectionInfo = null;
        $distanceMetric = null;

        if ($selectedCollection) {

            $collectionInfo = $qdrant->collectionInfo($selectedCollection);

            $distanceMetric =
                $collectionInfo['result']['config']['params']['vectors']['distance']
                ?? 'Unknown';
        }

        $query = trim($_GET['query'] ?? '');

        $chunks = [];

        if ($selectedCollection) {

            if (!empty($query)) {
                
                $vector = $embedding->embed($query);

                $searchResponse = $qdrant->search(
                    $selectedCollection,
                    $vector,
                    100
                );

                $chunks = $searchResponse['result'] ?? [];

            } else {
                $chunks = $qdrant->getPoints(
                    $selectedCollection,
                    100
                );
            }
        }

        
        $selectedId = $_GET['id'] ?? null;
        $selectedChunk = null;

        if (!empty($chunks)) {

            if ($selectedId) {

                foreach ($chunks as $chunk) {

                    if (($chunk['id'] ?? null) == $selectedId) {
                        $selectedChunk = $chunk;
                        break;
                    }
                }
            }

            if (!$selectedChunk) {
                $selectedChunk = $chunks[0];
            }
        }

        $page = max(1, (int)($_GET['page'] ?? 1));

        $perPage = 10;

        $totalChunks = count($chunks);

        $offset = ($page - 1) * $perPage;

        $chunks = array_slice($chunks, $offset, $perPage);

        $totalPages = ceil($totalChunks / $perPage);

        view('chunk-inspector', [
            'title'              => 'Chunk Inspector',
            'collections'        => $collections,
            'selectedCollection' => $selectedCollection,
            'chunks'             => $chunks,
            'selectedChunk'      => $selectedChunk,
            'query'              => $query,
            'distanceMetric'    => $distanceMetric,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
            'totalChunks' => $totalChunks,
        ]);
    }
}