<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная - Запись на экскурсию</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Главная страница</h2>

        <!-- Вывод ошибок валидации -->
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="error-box">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Вывод данных из сессии (после успешной отправки) -->
        <?php if (isset($_SESSION['success_data'])): ?>
            <div class="success-box">
                <h3>Данные из сессии (успешно сохранено):</h3>
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

        <!-- ШТРАФНОЕ ЗАДАНИЕ: Вывод данных из куки -->
        <?php if (isset($_COOKIE['last_excursion'])): ?>
            <div class="cookie-box">
                <h3>🍪 Данные из Cookie (последняя запись):</h3>
                <p><?= htmlspecialchars($_COOKIE['last_excursion']) ?></p>
            </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['success_data']) && !isset($_COOKIE['last_excursion'])): ?>
            <p>Данных пока нет. Заполните форму!</p>
        <?php endif; ?>

        <div style="margin-top: 20px; text-align: center;">
            <a href="form.html" class="btn">Заполнить форму</a>
            <a href="view.php" class="btn">Посмотреть все данные</a>
        </div>
    </div>
</body>
</html>