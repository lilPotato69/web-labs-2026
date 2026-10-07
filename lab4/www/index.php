<?php
session_start();
require_once 'UserInfo.php';
$info = UserInfo::getInfo();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная - Запись на экскурсию</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container" style="max-width: 900px;">

        <h2>Главная страница</h2>

        <!-- Ошибки (из ЛР-3) -->
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="error-box">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $e): ?><li><?= $e ?></li><?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Данные из сессии (из ЛР-3) -->
        <?php if (isset($_SESSION['success_data'])): ?>
            <div class="success-box">
                <h3>✅ Данные из сессии:</h3>
                <ul>
                    <li><b>Имя:</b> <?= $_SESSION['success_data']['username'] ?></li>
                    <li><b>Дата:</b> <?= $_SESSION['success_data']['date'] ?></li>
                    <li><b>Маршрут:</b> <?= $_SESSION['success_data']['route'] ?></li>
                    <li><b>Аудиогид:</b> <?= $_SESSION['success_data']['audioguide'] ?></li>
                    <li><b>Язык:</b> <?= $_SESSION['success_data']['language'] ?></li>
                </ul>
            </div>
            <?php unset($_SESSION['success_data']); ?>
        <?php endif; ?>

        <!-- Штрафное задание: Кука последней отправки -->
        <?php if (isset($_COOKIE['last_submission'])): ?>
            <div class="cookie-box">
                <h3>🍪 Cookie last_submission:</h3>
                <p>Последняя отправка формы: <?= htmlspecialchars($_COOKIE['last_submission']) ?></p>
            </div>
        <?php endif; ?>

        <!-- Штрафное задание: Информация о пользователе -->
        <div class="info-box">
            <h3>ℹ️ Информация о пользователе (класс UserInfo):</h3>
            <ul>
                <?php foreach ($info as $k => $v): ?>
                    <li><b><?= htmlspecialchars($k) ?>:</b> <?= htmlspecialchars($v) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Штрафное задание: Данные из API -->
        <div class="api-box">
            <h3>🗺️ Достопримечательности рядом с Москвой (API OpenTripMap)</h3>
            <p><small>Источник данных: <span id="api-source"><?= $_SESSION['api_source'] ?? 'нет данных' ?></span></small></p>

            <div id="api-content">
                <?php if (isset($_SESSION['api_data']['error'])): ?>
                    <div class="error-box">
                        <b>Ошибка API:</b> <?= htmlspecialchars($_SESSION['api_data']['error']) ?>
                    </div>
                <?php elseif (!empty($_SESSION['api_data'])): ?>
                    <ul>
                        <?php foreach (array_slice($_SESSION['api_data'], 0, 10) as $place): ?>
                            <li>
                                <b><?= htmlspecialchars($place['name'] ?: 'Без названия') ?></b>
                                <?php if (!empty($place['rate'])): ?>
                                    (рейтинг: <?= $place['rate'] ?>)
                                <?php endif; ?>
                                <?php if (!empty($place['kinds'])): ?>
                                    — <i><?= htmlspecialchars($place['kinds']) ?></i>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Данных пока нет. Заполните форму, чтобы подгрузить достопримечательности.</p>
                <?php endif; ?>
            </div>

            <!-- Штрафное задание: Кнопка "Обновить данные" (fetch) -->
            <button id="refreshBtn" class="btn" type="button">🔄 Обновить данные</button>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="form.html" class="btn">Заполнить форму</a>
            <a href="view.php" class="btn">Все записи</a>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>