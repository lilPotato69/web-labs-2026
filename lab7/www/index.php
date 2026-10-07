<?php
require 'vendor/autoload.php';

use App\RedisExample;
use App\ElasticExample;
use App\ClickhouseExample;

$redis   = new RedisExample();
$elastic = new ElasticExample();
$click   = new ClickhouseExample();

$last    = $redis->getValue('last_excursion');
$count   = $redis->getValue('excursion_count') ?? 0;
$esCount = $elastic->count('excursions');

$search = trim($_GET['q'] ?? '');
$hits = [];
if ($search !== '') {
    $hits = $elastic->search('excursions', [
        'multi_match' => [
            'query'  => $search,
            'fields' => ['name', 'route', 'language']
        ]
    ]);
}

$click->ensureTable();
$eventCount = $click->countEvents();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>NoSQL лаба — Запись на экскурсию</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container" style="max-width: 900px;">
    <h2>🧪 ЛР-6: Redis + Elasticsearch + ClickHouse</h2>

    <div class="stats-box">
        <h3>📊 Сводка</h3>
        <ul>
            <li>🔴 <b>Redis</b> — счётчик записей: <b><?= htmlspecialchars($count) ?></b></li>
            <li>🔍 <b>Elasticsearch</b> — документов в индексе: <b><?= $esCount ?></b></li>
            <li>⚡️ <b>ClickHouse</b> — событий в логе: <b><?= $eventCount ?></b></li>
        </ul>
    </div>

    <?php if ($last): ?>
        <div class="success-box">
            <h3>🔴 Последняя запись (из Redis)</h3>
            <pre><?= htmlspecialchars(json_encode(json_decode($last, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
        </div>
    <?php endif; ?>

    <div class="api-box">
        <h3>🔍 Поиск экскурсий в Elasticsearch (вариант 8)</h3>
        <form method="GET" action="index.php">
            <input type="text" name="q" placeholder="Например: Ivan, city, en..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn">Найти</button>
        </form>

        <?php if ($search !== ''): ?>
            <p><b>Найдено: <?= count($hits) ?></b></p>
            <?php if (empty($hits)): ?>
                <p>Ничего не найдено.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($hits as $hit): ?>
                        <li>
                            <b><?= htmlspecialchars($hit['_source']['name']) ?></b>
                            — <?= htmlspecialchars($hit['_source']['route']) ?>
                            (<?= htmlspecialchars($hit['_source']['language']) ?>)
                            <small>[score: <?= round($hit['_score'], 2) ?>]</small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div style="margin-top: 20px; text-align: center;">
        <a href="form.html" class="btn">Заполнить форму</a>
    </div>
</div>
</body>
</html>