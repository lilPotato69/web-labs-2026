<?php
require 'db.php';
require 'Excursion.php';

$excursion = new Excursion($pdo);
$excursion->createTable();

// Обработка удаления
if (isset($_GET['delete'])) {
    $excursion->delete((int)$_GET['delete']);
    header("Location: index.php");
    exit();
}

// Фильтр
$filter = $_GET['filter'] ?? 'all';
$all = $excursion->getAll($filter);
$stats = [
    'total'          => $excursion->count(),
    'with_audioguide' => count($excursion->getAll('audioguide')),
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Записи на экскурсии</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container" style="max-width: 900px;">

    <h2>Записи на экскурсии (БД)</h2>

    <div class="stats-box">
        <h3>📊 Статистика</h3>
        <ul>
            <li>Всего записей: <b><?= $stats['total'] ?></b></li>
            <li>С аудиогидом: <b><?= $stats['with_audioguide'] ?></b></li>
        </ul>
    </div>

    <div class="filter-box">
        <b>Фильтр:</b>
        <a href="?filter=all" class="btn <?= $filter === 'all' ? 'active' : '' ?>">Все</a>
        <a href="?filter=audioguide" class="btn <?= $filter === 'audioguide' ? 'active' : '' ?>">Только с аудиогидом</a>
    </div>

    <h3>Список записей <?= $filter === 'audioguide' ? '(с аудиогидом)' : '' ?>:</h3>

    <?php if (empty($all)): ?>
        <p>Записей пока нет.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Дата</th>
                    <th>Маршрут</th>
                    <th>Аудиогид</th>
                    <th>Язык</th>
                    <th>Создано</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($all as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['route']) ?></td>
                    <td><?= $row['audioguide'] ? '✅' : '—' ?></td>
                    <td><?= htmlspecialchars($row['language']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td>
                        <a href="?delete=<?= $row['id'] ?>"
                           onclick="return confirm('Удалить запись?');"
                           class="btn-delete">✕</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div style="margin-top: 20px; text-align: center;">
        <a href="form.html" class="btn">Добавить запись</a>
        <a href="http://localhost:8089" target="_blank" class="btn">Открыть Adminer</a>
    </div>
</div>
</body>
</html>