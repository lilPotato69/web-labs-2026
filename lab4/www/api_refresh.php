<?php
session_start();
require_once 'ApiClient.php';

header('Content-Type: application/json; charset=utf-8');

$api = new ApiClient();
$url = 'https://api.opentripmap.com/0.1/en/places/radius'
     . '?radius=3000&lon=37.6173&lat=55.7558'
     . '&format=json&limit=10'
     . '&apikey=5ae2e3f221c38a28845f05b6';

$apiData = $api->request($url);

// Обновляем кеш, если ответ успешный
if (!isset($apiData['error'])) {
    file_put_contents('api_cache.json', json_encode($apiData, JSON_UNESCAPED_UNICODE));
}

$_SESSION['api_data'] = $apiData;
$_SESSION['api_source'] = 'api (refresh)';

echo json_encode([
    'source' => 'api (refresh)',
    'data'   => $apiData
], JSON_UNESCAPED_UNICODE);