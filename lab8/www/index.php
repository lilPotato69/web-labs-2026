<?php
require 'QueueManager.php';

// RabbitMQ — через Management API (штрафное задание)
$rabbitStats = ['main' => 0, 'errors' => 0, 'error_msg' => null];
try {
    $qm = new QueueManager();
    $rabbitStats = array_merge($rabbitStats, $qm->stats());
} catch (\Throwable $e) {
    $rabbitStats['error_msg'] = $e->getMessage();
}

// Чтение логов воркеров
function countLog(string $file): int {
    if (!file_exists($file)) return 0;
    return count(file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
}

$processedRabbit = countLog('processed_rabbit.log');
$processedKafka  = countLog('processed_kafka.log');
$errorsRabbit    = countLog('errors_rabbit.log');
$errorsKafka     = countLog('errors_kafka.log');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЛР-7: RabbitMQ + Kafka</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container" style="max-width: 950px;">
    <h2>🧪 ЛР-7: Асинхронная обработка через RabbitMQ и Kafka</h2>

    <?php if (isset($_GET['published'])): ?>
        <div class="success-box">✅ Сообщение отправлено в обе очереди!</div>
    <?php endif; ?>

    <!-- ===================== RABBITMQ ===================== -->
    <div class="stats-box">
        <h3>🐇 RabbitMQ</h3>
        <?php if ($rabbitStats['error_msg']): ?>
            <p class="error-text">Ошибка подключения к Management API: <?= htmlspecialchars($rabbitStats['error_msg']) ?></p>
        <?php else: ?>
            <ul>
                <li>📥 В основной очереди (<code>lab7_queue</code>): <b><?= $rabbitStats['main'] ?></b></li>
                <li>⚠️ В очереди ошибок (<code>lab7_errors</code>): <b><?= $rabbitStats['errors'] ?></b></li>
                <li>✅ Обработано воркером: <b><?= $processedRabbit ?></b></li>
                <li>❌ Провалено: <b><?= $errorsRabbit ?></b></li>
            </ul>
            <p><a href="http://localhost:15672" target="_blank" class="btn">Открыть RabbitMQ Management</a></p>
        <?php endif; ?>
    </div>

    <!-- ===================== KAFKA ===================== -->
    <div class="api-box">
        <h3>🦊 Apache Kafka</h3>
        <ul>
            <li>✅ Обработано воркером: <b><?= $processedKafka ?></b></li>
            <li>❌ Провалено (в topic <code>lab7_errors</code>): <b><?= $errorsKafka ?></b></li>
        </ul>
        <p><small>Топики: <code>lab7_topic</code>, <code>lab7_errors</code></small></p>
    </div>

    <div style="margin-top: 20px; text-align: center;">
        <a href="form.html" class="btn">Отправить новое сообщение</a>
        <a href="index.php" class="btn">🔄 Обновить статистику</a>
    </div>
</div>
</body>
</html>