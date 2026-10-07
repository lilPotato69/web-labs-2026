<?php
session_start();

// --- Стандартная обработка формы (из ЛР-3) ---
$username = htmlspecialchars(trim($_POST['username'] ?? ''));
$date = htmlspecialchars(trim($_POST['date'] ?? ''));
$route = htmlspecialchars(trim($_POST['route'] ?? ''));
$audioguide = isset($_POST['audioguide']) ? 'Да' : 'Нет';
$language = htmlspecialchars(trim($_POST['language'] ?? 'ru'));

$errors = [];
if (empty($username)) $errors[] = "Имя не может быть пустым.";
if (empty($date)) $errors[] = "Дата экскурсии обязательна.";
if (strlen($username) > 100) $errors[] = "Имя слишком длинное.";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

$_SESSION['success_data'] = compact('username', 'date', 'route', 'audioguide', 'language');

$line = "$username;$date;$route;$audioguide;$language\n";
file_put_contents("data.txt", $line, FILE_APPEND);

// Штрафное задание: Кука с временем последней отправки
setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");

// --- ШТРАФНОЕ ЗАДАНИЕ 1: Кеширование API ---
require_once 'ApiClient.php';
$api = new ApiClient();

// URL API OpenTripMap: места рядом с Москвой (для темы "Экскурсии")
$url = 'https://api.opentripmap.com/0.1/en/places/radius'
     . '?radius=3000&lon=37.6173&lat=55.7558'
     . '&format=json&limit=10'
     . '&apikey=5ae2e3f221c38a28845f05b6';

$cacheFile = 'api_cache.json';
$cacheTtl  = 300; // 5 минут
$apiData   = null;

// Проверяем кеш
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTtl) {
    $apiData = json_decode(file_get_contents($cacheFile), true);
    $_SESSION['api_source'] = 'cache';
}

// Если кеш устарел или его нет — запрашиваем API
if ($apiData === null) {
    $apiData = $api->request($url);
    $_SESSION['api_source'] = 'api';

    // Кешируем ТОЛЬКО успешные ответы (без ошибок)
    if (!isset($apiData['error'])) {
        file_put_contents($cacheFile, json_encode($apiData, JSON_UNESCAPED_UNICODE));
    }
}

$_SESSION['api_data'] = $apiData;

header("Location: index.php");
exit();
?>