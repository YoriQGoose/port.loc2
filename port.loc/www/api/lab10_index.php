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
    <title>Лабораторная работа №10 - Портовое управление</title>
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
            background: linear-gradient(135deg, #0062cc 0%, #0096ff 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 98, 204, 0.2);
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
            color: #0062cc;
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
            color: #0062cc;
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
            color: #0062cc;
            margin-bottom: 20px;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card h3 {
            color: #444;
            margin: 20px 0 10px 0;
            font-size: 20px;
        }
        
        .card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }
        
        .btn {
            display: block;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0062cc 0%, #0096ff 100%);
            color: white;
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #0062cc;
            border: 2px solid #0062cc;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 98, 204, 0.3);
        }
        
        .session-controls {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .session-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .session-btn-refresh {
            background: #0062cc;
            color: white;
        }
        
        .session-btn-destroy {
            background: #ff4757;
            color: white;
        }
        
        .session-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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
            border-left: 4px solid #0062cc;
            transition: transform 0.3s ease;
        }
        
        .example-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .example-card h4 {
            color: #0062cc;
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
            padding: 8px 15px;
            background: #0062cc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background 0.3s ease;
        }
        
        .example-btn:hover {
            background: #0056b3;
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
        
        .file-info {
            background: #e8f4fc;
            border: 1px solid #b3d9ff;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        
        .file-content {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            white-space: pre-wrap;
            margin: 15px 0;
            max-height: 200px;
            overflow-y: auto;
            font-size: 13px;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-top">
                <h1>📚 Лабораторная работа №10</h1>
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
                <p>Текстовые файлы отлично подходят для хранения разного рода данных. Они не так гибки, как базы данных, но обычно не требуют такого количества ресурсов. Более того, текстовые файлы имеют формат, который читается на большинстве систем.</p>
                
                <h3>Основные функции PHP для работы с файлами:</h3>
                <div class="code-block">
// Открытие файла
$f = fopen("unitednations.txt", "r");

// Чтение строки
$line = fgets($f);

// Проверка конца файла
while (!feof($f)) {
    // Чтение и обработка строк
}

// Закрытие файла
fclose($f);

// Разделение строки
$arrM = explode(",", $line);
                </div>
                
                <div class="file-info">
                    <h4>📄 Используемый файл данных:</h4>
                    <p><strong>Имя файла:</strong> unitednations.txt</p>
                    <p><strong>Содержимое:</strong></p>
                    <div class="file-content">
<?php
if (file_exists("unitednations.txt")) {
    echo htmlspecialchars(file_get_contents("unitednations.txt"));
} else {
    echo "Файл не найден. Создайте файл unitednations.txt с данными.";
}
?>
                    </div>
                    <p><strong>Формат:</strong> Название организации, домен (разделитель - запятая)</p>
                </div>
            </div>
            
            <div class="card">
                <h2>💻 Практические примеры</h2>
                <p>Выберите пример для изучения работы с текстовыми файлами:</p>
                
                <div class="example-grid">
                    <div class="example-card">
                        <h4>Пример 1: Открытие файла</h4>
                        <p>Базовый пример открытия текстового файла с использованием функции fopen().</p>
                        <a href="simple_open.php" class="example-btn">Перейти к примеру →</a>
                    </div>
                    
                    <div class="example-card">
                        <h4>Пример 2: Чтение первой строки</h4>
                        <p>Использование функции fgets() для чтения первой строки из файла.</p>
                        <a href="read_first_line.php" class="example-btn">Перейти к примеру →</a>
                    </div>
                    
                    <div class="example-card">
                        <h4>Пример 3: Чтение всех строк</h4>
                        <p>Чтение всего файла построчно с помощью цикла while и функции feof().</p>
                        <a href="read_all_lines.php" class="example-btn">Перейти к примеру →</a>
                    </div>
                    
                    <div class="example-card">
                        <h4>Пример 4: Разбор строк и создание ссылок</h4>
                        <p>Использование функции explode() для разбора строк с разделителями.</p>
                        <a href="read_and_parse.php" class="example-btn">Перейти к примеру →</a>
                    </div>
                    
                    <div class="example-card">
                        <h4>Пример 5: Полный пример с fread</h4>
                        <p>Использование fread() для чтения всего файла целиком.</p>
                        <a href="complete_example.php" class="example-btn">Перейти к примеру →</a>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <h2>📝 Задание лабораторной работы</h2>
                <h3>Цель работы:</h3>
                <p>Научиться организовывать использование внешнего файла для хранения и загрузки внешних данных.</p>
                
                <h3>Задачи:</h3>
                <ul class="tasks-list">
                    <li>Изучить теоретическое обоснование работы с текстовыми файлами</li>
                    <li>Освоить функции fopen(), fclose(), fgets(), feof(), explode()</li>
                    <li>Реализовать чтение данных из текстового файла</li>
                    <li>Научиться разбирать строки с разделителями</li>
                    <li>Создать динамический контент на основе данных из файла</li>
                </ul>
                
                <h3>Вопросы для защиты:</h3>
                <ul class="tasks-list">
                    <li>Как использовать текстовые файлы для хранения данных?</li>
                    <li>Каким образом осуществляется работа с массивами при разборе строк?</li>
                    <li>В чем преимущества и недостатки хранения данных в текстовых файлах по сравнению с базами данных?</li>
                </ul>
                
                <div class="btn-group">
                    <a href="simple_open.php" class="btn btn-primary">Начать выполнение →</a>
                    <a href="index.php" class="btn btn-secondary">Вернуться на главную</a>
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
            <p>© 2025 Лабораторная работа №10 - Работа с текстовыми файлами | Портовое управление</p>
            <p>Текущий пользователь: <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Гость'); ?> 
               (Роль: <?php echo htmlspecialchars($_SESSION['user_role'] ?? 'не определена'); ?>) | 
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