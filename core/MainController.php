<?php
use Model\Posts;
class MainController
{


    // Создаем новый View для шаблона register, изначально передавая в него массив ошибок, чтобы дальше в него заносить ошибки и после выводить их на страницу
    public function registerRender()
    {
        return new View('register', ['errors' => []]);
    }

    public function register()
    {
        // Изначально создаётся пустой массив ошибок, чтобы если потом были ошибки их записывать в массив и передавать в переменную в шаблоне
        $errors = [];

        // Проверка на заполненность логина
        if (empty($_POST['login'])) {
            $errors[] = 'Логин обязателен';
            // Проверяем есть ли в массиве users элемент с переданным ключoм в login
        } elseif (isset($_SESSION['users'][$_POST['login']])) {
            $errors[] = 'Этот логин уже занят';
        }
        // Проверка на заполненность фио
        if (empty($_POST['fio'])) {
            $errors[] = 'ФИО обязательно';
        }
        // Проверка на заполненность пароля
        if (empty($_POST['password'])) {
            $errors[] = 'Пароль обязателен';
            // Если длина переданного пароля меньше 6
        } elseif (strlen($_POST['password']) < 6) {
            $errors[] = 'Пароль должен быть не менее 6 символов';
        }


        // Если есть ошибки, то рендерим форму вместе с ошибками
        if ($errors) {
            return new View('register', ['errors' => $errors]);
        }

        // Запись данных пользователя
        $_SESSION['users'][$_POST['login']] = [
            'fio' => $_POST['fio'],
            'password' => $_POST['password'],
            'birthdate' => $_POST['birthdate']
        ];

        // Автоматическая авторизация пользователя после регистрации, путём добавления в auth переданных данных. Которые будут дальше вызываться условным рендерингом
        $_SESSION['auth'] = $_POST['login'];
        // Перенаправление на главную страницу
        header('Location: /');
    }
    public function logout()
    {
        // Очиска auth
        unset($_SESSION['auth']);
        // Перенаправление на главную страницу
        header('Location: /');
    }

    // Создаем новый View для шаблона login, изначально передавая в него массив ошибок, чтобы дальше в него заносить ошибки и после выводить их на страницу
    public function loginRender()
    {
        return new View('login', ['errors' => []]);
    }

    public function login()
    {
        //  По той же аналогии создаём пустой массив ошибок
        $errors = [];

        // Проверка на наполненность логина
        if (empty($_POST['login'])) {
            $errors[] = 'Введите логин';
        }
        // Проверка на наполненость пароля
        if (empty($_POST['password'])) {
            $errors[] = 'Введите пароль';
        }

        if (($user = $_SESSION['users'][$_POST['login']] ?? false) && $user['password'] !== $_POST['password'])
            $errors[] = "Логин или пароль не верный";
        // Если есть ошибки, возвращаем форму, но уже с ошибками
        if ($errors) {
            return new View('login', ['errors' => $errors]);
        }

        // Авторизуем пользователя
        $_SESSION['auth'] = $user;

        // Перебрасываем на главную страницу
        header('Location: /');


    }

    // public function testGetPosts(){
    //     date_default_timezone_set('Europe/Moscow');
    //     (new Posts())->insert([
    //         'name' => "Roman",
    //         'desccription' => 'test',
    //         'created' => date('Y-m-d'),
    //         'author' => 20,
    //     ]);
    //     $posts = (new Posts())->all();
    //     echo '<pre>';
    //     print_r($posts);
    // }

    public function testGetPosts()
    {
        $posts = new Posts();
        $data = $posts->select('id,name')->where([['id', 'IN', [3, 4]]])->get();
        echo '<pre>';
        print_r($data);
    }
}