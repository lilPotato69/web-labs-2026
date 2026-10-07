<?php
namespace App;

use App\Helpers\ClientFactory;

class ElasticExample
{
    private $client;

    public function __construct()
    {
        $this->client = ClientFactory::make('http://elasticsearch:9200/');
    }

    public function indexDocument(string $index, $id, array $data): string
    {
        $response = $this->client->put("$index/_doc/$id", [
            'json' => $data
        ]);
        return $response->getBody()->getContents();
    }

    public function search(string $index, array $query): array
    {
        $response = $this->client->get("$index/_search", [
            'json' => ['query' => $query]
        ]);
        $json = json_decode($response->getBody()->getContents(), true);
        return $json['hits']['hits'] ?? [];
    }

    public function count(string $index): int
    {
        $response = $this->client->get("$index/_count");
        $json = json_decode($response->getBody()->getContents(), true);
        return (int)($json['count'] ?? 0);
    }
}