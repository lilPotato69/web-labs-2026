<?php
session_start();

// 1. Получаем данные из формы и очищаем их
$username = htmlspecialchars(trim($_POST['username'] ?? ''));
$date = htmlspecialchars(trim($_POST['date'] ?? ''));
$route = htmlspecialchars(trim($_POST['route'] ?? ''));
$audioguide = isset($_POST['audioguide']) ? 'Да' : 'Нет';
$language = htmlspecialchars(trim($_POST['language'] ?? 'ru'));

// 2. Валидация (Шаг 6)
$errors = [];
if (empty($username)) {
    $errors[] = "Имя не может быть пустым.";
}
if (empty($date)) {
    $errors[] = "Дата экскурсии обязательна.";
}
if (strlen($username) > 100) {
    $errors[] = "Имя слишком длинное (макс. 100 символов).";
}

// 3. Если есть ошибки — сохраняем их в сессию и возвращаемся
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    // Сохраняем введенные данные, чтобы пользователь не заполнял форму заново
    $_SESSION['old_data'] = $_POST;
    header("Location: index.php");
    exit();
}

// 4. Если ошибок нет — сохраняем в сессию
$_SESSION['success_data'] = [
    'username' => $username,
    'date' => $date,
    'route' => $route,
    'audioguide' => $audioguide,
    'language' => $language
];

// 5. Сохраняем в файл data.txt (Шаг 4)
$line = "$username;$date;$route;$audioguide;$language\n";
file_put_contents("data.txt", $line, FILE_APPEND);

// 6. ШТРАФНОЕ ЗАДАНИЕ: Сохраняем в куки
$cookie_value = "$username;$date;$route";
setcookie("last_excursion", $cookie_value, time() + 3600, "/"); // Кука на 1 час

// 7. Перенаправляем на главную
header("Location: index.php");
exit();
?>