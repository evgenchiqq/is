<?php
// Начинаем сессию
session_start();

// Удаляем все переменные сессии
$_SESSION = array();

// Если необходимо удалить куки, устанавливаем срок действия в прошлое
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Завершаем сессию
session_destroy();

// Перенаправляем пользователя на главную страницу или страницу входа
header("Location: signinpage.html");
exit;
?>