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

// Получение ID пользователя из URL
$user_id = $_GET['id'];

// Переменная для сообщений
$message = "";

// Обработка обновления данных пользователя
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $login = $_POST['login'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Проверяем, был ли введен новый пароль
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Если пароль не пустой, обновляем его в запросе
    if ($password) {
        $update_sql = "UPDATE users SET login='$login', email='$email', role='$role', password='$password' WHERE id=$user_id";
    } else {
        $update_sql = "UPDATE users SET login='$login', email='$email', role='$role' WHERE id=$user_id";
    }

    if ($conn->query($update_sql) === TRUE) {
        $message = "<div class='success'><p>Данные успешно обновлены!</p></div>";
    } else {
        $message = "<div class='error'><p>Ошибка при обновлении данных: " . $conn->error . "</p></div>";
    }
}

// Получение данных о выбранном пользователе
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);

// Оформление
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(data/jpg/3.jpg);
            background-color: #ffffff;
            background-size: cover;
            transition: background-image 1s ease-in-out;
            background-position: center;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }
        .container {
            max-width: 600px;
            margin-top: 5px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            margin-top: 5px;
            border-radius: 4px;
            width: 100%;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .btn-admin {
            display: inline-block;
            margin-top: 5px;
            text-align: center;
            color: #fff;
            background-color: #007bff;
            border-radius: 4px;
            padding: 10px;
            text-decoration: none;
            width: 97%;
        }
        .btn-admin:hover {
            background-color: #0056b3;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-top: 5px;
            border-radius: 4px;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
            padding: 10px;
            border-radius: 4px;
            margin-top: 5px;
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
    </style>
</head>
<body>

<div class="container">
    <?php
    // Вывод сообщения об успешном обновлении данных
    if ($message) {
        echo $message;
    }

    // Проверяем, есть ли данные о пользователе
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Вывод формы для редактирования данных пользователя
        echo "<h2>Редактирование пользователя</h2>";
        echo "<form method='post'>";
        echo "<input type='text' name='login' value='" . htmlspecialchars($row['login']) . "' required placeholder='Логин'>";
        echo "<input type='email' name='email' value='" . htmlspecialchars($row['email']) . "' required placeholder='Email'>";
        echo "<input type='password' name='password' placeholder='Новый пароль (оставьте пустым, если не хотите менять)'>";
        echo "<select name='role' required>";
        echo "<option value='user' " . ($row['role'] == 'user' ? 'selected' : '') . ">Пользователь</option>";
        echo "<option value='manager' " . ($row['role'] == 'manager' ? 'selected' : '') . ">Менеджер</option>";
        echo "<option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Администратор</option>";
        echo "</select>";
        echo "<button type='submit'>Сохранить изменения</button>";
        echo "</form>";

        // Кнопка для возврата на adminpage.php
        echo "<a href='adminpage.php' class='btn btn-admin'>Вернуться на панель администрирования</a>";
    } else {
        echo "<p>Пользователь не найден.</p>";
    }
    ?>
</div>

<footer>
    <p>© 2024 Все права защищены.</p>
</footer>

</body>
</html>

<?php
// Закрытие соединения с базой данных
$conn->close();
?>