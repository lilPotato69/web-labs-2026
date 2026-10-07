<?php
require 'QueueManager.php';

echo "👷 RabbitMQ worker started (Ctrl+C для остановки)\n";
$q = new QueueManager();

$q->consume(function ($data) {
    echo "📥 RabbitMQ получил: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";

    // Искусственная проверка — если имя пустое, шлём в очередь ошибок
    if (empty($data['name'])) {
        throw new \Exception("Пустое имя");
    }

    sleep(1); // имитация тяжёлой работы

    file_put_contents('processed_rabbit.log',
        json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
    echo "✅ RabbitMQ обработал\n";
});