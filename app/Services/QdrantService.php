<?php

namespace App\Services;

class QdrantService
{
    private string $host;

    public function __construct()
    {
        $this->host = $_ENV['QDRANT_HOST'];
    }

    public function collections(): array
    {
        return $this->get('/collections');
    }

    public function info(): array
    {
        return $this->get('/');
    }

    private function get(string $endpoint): array
    {
        $ch = curl_init($this->host . $endpoint);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true) ?? [];
    }

    public function collectionInfo(string $name): array
    {
        return $this->get('/collections/' . $name);
    }

    public function scroll( string $collection, int $limit = 20): array {

        return $this->post(
            "/collections/$collection/points/scroll",
            [
                'limit' => $limit,
                'with_payload' => true,
                'with_vector' => true
            ]
        );
    }

    private function post( string $endpoint, array $payload): array {

        $ch = curl_init(
            $this->host . $endpoint
        );

        curl_setopt_array($ch, [

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,

            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],

            CURLOPT_POSTFIELDS =>
                json_encode($payload)
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true) ?? [];
    }
}