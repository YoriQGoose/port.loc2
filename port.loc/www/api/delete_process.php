<?php
session_start();
require_once 'check_access.php';
require_once 'permissions.php';

// Проверяем, есть ли право на удаление
if (!canDelete()) {
    $_SESSION['access_error'] = "У вас нет прав для удаления данных";
    header("Location: index.php");
    exit();
}



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
    die("Ошибка подключения: " . mysqli_connect_error());
}

// Установка кодировки
mysqli_set_charset($conn, "utf8");

// Обработка удаления данных
if (isset($_POST['table']) && isset($_POST['record_id'])) {
    $table = $_POST['table'];
    $record_id = $_POST['record_id'];
    $confirmed = isset($_POST['confirmed']) ? $_POST['confirmed'] : 0;
    
    // Определение поля ID для каждой таблицы
    $id_fields = [
        'employees' => 'employee_id',
        'cargoes' => 'cargo_id',
        'vessels' => 'vessel_id',
        'operations' => 'operation_id',
        'customers' => 'customer_id'
    ];
    
    if (isset($id_fields[$table])) {
        $id_field = $id_fields[$table];
        
        // Проверяем существование записи перед удалением
        $check_sql = "SELECT * FROM $table WHERE $id_field = $record_id";
        $check_result = mysqli_query($conn, $check_sql);
        
        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $record_data = mysqli_fetch_assoc($check_result);
            
            if ($confirmed == 1) {
                // Удаляем запись
                $sql = "DELETE FROM $table WHERE $id_field = $record_id";
                
                if (mysqli_query($conn, $sql)) {
                    $deleted_rows = mysqli_affected_rows($conn);
                    
                    if ($deleted_rows > 0) {
                        $message = "Запись успешно удалена!";
                        $message_class = "success";
                        
                        // Сохраняем информацию об удаленной записи для отображения
                        $deleted_record = $record_data;
                    } else {
                        $message = "Не удалось удалить запись. Возможно, она уже была удалена.";
                        $message_class = "error";
                    }
                } else {
                    $message = "Ошибка при удалении: " . mysqli_error($conn);
                    $message_class = "error";
                }
            } else {
                // Показываем информацию о записи для подтверждения
                $show_confirmation = true;
                $record_info = $record_data;
            }
        } else {
            $message = "Запись с ID $record_id не найдена в таблице!";
            $message_class = "error";
        }
    } else {
        $message = "Неизвестная таблица!";
        $message_class = "error";
    }
} else {
    $message = "Не указаны параметры для удаления!";
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

// Функция для форматирования значения
function formatValue($value) {
    if ($value === null || $value === '') {
        return '<span style="color: #999;">—</span>';
    }
    return htmlspecialchars($value);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Результат удаления данных</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .message { padding: 20px; margin: 20px 0; border-radius: 5px; font-size: 16px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .button { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
        .button-red { background: #f44336; }
        .record-info { background: #ffebee; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 5px solid #f44336; }
        .record-table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        .record-table th, .record-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .record-table th { background-color: #f2f2f2; }
        .query-info { background: #d1ecf1; color: #0c5460; padding: 10px; margin: 10px 0; border-radius: 5px; font-family: monospace; }
    </style>
</head>
<body>
    <h1>Результат удаления данных</h1>
    
    <?php if (isset($show_confirmation) && $show_confirmation): ?>
        <div class="message warning">
            <h3>⚠️ Подтвердите удаление</h3>
            <p>Вы собираетесь удалить следующую запись из таблицы "<?php echo getTableName($table); ?>":</p>
            
            <div class="record-info">
                <table class="record-table">
                    <?php foreach ($record_info as $key => $value): ?>
                        <tr>
                            <th><?php echo htmlspecialchars($key); ?></th>
                            <td><?php echo formatValue($value); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            
            <p>Эта операция необратима! Вы уверены, что хотите продолжить?</p>
            
            <form method="POST" action="delete_process.php">
                <input type="hidden" name="table" value="<?php echo htmlspecialchars($table); ?>">
                <input type="hidden" name="record_id" value="<?php echo htmlspecialchars($record_id); ?>">
                <input type="hidden" name="confirmed" value="1">
                
                <button type="submit" class="button-red">Да, удалить</button>
                <a href="delete_form.php" class="button">Отмена</a>
            </form>
        </div>
        
    <?php elseif (isset($message)): ?>
        <div class="message <?php echo $message_class; ?>">
            <h3>Таблица: <?php echo getTableName($table); ?></h3>
            <p><?php echo $message; ?></p>
            
            <?php if (isset($deleted_record)): ?>
                <div class="record-info">
                    <h4>Удаленная запись:</h4>
                    <table class="record-table">
                        <?php foreach ($deleted_record as $key => $value): ?>
                            <tr>
                                <th><?php echo htmlspecialchars($key); ?></th>
                                <td><?php echo formatValue($value); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
            
            <?php if (isset($sql)): ?>
                <div class="query-info">
                    <strong>Выполненный SQL запрос:</strong><br>
                    <?php echo htmlspecialchars($sql); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div>
        <a href="delete_form.php" class="button">Вернуться к удалению</a>
        <a href="update_form.php" class="button">Обновление данных</a>
        <a href="form_all.php" class="button">Ввод данных</a>
        <a href="view_all.php" class="button">Просмотр данных</a>
    </div>
</body>
</html>