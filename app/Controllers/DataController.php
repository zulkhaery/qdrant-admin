<?php

namespace App\Controllers;

use App\Services\QdrantService;
use App\Services\EmbeddingService;

class DataController
{   
    public function index()
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

    public function ingest()
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
        return view('ingest', [
            'title' => 'Document Ingestion',
            'collections' => $collections['result']['collections'] ?? []
            
        ]);
    }
}