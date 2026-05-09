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

    public function collectionInfo(string $name): array
    {
        return $this->get('/collections/' . $name);
    }

    public function scroll(string $collection, int $limit = 1000): array
    {
        return $this->post("/collections/$collection/points/scroll", [
            'limit' => $limit,
            'with_payload' => true,
            'with_vector' => true
        ]);
    }

    public function insert(string $collection, int $id, array $vector, array $payload = []): array
    {
        return $this->put("/collections/$collection/points", [
            'points' => [
                [
                    'id' => $id,
                    'vector' => $vector,
                    'payload' => $payload
                ]
            ]
        ]);
    }

    public function createCollection(string $name, int $size, string $distance): array
    {
        return $this->put("/collections/$name", [
            'vectors' => [
                'size' => $size,
                'distance' => $distance
            ]
        ]);
    }

    public function search(string $collection, array $vector, int $limit = 1000): array
    {
        return $this->post("/collections/$collection/points/search", [
            'vector' => $vector,
            'limit' => $limit,
            'with_payload' => true,
            'with_vector' => true
        ]);
    }

    public function payloadFilter(string $collection, array $filter): array
    {
        return $this->post("/collections/$collection/points/scroll", [
            'filter' => $filter,
            'limit' => 20,
            'with_payload' => true,
            'with_vector' => false
        ]);
    }

    public function createPayloadIndex(string $collection, string $field, string $schema): array
    {
        return $this->put("/collections/$collection/index", [
            'field_name' => $field,
            'field_schema' => $schema
        ]);
    }

    public function deletePayloadIndex(string $collection, string $field): array
    {
        return $this->delete("/collections/$collection/index/$field");
    }

    public function deletePoint(string $collection, string|int $id): array
    {
        return $this->post("/collections/$collection/points/delete", [
            'points' => [
                is_numeric($id) ? (int) $id : $id
            ]
        ]);
    }

    public function deleteCollection(string $name): array
    {
        return $this->delete("/collections/$name");
    }

    public function getPoints(string $collection, int $limit = 100): array
    {
        $response = $this->post("/collections/$collection/points/scroll", [
            'limit' => $limit,
            'with_payload' => true,
            'with_vector' => true
        ]);

        return $response['result']['points'] ?? [];
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

    private function post(string $endpoint, array $payload): array
    {
        $ch = curl_init($this->host . $endpoint);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true) ?? [];
    }

    private function put(string $endpoint, array $payload): array
    {
        $ch = curl_init($this->host . $endpoint);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true) ?? [];
    }

    private function delete(string $endpoint): array
    {
        $ch = curl_init($this->host . $endpoint);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true) ?? [];
    }
}