<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
</head>
<body>
    <h1>Авторизация</h1>
    
    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form action="/login" method="POST">
        <div>
            <label>Логин:</label>
            <input type="text" name="login" value="<?= $_POST['login'] ?? '' ?>">
        </div>
        <div>
            <label>Пароль:</label>
            <input type="password" name="password">
        </div>
        <input type="submit" value="Войти">
    </form>
    
    <p>Нет аккаунта? <a href="/register">Зарегистрируйтесь</a></p>
</body>
</html>