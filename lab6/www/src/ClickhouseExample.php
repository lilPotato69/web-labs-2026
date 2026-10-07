<?php
namespace App;

use App\Helpers\ClientFactory;

class ClickhouseExample
{
    private $client;

    public function __construct()
    {
        $this->client = ClientFactory::make('http://clickhouse:8123/');
    }

    public function query(string $sql): string
    {
        $response = $this->client->post('', [
            'body'  => $sql,
            'query' => ['default_format' => 'JSON']
        ]);
        return $response->getBody()->getContents();
    }

    public function ensureTable(): void
    {
        $this->query("
            CREATE TABLE IF NOT EXISTS events (
                event_time DateTime DEFAULT now(),
                event_type String,
                user_name String,
                route String
            ) ENGINE = MergeTree()
            ORDER BY event_time
        ");
    }

    public function logEvent(string $type, string $name, string $route): void
    {
        $type = addslashes($type);
        $name = addslashes($name);
        $route = addslashes($route);
        $this->query("INSERT INTO events (event_type, user_name, route) VALUES ('$type', '$name', '$route')");
    }

    public function countEvents(): int
    {
        $json = json_decode($this->query("SELECT count() AS c FROM events"), true);
        return (int)($json['data'][0]['c'] ?? 0);
    }
}