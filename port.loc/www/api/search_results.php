<?php

session_start();
require_once 'check_access.php';
require_once 'permissions.php';

// Проверяем, есть ли право на поиск/просмотр
if (!canView()) {
    $_SESSION['access_error'] = "У вас нет прав для поиска данных";
    header("Location: index.php");
    exit();
}

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

// Получение параметров поиска
$table = isset($_GET['table']) ? $_GET['table'] : '';
$field = isset($_GET['field']) ? $_GET['field'] : '';
$operator = isset($_GET['operator']) ? $_GET['operator'] : '=';
$value = isset($_GET['value']) ? $_GET['value'] : '';

// Безопасная обработка ввода
$table = mysqli_real_escape_string($conn, $table);
$field = mysqli_real_escape_string($conn, $field);
$operator = mysqli_real_escape_string($conn, $operator);
$value = mysqli_real_escape_string($conn, $value);

// Определение названий таблиц для отображения
$tableNames = [
    'employees' => 'Сотрудники',
    'cargo' => 'Грузы',
    'vessels' => 'Суда',
    'operations' => 'Операции',
    'storage_zones' => 'Складские зоны'
];

// Определение русских названий полей для отображения
$fieldNames = [
    'employees' => [
        'employee_id' => 'ID',
        'first_name' => 'Имя',
        'last_name' => 'Фамилия',
        'position' => 'Должность',
        'department' => 'Отдел',
        'hire_date' => 'Дата приема',
        'salary' => 'Зарплата',
        'email' => 'Email',
        'phone' => 'Телефон',
        'created_at' => 'Дата создания'
    ],
    'cargo' => [
        'cargo_id' => 'ID',
        'cargo_code' => 'Код груза',
        'description' => 'Описание',
        'cargo_type' => 'Тип груза',
        'weight_kg' => 'Вес (кг)',
        'volume_m3' => 'Объем (м³)',
        'owner_company' => 'Компания-владелец',
        'destination_port' => 'Порт назначения',
        'arrival_date' => 'Дата прибытия',
        'departure_date' => 'Дата отправки',
        'status' => 'Статус',
        'created_at' => 'Дата создания'
    ],
    'vessels' => [
        'vessel_id' => 'ID',
        'vessel_name' => 'Название судна',
        'imo_number' => 'IMO номер',
        'flag_country' => 'Страна флага',
        'vessel_type' => 'Тип судна',
        'capacity_tonnage' => 'Вместимость (т)',
        'year_built' => 'Год постройки',
        'current_status' => 'Текущий статус',
        'port_of_registry' => 'Порт приписки',
        'created_at' => 'Дата создания'
    ],
    'operations' => [
        'operation_id' => 'ID операции',
        'cargo_id' => 'ID груза',
        'vessel_id' => 'ID судна',
        'employee_id' => 'ID сотрудника',
        'operation_type' => 'Тип операции',
        'operation_date' => 'Дата и время операции',
        'location' => 'Место проведения',
        'notes' => 'Примечания',
        'duration_minutes' => 'Длительность (мин)',
        'equipment_used' => 'Оборудование',
        'created_at' => 'Дата создания'
    ],
    'storage_zones' => [
        'zone_id' => 'ID зоны',
        'zone_code' => 'Код зоны',
        'zone_name' => 'Название зоны',
        'zone_type' => 'Тип зоны',
        'max_capacity_ton' => 'Макс. вместимость (т)',
        'current_occupancy' => 'Текущая загрузка (т)',
        'temperature_condition' => 'Температурный режим',
        'responsible_employee_id' => 'Ответственный сотрудник',
        'location_coordinates' => 'Координаты',
        'is_active' => 'Активна',
        'created_at' => 'Дата создания'
    ]
];

// Формирование SQL запроса
$sql = "SELECT * FROM $table";
$whereClause = "";

if (!empty($field) && !empty($value)) {
    if ($operator === 'LIKE') {
        $whereClause = " WHERE $field LIKE '%$value%'";
    } else {
        $whereClause = " WHERE $field $operator '$value'";
    }
    $sql .= $whereClause;
}

// Ограничение для предотвращения вывода всех данных при пустом поиске
if (empty($field) || empty($value)) {
    $sql .= " LIMIT 50";
}

// Выполнение запроса
$result = mysqli_query($conn, $sql);
$rowCount = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Результаты поиска</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .search-info { background: #e9f7ef; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .results-container { margin: 20px 0; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f5f5f5; }
        .button { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
        .no-results { padding: 20px; background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; border-radius: 5px; }
        .query-info { background: #d1ecf1; color: #0c5460; padding: 10px; margin: 10px 0; border-radius: 5px; font-family: monospace; }
    </style>
</head>
<body>
    <h1>Результаты поиска</h1>
    
    <div class="search-info">
        <h3>Параметры поиска:</h3>
        <p><strong>Таблица:</strong> <?php echo isset($tableNames[$table]) ? $tableNames[$table] : $table; ?></p>
        <?php if (!empty($field) && !empty($value)): ?>
            <p><strong>Условие:</strong> 
                <?php 
                    $fieldLabel = isset($fieldNames[$table][$field]) ? $fieldNames[$table][$field] : $field;
                    echo $fieldLabel . ' ' . $operator . ' "' . htmlspecialchars($value) . '"';
                ?>
            </p>
        <?php endif; ?>
        <p><strong>Найдено записей:</strong> <?php echo $rowCount; ?></p>
    </div>
    
    <?php if (!empty($sql)): ?>
        <div class="query-info">
            <strong>Выполненный SQL запрос:</strong><br>
            <?php echo htmlspecialchars($sql); ?>
        </div>
    <?php endif; ?>
    
    <div class="results-container">
        <?php if ($rowCount > 0): ?>
            <h2>Результаты (<?php echo $rowCount; ?> записей)</h2>
            <table>
                <thead>
                    <tr>
                        <?php 
                        // Получаем первую строку для определения столбцов
                        $firstRow = mysqli_fetch_assoc($result);
                        mysqli_data_seek($result, 0); // Возвращаем указатель на начало
                        
                        // Выводим заголовки таблицы
                        foreach ($firstRow as $key => $value) {
                            $header = isset($fieldNames[$table][$key]) ? $fieldNames[$table][$key] : $key;
                            echo "<th>$header</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Выводим все строки
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        foreach ($row as $key => $value) {
                            // Форматируем специальные значения
                            $displayValue = $value;
                            
                            if ($value === null || $value === '') {
                                $displayValue = '<span style="color: #999;">—</span>';
                            } elseif ($key === 'is_active') {
                                $displayValue = $value ? 'Да' : 'Нет';
                            } elseif ($key === 'salary' || $key === 'weight_kg' || $key === 'volume_m3' || 
                                     $key === 'capacity_tonnage' || $key === 'max_capacity_ton') {
                                $displayValue = number_format($value, 2, ',', ' ');
                            }
                            
                            echo "<td>$displayValue</td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-results">
                <h3>Записей не найдено</h3>
                <p>По вашему запросу не найдено ни одной записи.</p>
                <p>Попробуйте изменить параметры поиска.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div>
        <a href="search_form.php" class="button">Новый поиск</a>
        <a href="form_all.php" class="button">Ввод данных</a>
        <a href="view_all.php" class="button">Просмотр всех данных</a>
    </div>
    
    <?php
    // Закрытие соединения
    mysqli_free_result($result);
    mysqli_close($conn);
    ?>
</body>
</html>