    <?php
    // Подключение к базе данных
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "govor";

    // Создание подключения
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Проверка соединения
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    // Обработка изменения данных пользователя
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $new_login = $conn->real_escape_string(trim($_POST['login']));
        $new_email = $conn->real_escape_string(trim($_POST['email']));
        $new_password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Хеширование пароля

        // Обновление данных пользователя в базе данных
        $sql_update = "UPDATE users SET login='$new_login', email='$new_email', password='$new_password' WHERE id='$user_id'";
        
        if ($conn->query($sql_update) === TRUE) {
            echo "Данные успешно обновлены.";
        } else {
            echo "Ошибка при обновлении данных: " . $conn->error;
        }
    }
    ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url(data/jpg/2.jpg);
            background-color: #ffffff;
            background-size: cover;
            transition: background-image 1s ease-in-out;
            background-position: center;
        }
        header {
            background: rgba(74, 144, 226, 0.8);
            color: #ffffff;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        nav {
            background: rgba(211, 84, 0, 0.8);
            color: #ffffff;
            padding: 10px;
            text-align: center;
            border-bottom: 5px solid #c0392b;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 25px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        nav ul li a:hover {
            color: #f39c12;
        }
        main {
            padding: 30px;
            max-width: 900px;
            margin: 20px auto;
            background: #ffffff1a;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgb(0, 0, 0);
            border-radius: 8px;
        }
        footer {
            text-align: center;
            padding: 15px;
            background: #34495e;
            color: #ffffff;
            position: fixed;
            bottom: 0;
            width: 100%;
           left: 0;
        }
        h2 {
            color: #2980b9;
        }
        section {
            margin-bottom: 20px;
        }
        strong {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <header>
        <h1>Добро пожаловать, Пользователь!</h1>
    </header>
    
    <nav>
        <ul>
            <li><a href="edit_account.php">Профиль</a></li>
            <li><a href="logout.php">Выйти</a></li>
        </ul>
    </nav>

    <main>
        <h2>Обзор системы</h2>
        <p>На этой панели вы можете управлять своим профилем, просматривать проекты и отправлять отчеты.</p>
        
        <section>
            <h3>Недавние действия</h3>
            <ul>
                <li>-</li>
                <li>-</li>
                <li>-</li>
            </ul>
        </section>

        <section>
            <h3>Ваши проекты</h3>
            <p>Всего проектов: <strong>-</strong></p>
            <p>Завершенные проекты: <strong>-</strong></p>
            <p>Проекты в процессе: <strong>-</strong></p>
        </section>
    </main>

    <footer>
        <p>© 2024 Все права защищены.</p>
    </footer>
    </body>
    </html>
    <?php
    // Закрываем соединение с базой данных
    $conn->close();
    ?>