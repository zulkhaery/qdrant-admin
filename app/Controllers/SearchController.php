<?php

namespace App\Controllers;

use App\Services\QdrantService;
use App\Services\EmbeddingService;

class SearchController
{   
   
    public function searchVector()
    {
        $qdrant = new QdrantService();
        $collections = $qdrant->collections();
        $results = [];
        $collection = $_GET['collection'] ?? '';
        $query = $_GET['query'] ?? '';

        if (!empty($collection) && !empty($query)) {
            $embedding = new EmbeddingService();
            $vector = $embedding->embed($query);
            $response = $qdrant->search($collection, $vector);
            $results = $response['result'] ?? [];
        }

        return view('search-vector', [
            'title' => 'Search Vector',
            'collections' => $collections['result']['collections'] ?? [],
            'results' => $results,
            'collection' => $collection,
            'query' => $query
        ]);
    }

    public function payloadExplorer()
    {
        $qdrant = new QdrantService();

        $collections = $qdrant->collections();

        $collection = $_POST['collection'] ?? '';
        $filter = $_POST['filter'] ?? '';

        $results = [];

        if ($collection && $filter) {

            $decodedFilter = json_decode($filter, true);

            if ($decodedFilter) {

                $response = $qdrant->payloadFilter(
                    $collection,
                    $decodedFilter
                );

                $results = $response['result']['points'] ?? [];
            }
        }

        return view('payload-explorer', [
            'title' => 'Payload Explorer',
            'collections' => $collections['result']['collections'] ?? [],
            'collection' => $collection,
            'filter' => $filter,
            'results' => $results
        ]);
    }
}