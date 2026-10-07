<?php
require 'vendor/autoload.php';

use App\RedisExample;
use App\ElasticExample;
use App\ClickhouseExample;

$name       = htmlspecialchars(trim($_POST['username'] ?? 'Без имени'));
$date       = $_POST['date'] ?? date('Y-m-d');
$route      = $_POST['route'] ?? 'city';
$audioguide = isset($_POST['audioguide']) ? 1 : 0;
$language   = $_POST['language'] ?? 'ru';

$data = [
    'name'       => $name,
    'date'       => $date,
    'route'      => $route,
    'audioguide' => $audioguide,
    'language'   => $language,
    'created_at' => date('Y-m-d H:i:s')
];

// 1. Redis — кэш последней записи + счётчик
$redis = new RedisExample();
$redis->setValue('last_excursion', json_encode($data, JSON_UNESCAPED_UNICODE));
$redis->increment('excursion_count');

// 2. Elasticsearch — индексируем запись (ВАРИАНТ 8: поиск)
$elastic = new ElasticExample();
$docId = time() . '_' . rand(1000, 9999);
$elastic->indexDocument('excursions', $docId, $data);

// 3. ClickHouse — лог события
$click = new ClickhouseExample();
$click->ensureTable();
$click->logEvent('excursion_created', $name, $route);

header("Location: index.php");
exit();