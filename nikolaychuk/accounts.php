<!DOCTYPE html> 
<html lang="ru"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <style> 
        /* Стили остаются без изменений */ 
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4; 
            margin: 0; 
            padding: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
        } 

        table { 
            width: 100%; 
            max-width: 600px; 
            margin: 20px; 
            border-collapse: collapse; 
            background-color: white; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            border-radius: 8px; 
            overflow: hidden; 
        } 

        th, td { 
            padding: 15px; 
            text-align: left; 
            border-bottom: 1px solid #ddd; 
        } 

        th { 
            background-color: #4CAF50; 
            color: white; 
        } 

        tr:hover { 
            background-color: #f5f5f5; 
        } 

        @media (max-width: 600px) { 
            th, td { 
                display: block; 
                width: 100%; 
                box-sizing: border-box; 
            } 

            th { 
                text-align: center; 
            } 
        } 
    </style> 
    <title>Адаптивная таблица с PHP и MySQLi</title> 
</head> 
<body> 

<?php 
// Подключение к базе данных (замените параметры на ваши) 
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "govor"; 

$conn = new mysqli($servername, $username, $password, $dbname); 

// Проверка соединения 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 

// SQL-запрос для получения данных 
$sql = "SELECT `id`, `login`, `email`, `password` FROM `Users`"; 
$result = $conn->query($sql); 

if ($result->num_rows > 0) { 
    // Вывод данных в таблицу 
    echo '<table>'; 
    echo '<thead><tr><th>ID</th><th>Роль</th><th>Логин</th><th>Пароль</th></tr></thead>'; 
    echo '<tbody>'; 

    while ($row = $result->fetch_assoc()) { 
        echo '<tr>'; 
        echo '<td>' . $row['id'] . '</td>'; 
        echo '<td>' . $row['login'] . '</td>'; 
        echo '<td>' . $row['email'] . '</td>'; 
        echo '<td>' . $row['password'] . '</td>'; 
        echo '</tr>'; 
    } 

    echo '</tbody></table>'; 
} else { 
    echo "0 результатов"; 
} 

$conn->close(); 
?> 

</body> 
</html>