<?php   
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Проверка на пустые данные
    if (empty($data['username']) || empty($data['password'])) {
        echo json_encode(['success' => false, 'message' => 'Логин и пароль обязательны.']);
        exit;
    }

    // Получение данных из запроса
    $login = $data['username'];
    $password = $data['password'];

    // Настройки подключения к базе данных
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "govor";

    // Подключение к базе данных
    $connect = mysqli_connect($servername, $db_username, $db_password, $dbname);
    
    if (!$connect) {
        die(json_encode(['success' => false, 'message' => "Соединение не удалось: " . mysqli_connect_error()]));
    }

    // Подготовленный запрос для повышения безопасности
    $query_login = "SELECT * FROM Users WHERE login=?";
    $stmt = mysqli_prepare($connect, $query_login);
    
    if ($stmt === false) {
        die(json_encode(['success' => false, 'message' => "Ошибка подготовки запроса: " . mysqli_error($connect)]));
    }

    mysqli_stmt_bind_param($stmt, 's', $login);
    mysqli_stmt_execute($stmt);
    $result_login = mysqli_stmt_get_result($stmt);

    if ($result_login && mysqli_num_rows($result_login) > 0) {
        $row = mysqli_fetch_assoc($result_login);
        
        // Проверка пароля
        if (password_verify($password, $row['password'])) {
            // Установка сессионных переменных
            $_SESSION['id'] = $row['id']; // Сохраняем ID пользователя
            $_SESSION['role'] = $row['role'];   // Сохраняем роль пользователя

            switch ($row['role']) {
                case 'manager':
                    echo json_encode(['success' => true, 'redirect' => 'managerpage.php']);
                    break;
                case 'admin':
                    echo json_encode(['success' => true, 'redirect' => 'adminpage.php']);
                    break;
                case 'user':
                    echo json_encode(['success' => true, 'redirect' => 'userpage.php']);
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Неизвестная роль пользователя.']);
                    break; // Добавлено для явного завершения
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Неверный логин или пароль.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Пользователь не найден.']);
    }

    // Закрываем соединение
    mysqli_stmt_close($stmt);
    mysqli_close($connect);
}
?>
