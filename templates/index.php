<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F template</title>
</head>
<body>
<?php if (isset($_SESSION['auth'])): ?>
        <h1>Привет, <?= $_SESSION['auth']['fio'] ?>!</h1>
        <p>Логин: <?= $_SESSION['auth'] ?></p>
        <p>Дата рождения: <?= $_SESSION['auth']['birthdate'] ?></p>
        <a href="/logout">Выйти</a>
    <?php else: ?>
        <h1>Главная страница</h1>
        <a href="/login">Войти</a> | <a href="/register">Зарегистрироваться</a>
    <?php endif; ?>
</body>
</html>