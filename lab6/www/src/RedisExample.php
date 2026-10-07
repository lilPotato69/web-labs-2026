<?php
namespace App;

use Predis\Client;

class RedisExample
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'scheme' => 'tcp',
            'host'   => 'redis',
            'port'   => 6379,
        ]);
    }

    public function setValue(string $key, string $value): void
    {
        $this->client->set($key, $value);
    }

    public function getValue(string $key): ?string
    {
        return $this->client->get($key);
    }

    public function increment(string $key): int
    {
        return (int)$this->client->incr($key);
    }
}