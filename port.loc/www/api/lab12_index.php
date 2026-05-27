<?php
session_start();
// Если пользователь не авторизован, перенаправляем на страницу входа
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Подключаем файлы для проверки прав
require_once 'check_access.php';
require_once 'permissions.php';

// Инициализируем разрешения для роли (если еще не установлены)
$role_permissions = [
    'admin' => ['view', 'add', 'edit', 'delete', 'manage_users'],
    'operator' => ['view', 'add', 'edit'],
    'viewer' => ['view']
];

if (!isset($_SESSION['permissions']) && isset($_SESSION['user_role'])) {
    $role = $_SESSION['user_role'];
    $_SESSION['permissions'] = $role_permissions[$role] ?? ['view'];
}

// Переменные для отображения информации о сессии
$login_time = $_SESSION['login_time'] ?? time();
$session_duration = time() - $login_time;
$session_minutes = floor($session_duration / 60);
$session_seconds = $session_duration % 60;

$session_timeout = 1800; // 30 минут
$time_remaining = $session_timeout - $session_duration;
$minutes_remaining = floor($time_remaining / 60);
$seconds_remaining = $time_remaining % 60;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа №12 - Портовое управление</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, #fd7e14 0%, #e0a800 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(253, 126, 20, 0.2);
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .header-top h1 {
            font-size: 32px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .user-role {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
        }
        
        .logout-btn, .back-btn {
            display: inline-block;
            background: white;
            color: #fd7e14;
            padding: 10px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.3s ease;
            margin-left: 10px;
        }
        
        .logout-btn:hover, .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
        }
        
        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .back-btn:hover {
            background: white;
            color: #fd7e14;
        }
        
        .session-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .session-info h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .info-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
        }
        
        .info-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 18px;
            font-weight: 600;
        }
        
        .main-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .card h2 {
            color: #fd7e14;
            margin-bottom: 20px;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card h3 {
            color: #444;
            margin: 20px 0 15px 0;
            font-size: 20px;
        }
        
        .card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #fd7e14;
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .checkbox-group, .radio-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin: 15px 0;
        }
        
        .checkbox-item, .radio-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-item input, .radio-item input {
            width: 18px;
            height: 18px;
        }
        
        .checkbox-item label, .radio-item label {
            font-size: 16px;
            color: #444;
        }
        
        select.form-control {
            padding: 10px 15px;
            background: white;
        }
        
        select.form-control[multiple] {
            min-height: 120px;
        }
        
        .hidden-info {
            background: #f8f9fa;
            border: 2px dashed #fd7e14;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            font-family: monospace;
            font-size: 14px;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 25px;
            text-align: center;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 16px;
            min-width: 120px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #fd7e14 0%, #e0a800 100%);
            color: white;
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #fd7e14;
            border: 2px solid #fd7e14;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(253, 126, 20, 0.3);
        }
        
        .example-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .example-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid #fd7e14;
            transition: transform 0.3s ease;
        }
        
        .example-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .example-card h4 {
            color: #fd7e14;
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .example-card p {
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .example-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #fd7e14;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background 0.3s ease;
        }
        
        .example-btn:hover {
            background: #e0a800;
        }
        
        .result-box {
            background: #fff3e0;
            border: 2px solid #fd7e14;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .result-box h4 {
            color: #fd7e14;
            margin-bottom: 15px;
        }
        
        .result-item {
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
            margin: 15px 0;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            margin: 15px 0;
        }
        
        .code-block {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Consolas', monospace;
            overflow-x: auto;
            margin: 15px 0;
            font-size: 14px;
        }
        
        .tasks-list {
            padding-left: 20px;
            margin: 15px 0;
        }
        
        .tasks-list li {
            margin-bottom: 10px;
            color: #444;
        }
        
        footer {
            text-align: center;
            color: #666;
            padding: 20px;
            margin-top: 40px;
            border-top: 1px solid #ddd;
        }
        
        .password-field {
            letter-spacing: 3px;
        }
        
        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .user-info {
                text-align: center;
            }
            
            .example-grid {
                grid-template-columns: 1fr;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-top">
                <h1>🔒 Лабораторная работа №12</h1>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                    <div class="user-role"><?php echo htmlspecialchars($_SESSION['user_role']); ?></div>
                    <a href="index.php" class="back-btn">На главную</a>
                    <a href="logout.php" class="logout-btn">Выйти</a>
                </div>
            </div>
            
            <div class="session-info">
                <h3>📊 Информация о сессии</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">ID сессии</div>
                        <div class="info-value"><?php echo session_id(); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Длительность сессии</div>
                        <div class="info-value"><?php echo $session_minutes; ?> мин <?php echo $session_seconds; ?> сек</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Сессия истекает через</div>
                        <div class="info-value"><?php echo $minutes_remaining; ?> мин <?php echo $seconds_remaining; ?> сек</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Статус</div>
                        <div class="info-value" id="sessionStatus">✅ Активна</div>
                    </div>
                </div>
                
                <div class="session-controls">
                    <button class="session-btn session-btn-refresh" onclick="refreshSession()">
                        ♻️ Обновить сессию
                    </button>
                    <button class="session-btn session-btn-destroy" onclick="destroySession()">
                        🗑️ Завершить сессию
                    </button>
                </div>
            </div>
        </header>
        
        <main class="main-content">
            <div class="card">
                <h2>📚 Теоретическое обоснование</h2>
                <p>В этой лабораторной работе рассматриваются дополнительные элементы HTML-форм и методы проверки данных.</p>
                
                <h3>Элементы HTML-форм:</h3>
                <ul class="tasks-list">
                    <li><strong>Скрытые поля (hidden)</strong> - передача данных без отображения пользователю</li>
                    <li><strong>Поля ввода паролей (password)</strong> - маскировка вводимых символов</li>
                    <li><strong>Кнопки (submit, reset)</strong> - отправка и сброс данных формы</li>
                    <li><strong>Проверка обязательных полей</strong> - валидация данных на стороне сервера</li>
                </ul>
                
                <h3>Безопасность передачи данных:</h3>
                <div class="code-block">
// Метод GET - данные видны в URL (небезопасно для паролей)
&lt;form method="GET" action="обработчик.php"&gt;

// Метод POST - данные скрыты в теле запроса (более безопасно)
&lt;form method="POST" action="обработчик.php"&gt;

// Скрытое поле для передачи служебной информации
&lt;input type="hidden" name="secret_data" value="значение"&gt;

// Поле для ввода пароля
&lt;input type="password" name="user_password"&gt;
                </div>
            </div>
            
            <div class="card">
                <h2>1️⃣ Скрытые поля форм (Hidden Fields)</h2>
                <p>Скрытые поля позволяют передавать данные между страницами без отображения их пользователю.</p>
                
                <form method="GET" action="lab12_hidden.php" class="form-group">
                    <div class="form-group">
                        <label class="form-label">Кто из следующих персонажей получит приз?</label>
                        <select name="ListBox" class="form-control">
                            <option value="Иванов">Иванов</option>
                            <option value="Петров">Петров</option>
                            <option value="Сидоров">Сидоров</option>
                        </select>
                    </div>
                    
                    <!-- Скрытые поля -->
                    <input type="hidden" name="Hidden1" value="Иванов">
                    <input type="hidden" name="Hidden2" value="Петров">
                    <input type="hidden" name="Hidden3" value="Сидоров">
                    
                    <div class="hidden-info">
                        <strong>Скрытые поля в этой форме:</strong><br>
                        Hidden1 = "Иванов"<br>
                        Hidden2 = "Петров"<br>
                        Hidden3 = "Сидоров"
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Отправить (GET)</button>
                    </div>
                </form>
                
                <div class="code-block">
&lt;?php
// Генерация формы со скрытыми полями в PHP
$Message1 = "Иванов";
$Message2 = "Петров";
$Message3 = "Сидоров";

echo "&lt;form method='GET' action='hidden.php'&gt;";
echo "Кто из следующих персонажей получит приз?";
echo "&lt;select name='ListBox'&gt;";
echo "&lt;option&gt;$Message1&lt;/option&gt;";
echo "&lt;option&gt;$Message2&lt;/option&gt;";
echo "&lt;option&gt;$Message3&lt;/option&gt;";
echo "&lt;/select&gt;";
echo "&lt;input type='hidden' name='Hidden1' value='$Message1'&gt;";
echo "&lt;input type='hidden' name='Hidden2' value='$Message2'&gt;";
echo "&lt;input type='hidden' name='Hidden3' value='$Message3'&gt;";
echo "&lt;input type='submit' value='Отправить'&gt;";
echo "&lt;/form&gt;";
?&gt;
                </div>
            </div>
            
            <div class="card">
                <h2>2️⃣ Поля ввода паролей (Password Fields)</h2>
                <p>Поля ввода паролей маскируют вводимые символы звездочками или точками для безопасности.</p>
                
                <form method="POST" action="lab12_password.php" class="form-group">
                    <div class="form-group">
                        <label class="form-label">Логин:</label>
                        <input type="text" name="username" class="form-control" placeholder="Введите логин" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Пароль:</label>
                        <input type="password" name="password" class="form-control" placeholder="Введите пароль" required>
                        <small>Пароль будет скрыт звездочками при вводе</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Подтверждение пароля:</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Повторите пароль" required>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Войти (POST)</button>
                    </div>
                </form>
                
                <div class="code-block">
&lt;!-- Поле ввода пароля --&gt;
&lt;input type="password" name="user_password"&gt;

&lt;?php
// Обработка пароля (НИКОГДА не выводите пароль на экран!)
if (isset($_POST['password'])) {
    $password = $_POST['password'];
    
    // Проверка пароля (в реальном приложении используйте хеширование)
    if (strlen($password) < 6) {
        echo "Пароль слишком короткий!";
    } else {
        // Пароль валиден
        // В реальном приложении: password_hash($password, PASSWORD_DEFAULT)
    }
}
?&gt;
                </div>
                
                <div class="error-message">
                    ⚠️ <strong>Важно:</strong> Никогда не используйте метод GET для передачи паролей! Пароль будет виден в URL.
                    Всегда используйте метод POST для конфиденциальных данных.
                </div>
            </div>
            
            <div class="card">
                <h2>3️⃣ Кнопки форм (Submit и Reset)</h2>
                <p>Кнопки позволяют управлять поведением формы: отправка данных и сброс к исходным значениям.</p>
                
                <form method="POST" action="lab12_buttons.php" class="form-group">
                    <div class="form-group">
                        <label class="form-label">Имя:</label>
                        <input type="text" name="first_name" class="form-control" value="Пример" placeholder="Введите имя">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Фамилия:</label>
                        <input type="text" name="last_name" class="form-control" value="Пользователя" placeholder="Введите фамилию">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Комментарий:</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Введите комментарий">Тестовый комментарий</textarea>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" name="submit_btn" value="send" class="btn btn-primary">
                            📤 Отправить данные
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            🗑️ Очистить форму
                        </button>
                        <button type="button" onclick="alert('Это обычная кнопка')" class="btn btn-info">
                            ℹ️ Просто кнопка
                        </button>
                    </div>
                </form>
                
                <div class="code-block">
&lt;!-- Кнопка отправки формы --&gt;
&lt;input type="submit" value="Отправить"&gt;
&lt;button type="submit"&gt;Отправить&lt;/button&gt;

&lt;!-- Кнопка сброса формы --&gt;
&lt;input type="reset" value="Очистить"&gt;
&lt;button type="reset"&gt;Очистить&lt;/button&gt;

&lt;!-- Обычная кнопка (не отправляет форму) --&gt;
&lt;button type="button" onclick="myFunction()"&gt;Нажми меня&lt;/button&gt;

&lt;?php
// Проверка, какая кнопка была нажата
if (isset($_POST['submit_btn'])) {
    echo "Была нажата кнопка отправки!";
}
?&gt;
                </div>
            </div>
            
            <div class="card">
                <h2>4️⃣ Кредитная заявка с проверкой полей</h2>
                <p>Пример комплексной формы с валидацией данных на стороне сервера.</p>
                
                <form method="POST" action="lab12_loan.php" class="form-group">
                    <input type="hidden" name="posted" value="true">
                    
                    <div class="form-group">
                        <label class="form-label">Имя: <span style="color: red">*</span></label>
                        <input type="text" name="FirstName" class="form-control" placeholder="Введите имя" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Фамилия: <span style="color: red">*</span></label>
                        <input type="text" name="LastName" class="form-control" placeholder="Введите фамилию" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Возраст: <span style="color: red">*</span></label>
                        <input type="number" name="Age" class="form-control" placeholder="Введите возраст" min="18" max="100" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Адрес: <span style="color: red">*</span></label>
                        <textarea name="Address" class="form-control" rows="3" placeholder="Введите адрес" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Размер заработной платы: <span style="color: red">*</span></label>
                        <select name="Salary" class="form-control" required>
                            <option value="">Выберите вариант</option>
                            <option value="0">До $10,000</option>
                            <option value="10000">$10,000 - $25,000</option>
                            <option value="25000">$25,000 - $50,000</option>
                            <option value="50000">Свыше $50,000</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Сумма кредита: <span style="color: red">*</span></label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" name="Loan" value="1000" id="loan1000" required>
                                <label for="loan1000">$1,000 под 8.0% годовых</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" name="Loan" value="5000" id="loan5000">
                                <label for="loan5000">$5,000 под 11.5% годовых</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" name="Loan" value="10000" id="loan10000">
                                <label for="loan10000">$10,000 под 15.0% годовых</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Подать заявку</button>
                        <button type="reset" class="btn btn-secondary">Очистить форму</button>
                    </div>
                    
                    <small style="color: #666; display: block; margin-top: 15px;">
                        <span style="color: red">*</span> Обязательные поля
                    </small>
                </form>
                
                <div class="code-block">
&lt;?php
// Проверка обязательных полей
if (isset($_POST['posted'])) {
    $first_name = $_POST['FirstName'];
    $last_name = $_POST['LastName'];
    $age = $_POST['Age'];
    $address = $_POST['Address'];
    $loan = $_POST['Loan'];
    
    // Проверка имени
    if (empty($first_name) || empty($last_name)) {
        echo "Необходимо ввести имя и фамилию!";
        exit;
    }
    
    // Проверка возраста
    if ($age &lt; 18 || $age &gt; 100) {
        echo "Введен некорректный возраст!";
        exit;
    }
    
    // Проверка адреса
    if (empty($address)) {
        echo "Необходимо ввести адрес!";
        exit;
    }
    
    // Проверка суммы кредита
    $valid_loans = ['1000', '5000', '10000'];
    if (!in_array($loan, $valid_loans)) {
        echo "Необходимо выбрать сумму кредита!";
        exit;
    }
    
    // Если все проверки пройдены
    echo "Заявка принята!";
}
?&gt;
                </div>
            </div>
            
            <div class="card">
                <h2>📝 Задание лабораторной работы</h2>
                <h3>Цель работы:</h3>
                <p>Научиться использовать дополнительные элементы HTML-форм совместно с языком PHP.</p>
                
                <h3>Содержание отчета:</h3>
                <ol class="tasks-list">
                    <li>Название лабораторной работы</li>
                    <li>Описание скрытых полей при совместном использовании PHP и HTML-форм</li>
                    <li>Описание полей для ввода паролей при совместном использовании PHP и HTML-форм</li>
                    <li>Примеры кода для каждого типа элементов</li>
                    <li>Результаты выполнения примеров</li>
                </ol>
                
                <h3>Вопросы для защиты работы:</h3>
                <ol class="tasks-list">
                    <li>Как совместно использовать PHP и скрытые поля HTML-форм?</li>
                    <li>Как совместно использовать PHP и поля ввода паролей HTML-форм?</li>
                    <li>Какие методы передачи данных существуют и чем они отличаются?</li>
                    <li>Как организовать проверку обязательных полей формы на сервере?</li>
                </ol>
                
                <div class="example-grid">
                    <div class="example-card">
                        <h4>Пример скрытых полей</h4>
                        <p>Передача данных между страницами без отображения пользователю</p>
                        <a href="lab12_hidden.php" class="example-btn">Перейти к примеру →</a>
                    </div>
                    
                    <div class="example-card">
                        <h4>Пример с паролями</h4>
                        <p>Безопасная обработка конфиденциальных данных</p>
                        <a href="lab12_password.php" class="example-btn">Перейти к примеру →</a>
                    </div>
                    
                    <div class="example-card">
                        <h4>Кредитная заявка</h4>
                        <p>Комплексная форма с валидацией данных</p>
                        <a href="lab12_loan.php" class="example-btn">Перейти к примеру →</a>
                    </div>
                </div>
            </div>
        </main>
        
        <div class="card">
            <h2>📈 Демонстрация сессии</h2>
            <p>Данные, хранящиеся в текущей сессии:</p>
            <pre id="sessionData" style="background: #f8f9fa; padding: 15px; border-radius: 8px; overflow: auto; font-family: monospace; font-size: 14px;"></pre>
            <button class="session-btn session-btn-refresh" onclick="updateSessionData()">
                Обновить данные сессии
            </button>
        </div>
        
        <footer>
            <p>© 2025 Лабораторная работа №12 - PHP и HTML-формы (часть 2) | Портовое управление</p>
            <p>Текущий пользователь: <?php echo htmlspecialchars($_SESSION['user_name']); ?> 
               (Роль: <?php echo htmlspecialchars($_SESSION['user_role']); ?>) | 
               Сессия активна: <?php echo date('H:i:s'); ?></p>
        </footer>
    </div>
    
    <script>
        // Отображаем данные сессии
        function updateSessionData() {
            const sessionData = {
                'ID сессии': '<?php echo session_id(); ?>',
                'Пользователь': '<?php echo htmlspecialchars($_SESSION['user_name']); ?>',
                'Роль': '<?php echo htmlspecialchars($_SESSION['user_role']); ?>',
                'Логин': '<?php echo htmlspecialchars($_SESSION['username']); ?>',
                'Время входа': '<?php echo date("H:i:s", $login_time); ?>',
                'Длительность сессии': '<?php echo $session_minutes; ?> мин <?php echo $session_seconds; ?> сек'
            };
            
            const pre = document.getElementById('sessionData');
            pre.textContent = JSON.stringify(sessionData, null, 2);
        }
        
        // Обновляем сессию
        function refreshSession() {
            fetch('session_refresh.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ Сессия обновлена!');
                        location.reload();
                    } else {
                        alert('❌ Ошибка обновления сессии');
                    }
                });
        }
        
        // Завершаем сессию
        function destroySession() {
            if (confirm('Вы уверены, что хотите завершить сессию?')) {
                fetch('session_destroy.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('✅ Сессия завершена!');
                            window.location.href = 'login.php';
                        } else {
                            alert('❌ Ошибка завершения сессии');
                        }
                    });
            }
        }
        
        // Таймер обратного отсчета сессии
        let minutes = <?php echo $minutes_remaining; ?>;
        let seconds = <?php echo $seconds_remaining; ?>;
        
        function updateSessionTimer() {
            if (seconds === 0) {
                if (minutes === 0) {
                    document.getElementById('sessionStatus').textContent = '❌ Истекла';
                    document.getElementById('sessionStatus').style.color = '#ff4757';
                    return;
                }
                minutes--;
                seconds = 59;
            } else {
                seconds--;
            }
            
            // Обновляем отображение
            const statusElement = document.getElementById('sessionStatus');
            statusElement.innerHTML = `✅ Активна (${minutes}:${seconds.toString().padStart(2, '0')})`;
            
            if (minutes < 5) {
                statusElement.style.color = '#ffa502';
            }
            
            if (minutes < 1) {
                statusElement.style.color = '#ff4757';
            }
        }
        
        // Запускаем таймер
        setInterval(updateSessionTimer, 1000);
        
        // Загружаем данные сессии при старте
        updateSessionData();
    </script>
</body>
</html>