<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link href="Fonts" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url(data/jpg/2.jpg);
            background-color: #ffffff;
            background-size: cover;
            transition: background-image 1s ease-in-out;
            overflow: hidden;
        }
        .header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        .nav {
            background-color: #444;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        .nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff1a;
            backdrop-filter: blur(10px); /* Размытие фона на 10 пикселей */
            border-radius: 5px;
            box-shadow: 0 4px 10px rgb(0, 0, 0);
        }
        form {
            margin-top: 20px;
        }
        input, select, button {
            margin-bottom: 10px;
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        table {
        width: 100%;
        border-collapse: collapse;
        background-color: white; /* Установите белый фон для таблицы */
        }

        th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
        background-color: white; /* Установите белый фон для ячеек таблицы */
        }
        .btn {
        display: inline-block;
        padding: 5px 10px;
        margin: 5px;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s ease; /* Плавный переход фона */
        font-size: 14px; /* Размер шрифта (можно также уменьшить или увеличить) */
}

.btn-edit {
    background-color: #4CAF50; /* Зеленый */
}

.btn-edit:hover {
    background-color: #45a049; /* Темно-зеленый при наведении */
}

.btn-delete {
    background-color: #f44336; /* Красный */
}

.btn-delete:hover {
    background-color: #e53935; /* Темно-красный при наведении */
}
footer {
    text-align: center;
    padding: 15px;
    background: #34495e;
    color: #ffffff;
    position: fixed; /* Закрепляет футер внизу экрана */
    bottom: 0; /* Устанавливает футер на дно */
    width: 100%; /* Ширина футера 100% */
    left: 0; /* Начало футера с левого края */
}
    </style>
</head>
<body>

<div class="header">
    <h1>Добро пожаловать, Администратор!</h1>
</div>

<div class="nav">
    <a href="logout.php">Выход</a>
</div>

<div class="container">
    <div class="users">
        <h2>Список пользователей</h2>
        <?php
            // Подключение к базе данных
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "govor";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Добавление нового пользователя
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
                $login = $_POST['login'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хеширование пароля
                $role = $_POST['role'];

                $sql = "INSERT INTO users (login, email, password, role) VALUES ('$login', '$email', '$password', '$role')";

                if ($conn->query($sql) === TRUE) {
                    echo "Пользователь успешно добавлен!";
                } else {
                    echo "Ошибка при добавлении пользователя: " . $conn->error;
                }
            }

            // Получение списка пользователей
            $sql = "SELECT id, login, email, role FROM users";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                // Вывод данных о пользователях в виде таблицы
                echo "<table>";
                echo "<tr><th>ID</th><th>Логин</th><th>Email</th><th>Роль</th><th>Действия</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['login'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['role'] . "</td>";
                    echo "<td>
                            <a class='btn btn-edit' href=\"edit_user.php?id=" . $row['id'] . "\">Изменить</a> 
                            | 
                            <a class='btn btn-delete' href=\"delete_user.php?id=" . $row['id'] . "\" onclick=\"return confirm('Вы уверены, что хотите удалить этого пользователя?');\">Удалить</a>
                          </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Нет пользователей в базе данных.";
            }
            $conn->close();
        ?>
    </div>

    <div class="add-user">
        <h2>Добавить нового пользователя</h2>
        <form method="post">
            <input type="text" name="login" placeholder="Логин" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <select name="role" required>
                <option value="user">Пользователь</option>
                <option value="manager">Менеджер</option>
                <option value="admin">Администратор</option>
            </select>
            <button type="submit">Добавить пользователя</button>
        </form>
    </div>
</div>
<footer>
        <p>© 2024 Все права защищены.</p>
    </footer>
</body>
</html>