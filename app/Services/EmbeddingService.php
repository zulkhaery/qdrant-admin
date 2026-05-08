<?php

namespace App\Services;

class EmbeddingService
{
    private string $url;

    public function __construct()
    {
        $this->url = $_ENV['OLLAMA_HOST']. '/api/embeddings';
    }

    public function dimension(): int
    {
        $vector = $this->embed('txt');

        return count($vector);
    }

    public function embed(string $text): array
    {
        $payload = [
            'model' => $_ENV['EMBEDDING_MODEL'],
            'prompt' => $text
        ];

        $ch = curl_init($this->url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        $result = json_decode($response, true);

        return $result['embedding'] ?? [];
    }
}