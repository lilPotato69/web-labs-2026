<?php
require 'db.php';
require 'Excursion.php';

$excursion = new Excursion($pdo);
$excursion->createTable();

$name = htmlspecialchars(trim($_POST['username'] ?? ''));
$date = $_POST['date'] ?? '';
$route = $_POST['route'] ?? 'city';
$audioguide = isset($_POST['audioguide']) ? 1 : 0;
$language = $_POST['language'] ?? 'ru';

if ($name !== '' && $date !== '') {
    $excursion->add($name, $date, $route, $audioguide, $language);
}

header("Location: index.php");
exit();