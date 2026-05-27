<?php
session_start();
require_once 'check_access.php';
require_once 'permissions.php';

if (!canView()) {
    $_SESSION['access_error'] = "У вас нет прав для доступа";
    header("Location: index.php");
    exit();
}

$result_message = '';
$first_name = '';
$last_name = '';
$age = '';
$address = '';
$license = '';

if (isset($_POST['posted'])) {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $age = $_POST['age'] ?? '';
    $address = $_POST['address'] ?? '';
    $license = isset($_POST['license']) ? true : false;
    
    // Проверка заполнения обязательных полей
    if (empty($first_name) || empty($last_name) || empty($age)) {
        $result_message = 'Пожалуйста, заполните все обязательные поля';
        $result_class = 'error';
    } elseif (!is_numeric($age) || $age < 0) {
        $result_message = 'Пожалуйста, введите корректный возраст';
        $result_class = 'error';
    } else {
        $age = (int)$age;
        
        // Использование логических операторов
        if ($age >= 21 && $license) {
            $result_message = "Уважаемый(ая) $first_name $last_name. Вам можно предоставить автомобиль напрокат!";
            $result_class = 'success';
        } elseif ($age < 21 || !$license) {
            $reason = '';
            if ($age < 21) $reason .= 'возраст меньше 21 года';
            if (!$license) {
                if ($reason) $reason .= ' и ';
                $reason .= 'отсутствие водительских прав';
            }
            $result_message = "Уважаемый(ая) $first_name $last_name. К сожалению, мы не можем предоставить Вам автомобиль напрокат ($reason).";
            $result_class = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Пример 3: Логические операторы</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        header {
            background: linear-gradient(135deg, #2196F3 0%, #21CBF3 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .form-container {
            background: #f0f7ff;
            padding: 30px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        textarea {
            height: 100px;
            resize: vertical;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
        }
        
        input[type="checkbox"] {
            width: 20px;
            height: 20px;
        }
        
        button {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        
        .result-box {
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .info-box {
            background: #e8f5e9;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .nav-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .nav-btn {
            padding: 12px 25px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            text-align: center;
            flex: 1;
        }
        
        .nav-btn.primary {
            background: #4CAF50;
        }
        
        .requirements {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Пример 3: Логические операторы</h1>
            <p style="text-align: center; opacity: 0.9;">Проверка условий для аренды автомобиля</p>
        </header>
        
        <div class="requirements">
            <h3>📋 Требования для аренды:</h3>
            <p>Для получения автомобиля напрокат необходимо:</p>
            <ul>
                <li>Возраст 21 год или старше (оператор AND: age >= 21)</li>
                <li>Наличие действующих водительских прав (оператор AND: license == true)</li>
            </ul>
            <p><strong>Условие:</strong> (age >= 21) AND (license == true)</p>
        </div>
        
        <div class="form-container">
            <form method="POST" action="">
                <input type="hidden" name="posted" value="true">
                
                <div class="form-group">
                    <label>Имя:</label>
                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Фамилия:</label>
                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Возраст:</label>
                    <input type="number" name="age" value="<?php echo htmlspecialchars($age); ?>" min="16" max="100" required>
                </div>
                
                <div class="form-group">
                    <label>Адрес:</label>
                    <textarea name="address"><?php echo htmlspecialchars($address); ?></textarea>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" name="license" id="license" <?php echo $license ? 'checked' : ''; ?>>
                    <label for="license" style="margin: 0;">Мои водительские права действительны</label>
                </div>
                
                <button type="submit">Отправить заявку</button>
            </form>
        </div>
        
        <?php if ($result_message): ?>
            <div class="result-box <?php echo $result_class; ?>">
                <strong>Результат проверки:</strong><br>
                <?php echo $result_message; ?>
            </div>
        <?php endif; ?>
        
        <div class="info-box">
            <h3>Используемые логические операторы:</h3>
            <ul>
                <li><strong>AND (&&)</strong> - оба условия должны быть true</li>
                <li><strong>OR (||)</strong> - хотя бы одно условие должно быть true</li>
                <li><strong>NOT (!)</strong> - инвертирует значение условия</li>
            </ul>
        </div>
        
        <div class="nav-buttons">
            <a href="lab13_example2.php" class="nav-btn">← Предыдущий пример</a>
            <a href="lab13_index.php" class="nav-btn">Назад к списку</a>
            <a href="lab13_index.php" class="nav-btn primary">Завершить</a>
        </div>
    </div>
</body>
</html>