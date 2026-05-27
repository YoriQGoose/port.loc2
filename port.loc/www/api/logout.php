<?php
session_start();

// Логируем выход
$username = $_SESSION['username'] ?? 'Неизвестный';
$logout_time = date('Y-m-d H:i:s');

// Полностью уничтожаем сессию
$_SESSION = array();

// Если нужно уничтожить куку сессии
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выход из системы</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .logout-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 50px;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        
        .logout-icon {
            font-size: 80px;
            margin-bottom: 30px;
            color: #4A00E0;
        }
        
        .logout-container h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 32px;
        }
        
        .logout-container p {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
            font-size: 18px;
        }
        
        .btn-login {
            display: inline-block;
            background: linear-gradient(135deg, #4A00E0 0%, #8E2DE2 100%);
            color: white;
            padding: 15px 40px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(142, 45, 226, 0.3);
        }
        
        .session-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }
        
        .session-info strong {
            color: #333;
        }
        
        .countdown {
            font-size: 24px;
            font-weight: 600;
            color: #4A00E0;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logout-icon">👋</div>
        <h1>Выход выполнен</h1>
        <p>Вы успешно вышли из системы управления портовыми грузами.</p>
        
        <div class="session-info">
            <p>✅ Сессия завершена: <strong><?php echo date('H:i:s'); ?></strong></p>
            <p>👤 Пользователь: <strong><?php echo htmlspecialchars($username); ?></strong></p>
            <p>🆔 ID сессии: <strong><?php echo htmlspecialchars($session_id); ?></strong></p>
        </div>
        
        <div class="countdown" id="countdown">5</div>
        <p>Через несколько секунд вы будете перенаправлены на страницу входа...</p>
        
        <a href="login.php" class="btn-login">Войти снова</a>
    </div>
    
    <script>
        // Обратный отсчет перед редиректом
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = 'login.php';
            }
        }, 1000);
        
        // Если пользователь хочет сразу перейти
        document.querySelector('.btn-login').addEventListener('click', function(e) {
            e.preventDefault();
            clearInterval(timer);
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>