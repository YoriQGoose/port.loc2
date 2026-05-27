<?php
session_start();
require_once 'check_access.php';
require_once 'permissions.php';

if (!canView()) {
    $_SESSION['access_error'] = "У вас нет прав для доступа";
    header("Location: index.php");
    exit();
}

$message = '';
$answer = '';
$first_number = '';
$second_number = '';

if (isset($_POST['posted'])) {
    $first_number = $_POST['first_number'];
    $second_number = $_POST['second_number'];
    
    // Проверка на пустые значения
    if ($first_number === '' || $second_number === '') {
        $message = 'Ошибка: Пожалуйста, введите оба числа';
        $message_class = 'error';
    } else {
        // Проверка на деление на ноль
        if ($second_number == 0) {
            $message = 'На ноль делить нельзя!!!';
            $message_class = 'error';
        } else {
            $answer = $first_number / $second_number;
            $message = "Операция выполнена успешно!";
            $message_class = 'success';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Пример 1: Оператор IF</title>
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
            background: linear-gradient(135deg, #4A00E0 0%, #8E2DE2 100%);
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
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: 600;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            margin-bottom: 15px;
        }
        
        button {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
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
        
        .answer-box {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Пример 1: Использование оператора IF</h1>
            <p style="text-align: center; opacity: 0.9;">Деление чисел с проверкой на ноль</p>
        </header>
        
        <div class="form-container">
            <form method="POST" action="">
                <input type="hidden" name="posted" value="true">
                
                <label>Первое число (делимое):</label>
                <input type="text" name="first_number" value="<?php echo htmlspecialchars($first_number); ?>" 
                       placeholder="Введите число" required>
                
                <label>Второе число (делитель):</label>
                <input type="text" name="second_number" value="<?php echo htmlspecialchars($second_number); ?>" 
                       placeholder="Введите число (не ноль)" required>
                
                <button type="submit">Разделить</button>
            </form>
        </div>
        
        <?php if ($message): ?>
            <div class="result-box <?php echo $message_class; ?>">
                <strong>Результат:</strong> <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($answer !== ''): ?>
            <div class="answer-box">
                <?php echo htmlspecialchars($first_number); ?> ÷ <?php echo htmlspecialchars($second_number); ?> = 
                <span style="color: #4CAF50;"><?php echo number_format($answer, 2); ?></span>
            </div>
        <?php endif; ?>
        
        <div class="nav-buttons">
            <a href="lab13_index.php" class="nav-btn">← Назад к списку</a>
            <a href="lab13_example2.php" class="nav-btn primary">Следующий пример →</a>
        </div>
    </div>
</body>
</html>