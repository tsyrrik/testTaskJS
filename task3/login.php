<?php
session_start();

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Примитивная проверка логина и пароля
    if ($username === 'admin' && $password === 'password') {
        $_SESSION['logged_in'] = true;
        header('Location: stats.php');
        exit();
    } else {
        $error = "Неправильный логин или пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <

    meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
</head>
<body>
<h1>Вход</h1>
<?php if (isset($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>
<form method="POST">
    <label for="username">Логин:</label>
    <input type="text" id="username" name="username">
    <br>
    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password">
    <br>
    <button type="submit">Войти</button>
</form>
</body>
</html>
