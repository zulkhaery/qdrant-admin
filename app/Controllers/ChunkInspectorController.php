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
                    20
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

        view('chunk-inspector', [
            'title'              => 'Chunk Inspector',
            'collections'        => $collections,
            'selectedCollection' => $selectedCollection,
            'chunks'             => $chunks,
            'selectedChunk'      => $selectedChunk,
            'query'              => $query,
            'distanceMetric' => $distanceMetric,
        ]);
    }
}