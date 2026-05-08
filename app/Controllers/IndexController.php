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

    public function addCollection()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = $_POST['name'] ?? '';
            $size = (int) ($_POST['size'] ?? 768);
            $distance = $_POST['distance'] ?? 'Cosine';

            $qdrant = new QdrantService();

            $qdrant->createCollection($name, $size, $distance);

            header('Location: /collections');
            exit;
        }

        return view('add-collection', [
            'title' => 'Add Collection'
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
        $qdrant = new QdrantService();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $collection = $_POST['collection'] ?? 'my_docs';
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';

            if (!empty($_FILES['pdf']['tmp_name'])) {

                $parser = new \Smalot\PdfParser\Parser();

                $pdf = $parser->parseFile(
                    $_FILES['pdf']['tmp_name']
                );

                $content = $pdf->getText();

            }

            
            $embedding = new EmbeddingService();
            $chunks = chunkText($content);

            foreach ($chunks as $index => $chunk) {

                $vector = $embedding->embed($chunk);

                if (empty($vector)) {
                    continue;
                }

                $payload = [
                    'title' => $title,
                    'content' => $chunk,
                    'chunk_index' => $index + 1,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $qdrant->insert($collection, rand(1, 999999999), $vector,$payload);
            }

            header('Location: /view-data?collection='.$collection);
            exit;
        }

        $collections = $qdrant->collections(); 
        return view('input-data', [
            'title' => 'Input Data',
            'collections' => $collections['result']['collections'] ?? []
            
        ]);
    }

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

    public function embeddingTest()
    {
        $textA = $_POST['text_a'] ?? '';
        $textB = $_POST['text_b'] ?? '';

        $vectorA = [];
        $vectorB = [];

        $similarity = null;
        $method = $_POST['method'] ?? 'cosine';

        if ($textA && $textB) {

            $embedding = new EmbeddingService();

            $vectorA = $embedding->embed($textA);
            $vectorB = $embedding->embed($textB);

            if ($vectorA && $vectorB) {
               $similarity = match($method) {
                    'dot' => $this->dotProduct($vectorA, $vectorB),
                    'euclid' => $this->euclideanDistance($vectorA, $vectorB),
                    default => $this->cosineSimilarity($vectorA, $vectorB)
                };
            }
        }

        return view('embedding-test', [
            'title' => 'Embedding Test',
            'textA' => $textA,
            'textB' => $textB,
            'vectorA' => $vectorA,
            'vectorB' => $vectorB,
            'similarity' => $similarity,
            'method' => $method
        ]);
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0;
        $normA = 0;
        $normB = 0;

        foreach ($a as $i => $value) {

            $dot += $value * $b[$i];
            $normA += $value * $value;
            $normB += $b[$i] * $b[$i];
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    private function dotProduct(array $a, array $b): float
    {
        $dot = 0;

        foreach ($a as $i => $value) {
            $dot += $value * $b[$i];
        }

        return $dot;
    }

    private function euclideanDistance(array $a, array $b): float
    {
        $sum = 0;

        foreach ($a as $i => $value) {
            $sum += pow($value - $b[$i], 2);
        }

        return sqrt($sum);
    }

    public function collectionSchema()
    {
        $qdrant = new QdrantService();

        $collections = $qdrant->collections();

        $collection = $_GET['collection'] ?? '';

        $info = [];
        $payloadFields = [];
        $payloadSchema = [];

        if ($collection) {

            $info = $qdrant->collectionInfo($collection);

            $points = $qdrant->scroll($collection, 1);

            $firstPoint = $points['result']['points'][0] ?? null;

            $payloadSchema = $info['result']['payload_schema'] ?? [];

            if ($firstPoint) {

                foreach ($firstPoint['payload'] as $key => $value) {

                    $payloadFields[] = [
                        'field' => $key,
                        'type' => gettype($value)
                    ];
                }
            }
        }


        return view('collection-schema', [
            'title' => 'Collection Schema',
            'collections' => $collections['result']['collections'] ?? [],
            'collection' => $collection,
            'info' => $info,
            'payloadFields' => $payloadFields,
            'payloadSchema' => $payloadSchema
        ]);
    }

    public function createPayloadIndex()
    {
        $collection = $_POST['collection'] ?? '';
        $field = $_POST['field'] ?? '';
        $schema = $_POST['schema'] ?? 'keyword';

        if ($collection && $field) {

            $qdrant = new QdrantService();

            $qdrant->createPayloadIndex(
                $collection,
                $field,
                $schema
            );
        }

        header(
            'Location: /collection-schema?collection=' . $collection
        );

        exit;
    }

    public function deletePayloadIndex()
    {
        $collection = $_POST['collection'] ?? '';
        $field = $_POST['field'] ?? '';

        if ($collection && $field) {

            $qdrant = new QdrantService();

            $qdrant->deletePayloadIndex(
                $collection,
                $field
            );
        }

        header(
            'Location: /collection-schema?collection=' . $collection
        );

        exit;
    }
}