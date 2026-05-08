<?php

namespace App\Controllers;

use App\Services\QdrantService;
use App\Services\EmbeddingService;

class CollectionController
{   
    public function index()
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

    public function add()
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

  

    public function schema()
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

    public function addIndex()
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

    public function deleteIndex()
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