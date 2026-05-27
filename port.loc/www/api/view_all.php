<?php


// Фильтруем таблицы в зависимости от роли
$allTables = [
    'employees' => 'Сотрудники',
    'cargoes' => 'Грузы',
    'vessels' => 'Суда',
    'operations' => 'Операции',
    'customers' => 'Клиенты'
];

// Для всех ролей доступны все таблицы для просмотра
$tables = $allTables;

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

session_start();
require_once 'check_access.php';
require_once 'permissions.php';

// Проверяем, есть ли право на просмотр
if (!canView()) {
    $_SESSION['access_error'] = "У вас нет прав для просмотра данных";
    header("Location: index.php");
    exit();
}


// Установка кодировки
mysqli_set_charset($conn, "utf8");

// Массив таблиц для отображения
$tables = [
    'employees' => 'Сотрудники',
    'cargoes' => 'Грузы',
    'vessels' => 'Суда',
    'operations' => 'Операции',
    'storage_zones' => 'Складские зоны'
];

// Определение русских названий полей для отображения (как в предыдущем скрипте)
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
    'cargoes' => [
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
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Просмотр всех данных портовой БД</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .table-container { margin: 30px 0; }
        .table-header { background: #4CAF50; color: white; padding: 15px; border-radius: 5px 5px 0 0; }
        .table-content { border: 1px solid #ddd; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f5f5f5; }
        .button { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
        .stats { background: #e9f7ef; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .nav-tabs { margin: 20px 0; }
        .tab-button { padding: 10px 20px; background: #6c757d; color: white; border: none; cursor: pointer; margin-right: 5px; }
        .tab-button.active { background: #007bff; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
    <script>
        function showTab(tabName) {
            // Скрыть все вкладки
            var tabContents = document.getElementsByClassName('tab-content');
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }
            
            // Убрать активный класс у всех кнопок
            var tabButtons = document.getElementsByClassName('tab-button');
            for (var i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove('active');
            }
            
            // Показать выбранную вкладку
            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }
        
        // Показать первую вкладку при загрузке
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('employees-tab').click();
        });
    </script>
</head>
<body>
    <h1>Просмотр всех данных портовой базы данных</h1>
    
    <div class="stats">
        <h3>Статистика базы данных:</h3>
        <?php
        foreach ($tables as $tableName => $tableLabel) {
            $countQuery = "SELECT COUNT(*) as count FROM $tableName";
            $countResult = mysqli_query($conn, $countQuery);
            $countRow = mysqli_fetch_assoc($countResult);
            echo "<p><strong>$tableLabel:</strong> " . $countRow['count'] . " записей</p>";
        }
        ?>
    </div>
    
    <div class="nav-tabs">
        <?php foreach ($tables as $tableName => $tableLabel): ?>
            <button class="tab-button" onclick="showTab('<?php echo $tableName; ?>')" 
                    id="<?php echo $tableName; ?>-tab">
                <?php echo $tableLabel; ?>
            </button>
        <?php endforeach; ?>
    </div>
    
    <?php foreach ($tables as $tableName => $tableLabel): ?>
        <div id="<?php echo $tableName; ?>" class="tab-content">
            <div class="table-container">
                <div class="table-header">
                    <h2><?php echo $tableLabel; ?></h2>
                </div>
                <div class="table-content">
                    <?php
                    // Запрос для получения данных таблицы
                    $query = "SELECT * FROM $tableName ORDER BY 1 DESC LIMIT 50";
                    $result = mysqli_query($conn, $query);
                    $rowCount = mysqli_num_rows($result);
                    
                    if ($rowCount > 0) {
                        echo "<table>";
                        echo "<thead><tr>";
                        
                        // Получаем первую строку для определения столбцов
                        $firstRow = mysqli_fetch_assoc($result);
                        mysqli_data_seek($result, 0); // Возвращаем указатель на начало
                        
                        // Выводим заголовки таблицы
                        foreach ($firstRow as $key => $value) {
                            $header = isset($fieldNames[$tableName][$key]) ? $fieldNames[$tableName][$key] : $key;
                            echo "<th>$header</th>";
                        }
                        
                        echo "</tr></thead><tbody>";
                        
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
                        
                        echo "</tbody></table>";
                        echo "<p class='stats'>Показано: $rowCount записей (первые 50)</p>";
                    } else {
                        echo "<p>В таблице нет данных.</p>";
                    }
                    
                    mysqli_free_result($result);
                    ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    <div>
        <a href="form_all.php" class="button">Ввод данных</a>
        <a href="search_form.php" class="button">Поиск данных</a>
    </div>
    <div style="margin: 20px 0;">
    <a href="update_form.php" style="padding: 10px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin: 0 5px;">Обновление данных</a>
    <a href="delete_form.php" style="padding: 10px 15px; background: #f44336; color: white; text-decoration: none; border-radius: 5px; margin: 0 5px;">Удаление данных</a>
	</div>
    <?php
    // Закрытие соединения
    mysqli_close($conn);
    ?>
</body>
</html>