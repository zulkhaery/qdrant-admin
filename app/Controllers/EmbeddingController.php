<?php

namespace App\Controllers;

use App\Services\QdrantService;
use App\Services\EmbeddingService;

class EmbeddingController
{   
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

        return view('embedding', [
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
}