<?php
require 'QueueManager.php';
require 'KafkaManager.php';

$name       = htmlspecialchars(trim($_POST['username'] ?? ''));
$date       = $_POST['date'] ?? '';
$route      = $_POST['route'] ?? 'city';
$audioguide = isset($_POST['audioguide']) ? 1 : 0;
$language   = $_POST['language'] ?? 'ru';

$data = [
    'name'       => $name,
    'date'       => $date,
    'route'      => $route,
    'audioguide' => $audioguide,
    'language'   => $language,
    'created_at' => date('Y-m-d H:i:s'),
];

// RabbitMQ
try {
    $q = new QueueManager();
    $q->publish($data);
} catch (\Throwable $e) {
    error_log("RabbitMQ publish error: " . $e->getMessage());
}

// Kafka
try {
    $k = new KafkaManager();
    $k->publish($data);
} catch (\Throwable $e) {
    error_log("Kafka publish error: " . $e->getMessage());
}

header("Location: index.php?published=1");
exit();