<?php
require_once __DIR__ . '/vendor/autoload.php';

use Kafka\Producer;
use Kafka\ProducerConfig;
use Kafka\Consumer;
use Kafka\ConsumerConfig;

class KafkaManager
{
    private $mainTopic  = 'lab7_topic';
    private $errorTopic = 'lab7_errors';

    public function publish(array $data, bool $isError = false): void
    {
        $config = ProducerConfig::getInstance();
        $config->setMetadataBrokerList('kafka:9092');
        $config->setRequiredAck(1);
        $config->setIsAsyn(false);

        $topic = $isError ? $this->errorTopic : $this->mainTopic;

        $producer = new Producer(function () use ($data, $topic) {
            return [[
                'topic' => $topic,
                'value' => json_encode($data, JSON_UNESCAPED_UNICODE),
                'key'   => '',
            ]];
        });
        $producer->send(true);
    }

    public function consume(callable $callback): void
    {
        $config = ConsumerConfig::getInstance();
        $config->setMetadataBrokerList('kafka:9092');
        $config->setGroupId('lab7_group');
        $config->setTopics([$this->mainTopic]);
        $config->setOffsetReset('earliest');

        $consumer = new Consumer();
        $consumer->start(function ($topic, $part, $message) use ($callback) {
            $data = json_decode($message['message']['value'], true);
            try {
                $callback($data);
            } catch (\Throwable $e) {
                // ШТРАФНОЕ: ошибка → во второй topic
                $data['error'] = $e->getMessage();
                $data['failed_at'] = date('Y-m-d H:i:s');
                $this->publish($data, true);
                file_put_contents('errors_kafka.log',
                    json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL,
                    FILE_APPEND);
            }
        });
    }
}