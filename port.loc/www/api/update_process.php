<?php
session_start();
require_once 'check_access.php';
require_once 'permissions.php';

// Проверяем, есть ли право на редактирование
if (!canEdit()) {
    $_SESSION['access_error'] = "У вас нет прав для редактирования данных";
    header("Location: index.php");
    exit();
}

// Включение отображения ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключение к базе данных
$host = "localhost";
$user = "root";
$password = "";
$database = "port_cargo_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

// Установка кодировки
mysqli_set_charset($conn, "utf8");

// Обработка обновления данных
if (isset($_POST['update'])) {
    $table = $_POST['table'];
    $record_id = $_POST['record_id'];
    
    // Массив для хранения полей и значений для обновления
    $updates = [];
    
    // Обработка данных в зависимости от таблицы
    switch($table) {
        case 'employees':
            $fields = ['first_name', 'last_name', 'position', 'department', 
                      'hire_date', 'salary', 'email', 'phone'];
            $id_field = 'employee_id';
            break;
            
        case 'cargoes':
            $fields = ['cargo_code', 'description', 'cargo_type', 'weight_kg', 
                      'volume_m3', 'owner_company', 'destination_port', 
                      'arrival_date', 'status'];
            $id_field = 'cargo_id';
            break;
            
        case 'vessels':
            $fields = ['vessel_name', 'imo_number', 'flag_country', 'vessel_type',
                      'capacity_tonnage', 'year_built', 'current_status', 
                      'port_of_registry'];
            $id_field = 'vessel_id';
            break;
            
        case 'operations':
            $fields = ['cargo_id', 'vessel_id', 'employee_id', 'operation_type',
                      'operation_date', 'location', 'duration_minutes',
                      'equipment_used', 'notes'];
            $id_field = 'operation_id';
            break;
            
        case 'customers':
            $fields = ['company_name', 'contact_person', 'email', 'phone',
                      'address', 'city', 'country'];
            $id_field = 'customer_id';
            break;
            
        default:
            $message = "Неизвестная таблица!";
            $message_class = "error";
            break;
    }
    
    // Собираем поля для обновления
    foreach ($fields as $field) {
        if (isset($_POST[$field]) && $_POST[$field] !== '') {
            $value = mysqli_real_escape_string($conn, $_POST[$field]);
            $updates[] = "$field = '$value'";
        }
    }
    
    // Если есть поля для обновления
    if (!empty($updates)) {
        $sql = "UPDATE $table SET " . implode(', ', $updates) . " WHERE $id_field = $record_id";
        
        if (mysqli_query($conn, $sql)) {
            if (mysqli_affected_rows($conn) > 0) {
                $message = "Запись успешно обновлена!";
                $message_class = "success";
            } else {
                $message = "Запись с ID $record_id не найдена или данные не изменились.";
                $message_class = "error";
            }
        } else {
            $message = "Ошибка при обновлении: " . mysqli_error($conn);
            $message_class = "error";
        }
    } else {
        $message = "Не указано ни одного поля для обновления!";
        $message_class = "error";
    }
    
    // Закрытие соединения
    mysqli_close($conn);
} else {
    $message = "Форма не была отправлена!";
    $message_class = "error";
}

// Функция для получения русского названия таблицы
function getTableName($table) {
    $names = [
        'employees' => 'Сотрудники',
        'cargoes' => 'Грузы',
        'vessels' => 'Суда',
        'operations' => 'Операции',
        'customers' => 'Клиенты'
    ];
    return isset($names[$table]) ? $names[$table] : $table;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Результат обновления данных</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .message { padding: 20px; margin: 20px 0; border-radius: 5px; font-size: 16px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .button { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
        .query-info { background: #d1ecf1; color: #0c5460; padding: 10px; margin: 10px 0; border-radius: 5px; font-family: monospace; }
    </style>
</head>
<body>
    <h1>Результат обновления данных</h1>
    
    <?php if (isset($message)): ?>
        <div class="message <?php echo $message_class; ?>">
            <h3>Таблица: <?php echo getTableName($table); ?></h3>
            <p><?php echo $message; ?></p>
            <?php if (isset($sql)): ?>
                <div class="query-info">
                    <strong>Выполненный SQL запрос:</strong><br>
                    <?php echo htmlspecialchars($sql); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div>
        <a href="update_form.php" class="button">Вернуться к обновлению</a>
        <a href="delete_form.php" class="button">Удаление данных</a>
        <a href="form_all.php" class="button">Ввод данных</a>
        <a href="view_all.php" class="button">Просмотр данных</a>
    </div>
</body>
</html>