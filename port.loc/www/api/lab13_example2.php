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
$number = rand(1, 10);
$guess = '';

if (isset($_POST['posted'])) {
    $guess = $_POST['guess'];
    
    if ($guess === '') {
        $message = 'Пожалуйста, введите число от 1 до 10';
        $message_class = 'error';
    } elseif (!is_numeric($guess)) {
        $message = 'Пожалуйста, введите корректное число';
        $message_class = 'error';
    } else {
        $guess = (int)$guess;
        
        if ($guess > $number) {
            $message = "Ваше число слишком большое!";
            $message_class = 'error';
        } elseif ($guess < $number) {
            $message = "Ваше число слишком маленькое!";
            $message_class = 'error';
        } else {
            $message = "Поздравляем! Вы угадали число!";
            $message_class = 'success';
        }
    }
} else {
    $number = rand(1, 10); // Генерируем новое число при загрузке страницы
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Пример 2: Операторы сравнения</title>
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
            background: linear-gradient(135deg, #FF9800 0%, #FF5722 100%);
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
            background: #fff8e1;
            padding: 30px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }
        
        .number-display {
            font-size: 72px;
            color: #FF9800;
            margin: 20px 0;
            text-align: center;
        }
        
        input[type="number"] {
            width: 200px;
            padding: 15px;
            border: 3px solid #FF9800;
            border-radius: 8px;
            font-size: 24px;
            text-align: center;
            margin: 20px 0;
        }
        
        button {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 20px;
        }
        
        .result-box {
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
            font-size: 18px;
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
            background: #e3f2fd;
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
            background: #2196F3;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Пример 2: Операторы сравнения</h1>
            <p style="text-align: center; opacity: 0.9;">Игра "Угадай число от 1 до 10"</p>
        </header>
        
        <div class="info-box">
            <p><strong>Описание:</strong> Программа загадывает случайное число от 1 до 10. Попробуйте угадать его!</p>
            <p>Используются операторы сравнения: > (больше), < (меньше), == (равно)</p>
        </div>
        
        <div class="form-container">
            <form method="POST" action="">
                <input type="hidden" name="posted" value="true">
                <input type="hidden" name="number" value="<?php echo $number; ?>">
                
                <h2>Введите число от 1 до 10:</h2>
                <input type="number" name="guess" value="<?php echo htmlspecialchars($guess); ?>" 
                       min="1" max="10" step="1" required>
                <br>
                <button type="submit">Проверить</button>
            </form>
        </div>
        
        <?php if ($message): ?>
            <div class="result-box <?php echo $message_class; ?>">
                <strong>Результат:</strong> <?php echo $message; ?>
                <?php if ($message_class == 'error' && isset($number)): ?>
                    <br><small>Загаданное число было: <?php echo $number; ?></small>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($message_class == 'success'): ?>
            <div class="form-container" style="margin-top: 20px;">
                <button onclick="window.location.reload()">Новая игра</button>
            </div>
        <?php endif; ?>
        
        <div class="nav-buttons">
            <a href="lab13_example1.php" class="nav-btn">← Предыдущий пример</a>
            <a href="lab13_index.php" class="nav-btn">Назад к списку</a>
            <a href="lab13_example3.php" class="nav-btn primary">Следующий пример →</a>
        </div>
    </div>
</body>
</html>