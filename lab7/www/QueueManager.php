<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use GuzzleHttp\Client;

class QueueManager
{
    private $connection;
    private $channel;
    private $mainQueue  = 'lab7_queue';
    private $errorQueue = 'lab7_errors';
    private $user = 'lab7_user';
    private $pass = 'lab7_pass';

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('rabbitmq', 5672, $this->user, $this->pass);
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->mainQueue, false, true, false, false);
        $this->channel->queue_declare($this->errorQueue, false, true, false, false);
    }

    public function publish(array $data, bool $isError = false): void
    {
        $queue = $isError ? $this->errorQueue : $this->mainQueue;
        $msg = new AMQPMessage(
            json_encode($data, JSON_UNESCAPED_UNICODE),
            ['delivery_mode' => 2]
        );
        $this->channel->basic_publish($msg, '', $queue);
    }

    public function consume(callable $callback): void
    {
        $this->channel->basic_qos(null, 1, null);

        $this->channel->basic_consume(
            $this->mainQueue, '', false, false, false, false,
            function ($msg) use ($callback) {
                $data = json_decode($msg->body, true);
                try {
                    $callback($data);
                    $msg->ack();
                } catch (\Throwable $e) {
                    $data['error'] = $e->getMessage();
                    $data['failed_at'] = date('Y-m-d H:i:s');
                    $this->publish($data, true);
                    file_put_contents('errors_rabbit.log',
                        json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL,
                        FILE_APPEND);
                    $msg->ack();
                }
            }
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function stats(): array
    {
        $client = new Client([
            'base_uri'    => 'http://rabbitmq:15672',
            'auth'        => [$this->user, $this->pass],
            'timeout'     => 3.0,
            'http_errors' => false,
        ]);

        $result = ['main' => 0, 'errors' => 0];
        foreach (['lab7_queue' => 'main', 'lab7_errors' => 'errors'] as $q => $key) {
            $r = $client->get("/api/queues/%2F/$q");
            if ($r->getStatusCode() === 200) {
                $json = json_decode($r->getBody()->getContents(), true);
                $result[$key] = (int)($json['messages_ready'] ?? 0);
            }
        }
        return $result;
    }
}