<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Загружаем .env.test (штрафное задание 3)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..', '.env.test');
$dotenv->safeLoad();