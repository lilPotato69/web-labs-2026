<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Все записи</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container" style="max-width: 800px;">
        <h2>Все сохранённые данные:</h2>

        <?php if (file_exists("data.txt") && filesize("data.txt") > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Дата</th>
                        <th>Маршрут</th>
                        <th>Аудиогид</th>
                        <th>Язык</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $lines = file("data.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($lines as $line) {
                        $parts = explode(";", $line);
                        // Защита от неполных строк
                        if (count($parts) >= 5) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($parts[0]) . "</td>";
                            echo "<td>" . htmlspecialchars($parts[1]) . "</td>";
                            echo "<td>" . htmlspecialchars($parts[2]) . "</td>";
                            echo "<td>" . htmlspecialchars($parts[3]) . "</td>";
                            echo "<td>" . htmlspecialchars($parts[4]) . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Данных пока нет.</p>
        <?php endif; ?>

        <div style="margin-top: 20px; text-align: center;">
            <a href="index.php" class="btn">На главную</a>
            <a href="form.html" class="btn">Добавить запись</a>
        </div>
    </div>
</body>
</html>