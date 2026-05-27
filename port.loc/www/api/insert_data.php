<?php

// Включение отображения ошибок для отладки
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

// Обработка формы в зависимости от таблицы
if (isset($_POST['submit'])) {
    $table = $_POST['table'];
    
    switch($table) {
        case 'employees':
            $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
            $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
            $position = mysqli_real_escape_string($conn, $_POST['position']);
            $department = mysqli_real_escape_string($conn, $_POST['department']);
            $hire_date = $_POST['hire_date'];
            $salary = $_POST['salary'];
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            
            $sql = "INSERT INTO employees (first_name, last_name, position, department, hire_date, salary, email, phone) 
                    VALUES ('$first_name', '$last_name', '$position', '$department', '$hire_date', $salary, '$email', '$phone')";
            break;
            
        case 'cargo':
            $cargo_code = mysqli_real_escape_string($conn, $_POST['cargo_code']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $cargo_type = mysqli_real_escape_string($conn, $_POST['cargo_type']);
            $weight_kg = $_POST['weight_kg'];
            $volume_m3 = $_POST['volume_m3'];
            $owner_company = mysqli_real_escape_string($conn, $_POST['owner_company']);
            $destination_port = mysqli_real_escape_string($conn, $_POST['destination_port']);
            $arrival_date = $_POST['arrival_date'];
            $status = mysqli_real_escape_string($conn, $_POST['status']);
            
            $sql = "INSERT INTO cargo (cargo_code, description, cargo_type, weight_kg, volume_m3, owner_company, destination_port, arrival_date, status) 
                    VALUES ('$cargo_code', '$description', '$cargo_type', $weight_kg, $volume_m3, '$owner_company', '$destination_port', '$arrival_date', '$status')";
            break;
            
        case 'vessels':
            $vessel_name = mysqli_real_escape_string($conn, $_POST['vessel_name']);
            $imo_number = mysqli_real_escape_string($conn, $_POST['imo_number']);
            $flag_country = mysqli_real_escape_string($conn, $_POST['flag_country']);
            $vessel_type = mysqli_real_escape_string($conn, $_POST['vessel_type']);
            $capacity_tonnage = $_POST['capacity_tonnage'];
            $year_built = $_POST['year_built'];
            $current_status = mysqli_real_escape_string($conn, $_POST['current_status']);
            $port_of_registry = mysqli_real_escape_string($conn, $_POST['port_of_registry']);
            
            $sql = "INSERT INTO vessels (vessel_name, imo_number, flag_country, vessel_type, capacity_tonnage, year_built, current_status, port_of_registry) 
                    VALUES ('$vessel_name', '$imo_number', '$flag_country', '$vessel_type', $capacity_tonnage, $year_built, '$current_status', '$port_of_registry')";
            break;
            
        case 'operations':
            $cargo_id = $_POST['cargo_id'];
            $vessel_id = $_POST['vessel_id'];
            $employee_id = $_POST['employee_id'];
            $operation_type = mysqli_real_escape_string($conn, $_POST['operation_type']);
            $operation_date = $_POST['operation_date'];
            $location = mysqli_real_escape_string($conn, $_POST['location']);
            $duration_minutes = $_POST['duration_minutes'];
            $equipment_used = mysqli_real_escape_string($conn, $_POST['equipment_used']);
            $notes = mysqli_real_escape_string($conn, $_POST['notes']);
            
            $sql = "INSERT INTO operations (cargo_id, vessel_id, employee_id, operation_type, operation_date, location, duration_minutes, equipment_used, notes) 
                    VALUES ($cargo_id, $vessel_id, $employee_id, '$operation_type', '$operation_date', '$location', $duration_minutes, '$equipment_used', '$notes')";
            break;
            
        case 'storage_zones':
            $zone_code = mysqli_real_escape_string($conn, $_POST['zone_code']);
            $zone_name = mysqli_real_escape_string($conn, $_POST['zone_name']);
            $zone_type = mysqli_real_escape_string($conn, $_POST['zone_type']);
            $max_capacity_ton = $_POST['max_capacity_ton'];
            $temperature_condition = mysqli_real_escape_string($conn, $_POST['temperature_condition']);
            $location_coordinates = mysqli_real_escape_string($conn, $_POST['location_coordinates']);
            $is_active = $_POST['is_active'];
            
            $sql = "INSERT INTO storage_zones (zone_code, zone_name, zone_type, max_capacity_ton, temperature_condition, location_coordinates, is_active) 
                    VALUES ('$zone_code', '$zone_name', '$zone_type', $max_capacity_ton, '$temperature_condition', '$location_coordinates', $is_active)";
            break;
            
        default:
            $message = "Неизвестная таблица!";
            break;
    }
    
    // Выполнение запроса
    if (isset($sql) && !empty($sql)) {
        if (mysqli_query($conn, $sql)) {
            $message = "Данные успешно добавлены в таблицу '" . ucfirst($table) . "'!";
            $message_class = "success";
        } else {
            $message = "Ошибка: " . mysqli_error($conn);
            $message_class = "error";
        }
    }
    
    // Закрытие соединения
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Результат добавления данных</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .message { padding: 20px; margin: 20px 0; border-radius: 5px; font-size: 16px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .button { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
    </style>
</head>
<body>
    <h1>Результат операции</h1>
    
    <?php if (isset($message)): ?>
        <div class="message <?php echo $message_class; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <div>
        <a href="form_all.php" class="button">Вернуться к формам ввода</a>
        <a href="search_form.php" class="button">Перейти к поиску данных</a>
        <a href="view_all.php" class="button">Просмотр всех данных</a>
    </div>
</body>
</html>