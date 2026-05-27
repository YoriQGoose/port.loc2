<?php
session_start();
// Определяем пользователей (в реальном приложении храним в БД)
$users = [
    'admin' => [
        'password' => 'admin123',
        'name' => 'Администратор',
        'role' => 'admin',
        'permissions' => ['view', 'add', 'edit', 'delete', 'manage_users']
    ],
    'operator' => [
        'password' => 'operator123',
        'name' => 'Оператор',
        'role' => 'operator',
        'permissions' => ['view', 'add', 'edit']
    ],
    'viewer' => [
        'password' => 'viewer123',
        'name' => 'Просмотрщик',
        'role' => 'viewer',
        'permissions' => ['view']
    ]
];

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $error = 'Пожалуйста, введите логин и пароль';
    } elseif (isset($users[$username]) && $users[$username]['password'] === $password) {
        // Успешная авторизация
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_name'] = $users[$username]['name'];
        $_SESSION['user_role'] = $users[$username]['role'];
        $_SESSION['permissions'] = $users[$username]['permissions']; // Сохраняем разрешения
        $_SESSION['login_time'] = time();
        
        // Редирект на главную
        header("Location: index.php");
        exit();
    } else {
        $error = 'Неверный логин или пароль';
    }
}

// Если пользователь уже авторизован, перенаправляем на главную
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit();
}

// Определяем пользователей (в реальном приложении храним в БД)
$users = [
    'admin' => [
        'password' => 'admin123',
        'name' => 'Администратор',
        'role' => 'admin'
    ],
    'operator' => [
        'password' => 'operator123',
        'name' => 'Оператор',
        'role' => 'operator'
    ],
    'viewer' => [
        'password' => 'viewer123',
        'name' => 'Просмотрщик',
        'role' => 'viewer'
    ]
];

$error = '';
$username = '';

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $error = 'Пожалуйста, введите логин и пароль';
    } elseif (isset($users[$username]) && $users[$username]['password'] === $password) {
        // Успешная авторизация
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_name'] = $users[$username]['name'];
        $_SESSION['user_role'] = $users[$username]['role'];
        $_SESSION['login_time'] = time();
        
        // Редирект на главную
        header("Location: index.php");
        exit();
    } else {
        $error = 'Неверный логин или пароль';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация - Портовое управление</title>
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
        
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #4A00E0 0%, #8E2DE2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            opacity: 0.9;
            font-size: 16px;
        }
        
        .login-form {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #8E2DE2;
            box-shadow: 0 0 0 3px rgba(142, 45, 226, 0.1);
        }
        
        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #fcc;
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #4A00E0 0%, #8E2DE2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(142, 45, 226, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        
        .demo-credentials {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 13px;
        }
        
        .demo-credentials h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .demo-credentials ul {
            list-style: none;
            padding-left: 20px;
        }
        
        .demo-credentials li {
            margin-bottom: 5px;
            color: #666;
        }
        
        .demo-credentials strong {
            color: #333;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🔐 Авторизация</h1>
            <p>Система управления портовыми грузами</p>
        </div>
        
        <div class="login-form">
            <?php if ($error): ?>
                <div class="error-message">
                    ⚠️ <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Логин:</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($username); ?>"
                           required
                           autocomplete="username"
                           autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           required
                           autocomplete="current-password">
                </div>
                
                <button type="submit" class="btn-login">
                    Войти в систему
                </button>
            </form>
            
            <div class="demo-credentials">
                <h4>📋 Демо-доступы:</h4>
                <ul>
                    <li><strong>admin</strong> / <strong>admin123</strong> (Администратор)</li>
                    <li><strong>operator</strong> / <strong>operator123</strong> (Оператор)</li>
                    <li><strong>viewer</strong> / <strong>viewer123</strong> (Просмотрщик)</li>
                </ul>
            </div>
            
            <div class="login-footer">
                <p>© 2025 Портовое управление. Все права защищены.</p>
            </div>
        </div>
    </div>
    
    <script>
        // Добавляем эффект при загрузке
        document.addEventListener('DOMContentLoaded', function() {
            const loginContainer = document.querySelector('.login-container');
            loginContainer.style.opacity = '0';
            loginContainer.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                loginContainer.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                loginContainer.style.opacity = '1';
                loginContainer.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>