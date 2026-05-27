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

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная - Портовое управление</title>
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
            background: linear-gradient(135deg, #4A00E0 0%, #8E2DE2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(74, 0, 224, 0.2);
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
        
        .logout-btn {
            display: inline-block;
            background: white;
            color: #4A00E0;
            padding: 10px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
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
            color: #4A00E0;
            margin-bottom: 20px;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
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
            background: linear-gradient(135deg, #4A00E0 0%, #8E2DE2 100%);
            color: white;
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #4A00E0;
            border: 2px solid #4A00E0;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 0, 224, 0.3);
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
            background: #4A00E0;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-top">
                <h1>🏗️ Портовое управление</h1>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                    <div class="user-role"><?php echo htmlspecialchars($_SESSION['user_role']); ?></div>
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
                <h2>👥 Управление сотрудниками</h2>
                <p>Добавление, просмотр и управление данными сотрудников порта.</p>
                <div class="btn-group">
                    <a href="form_all.php" class="btn btn-primary">Добавить сотрудника</a>
                    <a href="search_form.php?table=employees" class="btn btn-secondary">Найти сотрудника</a>
                </div>
            </div>
            
            <div class="card">
                <h2>📦 Управление грузами</h2>
                <p>Отслеживание и управление портовыми грузами, их статусами и местоположением.</p>
                <div class="btn-group">
                    <a href="form_all.php" class="btn btn-primary">Добавить груз</a>
                    <a href="search_form.php?table=cargoes" class="btn btn-secondary">Найти груз</a>
                </div>
            </div>
            
            <div class="card">
                <h2>🚢 Управление судами</h2>
                <p>Информация о судах, их статусе и операциях в порту.</p>
                <div class="btn-group">
                    <a href="form_all.php" class="btn btn-primary">Добавить судно</a>
                    <a href="search_form.php?table=vessels" class="btn btn-secondary">Найти судно</a>
                </div>
            </div>
            
            <div class="card">
                <h2>🔧 Управление операциями</h2>
                <p>Журнал операций погрузки, разгрузки и перемещения грузов.</p>
                <div class="btn-group">
                    <a href="form_all.php" class="btn btn-primary">Добавить операцию</a>
                    <a href="search_form.php?table=operations" class="btn btn-secondary">Найти операцию</a>
                </div>
            </div>
            
            <div class="card">
                <h2>👤 Управление клиентами</h2>
                <p>База данных клиентов и компаний, работающих с портом.</p>
                <div class="btn-group">
                    <a href="form_all.php" class="btn btn-primary">Добавить клиента</a>
                    <a href="search_form.php?table=customers" class="btn btn-secondary">Найти клиента</a>
                </div>
            </div>
			
            <div class="card">
				<h2>🔗 Лабораторная работа №9</h2>
				<p>Передача переменных через URL (GET-параметры).</p>
				<div class="btn-group">
					<a href="lab9_index.php" class="btn btn-primary">Перейти к лабораторной работе</a>
				</div>
			</div>
            <div class="card">
                <h2>📚 Лабораторная работа 10</h2>
                <p>Использование внешнего файла для хранения и загрузки внешних данных.</p>
                <div class="btn-group">
                    <a href="lab10_index.php" class="btn btn-primary">Перейти к лабораторной работе</a>
                </div>
            </div>
			<div class="card">
                <h2>📋 Лабораторная работа №11</h2>
                <p>PHP и поля HTML-форм: текстовые поля, текстовая область, флажки, переключатели, списки.</p>
                <div class="btn-group">
                    <a href="lab11_index.php" class="btn btn-success">Перейти к лабораторной работе</a>
                </div>
			</div>
			<div class="card">
				<h2>🔒 Лабораторная работа №12</h2>
				<p>PHP и поля HTML-форм: скрытые поля, пароли, кнопки, проверка обязательных полей.</p>
				<div class="btn-group">
					<a href="lab12_index.php" class="btn btn-warning">Перейти к лабораторной работе</a>
				</div>
			</div>
			<div class="card">
				<h2>⚙️ Лабораторная работа №13</h2>
				<p>Использование стандартных операторов PHP при обработке данных пользователя из форм.</p>
				<div class="btn-group">
					<a href="lab13_index.php" class="btn btn-primary">Перейти к лабораторной работе</a>
				</div>
			</div>
			<div class="card">
				<h2>🔀 Лабораторная работа №14</h2>
				<p>Оператор SWITCH и циклы в PHP: WHILE, DO WHILE, FOR.</p>
				<div class="btn-group">
					<a href="lab14.php" class="btn btn-primary">Перейти к лабораторной работе</a>
				</div>
			</div>
            <div class="card">
                <h2>⚙️ Администрирование</h2>
                <p>Настройки системы, управление пользователями и резервное копирование.</p>
                <div class="btn-group">
                    <a href="update_form.php" class="btn btn-primary">Обновление данных</a>
                    <a href="delete_form.php" class="btn btn-primary">Удаление данных</a>
                    <a href="view_all.php" class="btn btn-secondary">Просмотр всех данных</a>
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
        <!-- Карточка информации о ролях -->
		<div class="card">
			<h2>👥 Информация о ролях</h2>
			<p>Просмотр доступных ролей и их разрешений в системе.</p>
			<div class="btn-group">
			<a href="role_info.php" class="btn btn-primary">Просмотреть роли</a>
		</div>
		</div>
        <footer>
            <p>© 2025 Система управления портовыми грузами | Сессия активна: <?php echo date('H:i:s'); ?></p>
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