<?php
// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "govor";

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}


// Обработка отправленной формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_login = $_POST['login'];
    $new_password = $_POST['password'];

    // Проверка значений перед обновлением
    if (empty($new_login)) {
        echo "<div class='alert error'>Логин не может быть пустым.</div>";
    } else {
        // Обновление данных пользователя
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET login = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssi", $new_login, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET login = ? WHERE id = ?");
            $stmt->bind_param("si", $new_login, $user_id);
        }

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<div class='alert success'>Данные успешно обновлены.</div>";
            } else {
                echo "<div class='alert error'>Данные не были изменены. Возможно, логин остался прежним.</div>";
            }
        } else {
            echo "<div class='alert error'>Ошибка при обновлении данных: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-image: url(data/jpg/3.jpg);
            background-color: #ffffff;
            background-size: cover;
            transition: background-image 1s ease-in-out;
        }
        h1 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            background: #ffffff1a;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgb(0, 0, 0);
        }
        input {
            width: 99%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
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
<h1>Редактирование учётной записи</h1>

<!-- Форма для изменения учётной записи -->
<form method="post">
    <label for="login">Имя пользователя:</label>
    <input type="text" id="login" name="login" value="<?php echo htmlspecialchars($user['login']); ?>" required>
    
    <label for="password">Новый пароль (оставьте пустым, если не хотите изменять):</label>
    <input type="password" id="password" name="password">
    
    <button type="submit">Сохранить изменения</button>
</form>

<!-- Кнопка выхода из учётной записи -->
<form method="post">
    <button type="submit" name="logout">Выйти из учётной записи</button>
</form>

<footer>
    <p>© 2024 Все права защищены.</p>
</footer>

<?php
// Обработка выхода из учётной записи
if (isset($_POST['logout'])) {
    session_unset(); // Сбрасываем все переменные сессии
    session_destroy(); // Уничтожаем сессию
    header("Location: signinpage.html"); // Перенаправление на страницу входа
    exit();
}
?>

</body>
</html>

<?php
$conn->close(); // Закрытие подключения к базе данных
?>