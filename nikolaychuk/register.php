<?php   
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из запроса
    $data = json_decode(file_get_contents("php://input"), true);
    $login = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    $email = $data['email'] ?? '';
    $role = $data['role'] ?? 'User';

    // Проверка на пустые значения
    if (empty($login) || empty($password) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Все поля должны быть заполнены.']);
        exit;
    }

    // Допускаем только определенные роли
    $allowed_roles = ['User', 'admin', 'seller', 'manager'];
    if (!in_array($role, $allowed_roles)) {
        echo json_encode(['success' => false, 'message' => 'Недопустимая роль.']);
        exit;
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "govor";

    $connect = mysqli_connect($server, $user, $pw, $db);
    if (!$connect) {
        die(json_encode(['success' => false, 'message' => "Connection failed: " . mysqli_connect_error()]));
    }

    // Экранирование входных данных для предотвращения SQL-инъекций
    $login = mysqli_real_escape_string($connect, $login);
    $email = mysqli_real_escape_string($connect, $email);

    // Проверка существования логина
    $query_check = "SELECT * FROM Users WHERE login='$login'";
    $result_check = mysqli_query($connect, $query_check);

    if ($result_check && mysqli_num_rows($result_check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Пользователь с таким логином уже существует.']);
    } else {
        // Хеширование пароля перед сохранением
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Запись нового пользователя в базу данных с указанной ролью
        $query_insert = "INSERT INTO Users (login, password, email, role) VALUES ('$login', '$hashed_password', '$email', '$role')";
        if (mysqli_query($connect, $query_insert)) {
            echo json_encode(['success' => true, 'message' => 'Регистрация прошла успешно.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ошибка при регистрации: ' . mysqli_error($connect)]);
        }
    }

    mysqli_close($connect);
}
?>