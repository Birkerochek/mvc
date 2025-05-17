<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
</head>
<body>
    <h1>Регистрация</h1>
    
    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form action="/register" method="POST">
        <div>
            <label>Логин:</label>
            <input type="text" name="login" value="<?= $_POST['login'] ?? '' ?>">
        </div>
        <div>
            <label>ФИО:</label>
            <input type="text" name="fio" value="<?= $_POST['fio'] ?? '' ?>">
        </div>
        <div>
            <label>Пароль:</label>
            <input type="password" name="password">
        </div>
        <div>
            <label>Дата рождения:</label>
            <input type="date" name="birthdate" value="<?= $_POST['birthdate'] ?? '' ?>">
        </div>
        <input type="submit" value="Зарегистрироваться">
    </form>
    
    <p>Есть аккаунт <a href="/login">Войти</a></p>
</body>
</html>