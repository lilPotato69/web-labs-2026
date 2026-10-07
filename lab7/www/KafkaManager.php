<?php

class KafkaManager
{
    private $broker    = 'kafka:9092';
    private $mainTopic = 'lab7_topic';
    private $errorTopic = 'lab7_errors';

    public function publish(array $data, bool $isError = false): void
    {
        $conf = new RdKafka\ProducerConf();
        $conf->set('metadata.broker.list', $this->broker);

        $producer = new RdKafka\Producer($conf);
        $topic = $producer->newTopic($isError ? $this->errorTopic : $this->mainTopic);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($data, JSON_UNESCAPED_UNICODE));
        $producer->flush(10000);
    }

    public function consume(callable $callback): void
    {
        $conf = new RdKafka\Conf();
        $conf->set('metadata.broker.list', $this->broker);
        $conf->set('group.id', 'lab7_group');
        $conf->set('auto.offset.reset', 'earliest');
        $conf->set('enable.auto.commit', 'false');

        $consumer = new RdKafka\KafkaConsumer($conf);
        $consumer->subscribe([$this->mainTopic]);

        echo "Ждём сообщения из топика {$this->mainTopic}...\n";

        while (true) {
            $message = $consumer->consume(120 * 1000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $data = json_decode($message->payload, true);
                    try {
                        $callback($data);
                        $consumer->commit();
                    } catch (\Throwable $e) {
                        $data['error'] = $e->getMessage();
                        $data['failed_at'] = date('Y-m-d H:i:s');
                        $this->publish($data, true);
                        file_put_contents('errors_kafka.log',
                            json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL,
                            FILE_APPEND);
                        $consumer->commit();
                    }
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    break;
                default:
                    echo "Ошибка Kafka: " . $message->errstr() . "\n";
                    break;
            }
        }
    }
}