<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Обработка формы
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Валидация
    if (empty($username)) {
        $error = 'Введите имя пользователя';
    } elseif (empty($password)) {
        $error = 'Введите пароль';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен содержать не менее 6 символов';
    } elseif ($password !== $confirm_password) {
        $error = 'Пароли не совпадают';
    } else {
        $success = true;
        
        // В реальном приложении здесь должно быть:
        // 1. Проверка существования пользователя в БД
        // 2. Хеширование пароля: password_hash($password, PASSWORD_DEFAULT)
        // 3. Сохранение в БД или проверка авторизации
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поля пароля - Лабораторная работа №12</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .result-box { background: #fff3e0; border: 2px solid #fd7e14; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .error-message { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .success-message { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .back-btn { display: inline-block; padding: 10px 20px; background: #fd7e14; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; margin-right: 10px; }
        h1 { color: #fd7e14; margin-bottom: 20px; }
        .data-item { background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🔐 Результат обработки полей пароля</h1>
            
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <?php if ($error): ?>
                    <div class="error-message">
                        <h3>❌ Ошибка:</h3>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php elseif ($success): ?>
                    <div class="success-message">
                        <h3>✅ Успешно!</h3>
                        <p>Данные формы успешно получены и прошли валидацию.</p>
                    </div>
                <?php endif; ?>
                
                <div class="result-box">
                    <h3>📋 Полученные данные:</h3>
                    
                    <div class="data-item">
                        <strong>Имя пользователя:</strong> 
                        <?php echo htmlspecialchars($username); ?>
                    </div>
                    
                    <div class="data-item">
                        <strong>Длина пароля:</strong> 
                        <?php echo strlen($password); ?> символов
                    </div>
                    
                    <div class="data-item">
                        <strong>Хеш пароля (MD5 для демонстрации):</strong><br>
                        <code style="word-break: break-all;"><?php echo md5($password); ?></code>
                    </div>
                    
                    <div class="data-item">
                        <strong>Пароли совпадают:</strong> 
                        <?php echo $password === $confirm_password ? '✅ Да' : '❌ Нет'; ?>
                    </div>
                    
                    <h4>Все переданные данные POST:</h4>
                    <pre style="background: white; padding: 15px; border-radius: 5px; overflow: auto;"><?php 
                    $post_data = $_POST;
                    $post_data['password'] = '******** (скрыто)';
                    $post_data['confirm_password'] = '******** (скрыто)';
                    print_r($post_data); 
                    ?></pre>
                    
                    <div style="background: #e8f4fc; padding: 15px; border-radius: 8px; margin-top: 20px;">
                        <h4>⚠️ Важные замечания о безопасности:</h4>
                        <ol style="margin-left: 20px;">
                            <li>В реальном приложении НИКОГДА не храните пароли в открытом виде</li>
                            <li>Используйте функцию <code>password_hash()</code> для хеширования паролей</li>
                            <li>Для проверки пароля используйте <code>password_verify()</code></li>
                            <li>Минимальная длина пароля - 8 символов</li>
                            <li>Требуйте от пользователей использовать сложные пароли</li>
                        </ol>
                    </div>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <h3>⚠️ Форма не отправлена</h3>
                    <p>Для отображения результатов отправьте форму из основной страницы лабораторной работы.</p>
                </div>
            <?php endif; ?>
            
            <a href="lab12_index.php" class="back-btn">Вернуться к лабораторной работе</a>
            <a href="index.php" class="back-btn" style="background: #6c757d;">На главную</a>
        </div>
    </div>
</body>
</html>