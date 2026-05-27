<?php
// Включение отображения ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключение к базе данных
$host = "localhost";
$user = "root";
$password = "root";
$database = "port_cargo_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die(json_encode(['exists' => false, 'error' => 'Ошибка подключения']));
}

// Установка кодировки
mysqli_set_charset($conn, "utf8");

// Получение параметров
$table = isset($_GET['table']) ? $_GET['table'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Определение поля ID для каждой таблицы
$id_fields = [
    'employees' => 'employee_id',
    'cargoes' => 'cargo_id',
    'vessels' => 'vessel_id',
    'operations' => 'operation_id',
    'customers' => 'customer_id'
];

if (isset($id_fields[$table]) && !empty($id)) {
    $id_field = $id_fields[$table];
    $id = mysqli_real_escape_string($conn, $id);
    
    $sql = "SELECT * FROM $table WHERE $id_field = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Преобразуем NULL значения в пустые строки
        foreach ($row as $key => $value) {
            if ($value === null) {
                $row[$key] = '';
            }
        }
        
        echo json_encode(['exists' => true, 'data' => $row]);
    } else {
        echo json_encode(['exists' => false]);
    }
} else {
    echo json_encode(['exists' => false, 'error' => 'Неверные параметры']);
}

mysqli_close($conn);
?>