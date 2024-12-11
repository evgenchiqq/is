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

    // Удаление выбранного пользователя
    $delete_sql = "DELETE FROM users WHERE id=$user_id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Пользователь успешно удален!";
        // Перенаправление на adminpage.php
        header("Location: adminpage.php");
        exit();
    } else {
        echo "Ошибка при удалении пользователя: " . $conn->error;
    }

    $conn->close();
?>