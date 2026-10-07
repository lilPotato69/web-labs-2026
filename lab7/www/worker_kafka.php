<?php
require 'KafkaManager.php';

echo "👷 Kafka worker started (Ctrl+C для остановки)\n";
$k = new KafkaManager();

$k->consume(function ($data) {
    echo "📥 Kafka получил: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";

    if (empty($data['name'])) {
        throw new \Exception("Пустое имя");
    }

    sleep(1);

    file_put_contents('processed_kafka.log',
        json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
    echo "✅ Kafka обработал\n";
});