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
    <title>Лабораторная работа №11 - Портовое управление</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
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
            color: #28a745;
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
            color: #28a745;
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
            color: #28a745;
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
            border-color: #28a745;
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
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #28a745;
            border: 2px solid #28a745;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
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
            border-left: 4px solid #28a745;
            transition: transform 0.3s ease;
        }
        
        .example-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .example-card h4 {
            color: #28a745;
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
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background 0.3s ease;
        }
        
        .example-btn:hover {
            background: #218838;
        }
        
        .result-box {
            background: #e8f5e9;
            border: 2px solid #28a745;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .result-box h4 {
            color: #28a745;
            margin-bottom: 15px;
        }
        
        .result-item {
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
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
                <h1>📋 Лабораторная работа №11</h1>
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
                <p>Использование элемента дизайна форма при разработке Web-сайта является наиболее популярным способом организации интерактивного взаимодействия с его посетителями. С помощью языка HTML можно создавать как простые, так и сложные формы, предполагающие множественный выбор из нескольких вариантов.</p>
                
                <h3>Элементы HTML-форм:</h3>
                <ul class="tasks-list">
                    <li>Текстовые поля (input type="text")</li>
                    <li>Текстовые области (textarea)</li>
                    <li>Флажки (checkbox)</li>
                    <li>Переключатели (radio)</li>
                    <li>Списки (select)</li>
                    <li>Скрытые поля (hidden)</li>
                    <li>Поля ввода паролей (password)</li>
                    <li>Кнопки (button, submit, reset)</li>
                </ul>
                
                <h3>Методы передачи данных:</h3>
                <div class="code-block">
// GET метод - данные передаются в URL
&lt;form method="GET" action="обработчик.php"&gt;

// POST метод - данные передаются в теле запроса
&lt;form method="POST" action="обработчик.php"&gt;

// В PHP получаем данные:
$var = $_GET['имя_поля'];    // для GET
$var = $_POST['имя_поля'];   // для POST
$var = $_REQUEST['имя_поля']; // для GET и POST
                </div>
            </div>
            
            <div class="card">
                <h2>1️⃣ Текстовые поля (GET метод)</h2>
                <p>Текстовые поля являются одними из наиболее известных элементов управления формами. В них пользователь может ввести любую информацию.</p>
                
                <form method="GET" action="lab11_text.php" class="form-group">
                    <label class="form-label">Кто ваш любимый автор?</label>
                    <input type="text" name="Author" class="form-control" placeholder="Введите имя автора">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Отправить (GET)</button>
                    </div>
                </form>
                
                <div class="code-block">
&lt;!-- HTML форма --&gt;
&lt;form method="GET" action="lab11_text.php"&gt;
    Кто ваш любимый автор?
    &lt;input name="Author" type="text"&gt;
    &lt;input type="submit" value="Отправить"&gt;
&lt;/form&gt;

&lt;?php
// PHP обработчик (lab11_text.php)
echo "Ваш любимый автор: " . $_GET['Author'];
?&gt;
                </div>
            </div>
            
            <div class="card">
                <h2>2️⃣ Текстовая область (POST метод)</h2>
                <p>Текстовые области предназначены для того, чтобы принимать от пользователя предложения и даже целые строки.</p>
                
                <form method="POST" action="lab11_textarea.php" class="form-group">
                    <label class="form-label">Перечислите ваши любимые Web-сайты:</label>
                    <textarea name="Websites" class="form-control" rows="5" cols="50" placeholder="http://example.com
http://another-example.com">http://
http://
http://
http://</textarea>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Отправить (POST)</button>
                    </div>
                </form>
                
                <div class="code-block">
&lt;!-- HTML форма --&gt;
&lt;form method="POST" action="lab11_textarea.php"&gt;
    Перечислите ваши любимые Web-сайты
    &lt;textarea name="Websites" cols="50" rows="5"&gt;
        http://
        http://
    &lt;/textarea&gt;
    &lt;input type="submit" value="Отправить"&gt;
&lt;/form&gt;

&lt;?php
// PHP обработчик (lab11_textarea.php)
echo "Ваши любимые Web-сайты: " . nl2br($_POST['Websites']);
?&gt;
                </div>
            </div>
            
            <div class="card">
                <h2>3️⃣ Флажки (Checkbox)</h2>
                <p>Флажки применимы в ситуациях, когда пользователю необходимо ответить на вопрос, требующий строгого однозначного ответа "да" или "нет".</p>
                
                <form method="POST" action="lab11_checkbox.php" class="form-group">
                    <label class="form-label">Вы студент Оренбургского государственного университета?</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="Choice" value="Да">
                            <label>Да, я студент ОГУ</label>
                        </div>
                    </div>
                    
                    <h4 style="margin-top: 20px;">Выберите языки программирования, которыми вы владеете:</h4>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="Choice1" value="Delphi">
                            <label>Delphi</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="Choice2" value="C++/C#">
                            <label>C++/C#</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="Choice3" value="Assembler">
                            <label>Assembler</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="Choice4" value="PHP">
                            <label>PHP</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="Choice5" value="JavaScript">
                            <label>JavaScript</label>
                        </div>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </div>
                </form>
                
                <div class="code-block">
&lt;!-- Одиночный флажок --&gt;
&lt;input name="Choice" type="checkbox" value="Да"&gt;

&lt;!-- Несколько флажков --&gt;
&lt;input name="Choice1" type="checkbox" value="Delphi"&gt;
&lt;input name="Choice2" type="checkbox" value="C++/C#"&gt;

&lt;?php
// Проверка выбора
if (isset($_POST['Choice'])) {
    echo "Флажок выбран: " . $_POST['Choice'];
} else {
    echo "Флажок не выбран";
}

// Обработка нескольких флажков
echo $_POST['Choice1'] ?? 'Не выбрано';
echo $_POST['Choice2'] ?? 'Не выбрано';
?&gt;
                </div>
            </div>
            
            <div class="card">
                <h2>4️⃣ Переключатели (Radio buttons)</h2>
                <p>Переключатели используются в тех случаях, когда имеется набор возможных ответов, но выбрать можно только один из них.</p>
                
                <form method="GET" action="lab11_radio.php" class="form-group">
                    <label class="form-label">Выберите название столицы России:</label>
                    <div class="radio-group">
                        <div class="radio-item">
                            <input type="radio" name="Question" value="Москва" checked>
                            <label>Москва</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="Question" value="Екатеринбург">
                            <label>Екатеринбург</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="Question" value="Оренбург">
                            <label>Оренбург</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="Question" value="Санкт-Петербург">
                            <label>Санкт-Петербург</label>
                        </div>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Отправить (GET)</button>
                    </div>
                </form>
                
                <div class="code-block">
&lt;!-- Группа переключателей с одинаковым именем --&gt;
&lt;input name="Question" type="radio" value="Москва"&gt; Москва
&lt;input name="Question" type="radio" value="Екатеринбург"&gt; Екатеринбург

&lt;?php
// Получение выбранного значения
if (isset($_GET['Question'])) {
    echo "Вы выбрали: " . $_GET['Question'];
} else {
    echo "Ничего не выбрано";
}
?&gt;
                </div>
            </div>
            
            <div class="card">
                <h2>5️⃣ Списки (Select)</h2>
                <p>Списки представляют собой элементы управления формами, которые обычно отображают несколько объектов.</p>
                
                <form method="POST" action="lab11_listbox.php" class="form-group">
                    <div class="form-group">
                        <label class="form-label">Выберите бытовую технику (один вариант):</label>
                        <select name="Tech" class="form-control">
                            <option value="Стиральная машина">Стиральная машина</option>
                            <option value="Утюг">Утюг</option>
                            <option value="Микроволновая печь">Микроволновая печь</option>
                            <option value="Пылесос">Пылесос</option>
                            <option value="Холодильник">Холодильник</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Выберите фирму-производителя (несколько вариантов):</label>
                        <select name="Production[]" multiple size="4" class="form-control">
                            <option value="LG">LG</option>
                            <option value="Samsung">Samsung</option>
                            <option value="Panasonic">Panasonic</option>
                            <option value="Sony">Sony</option>
                            <option value="Bosch">Bosch</option>
                            <option value="Philips">Philips</option>
                        </select>
                        <small>Для выбора нескольких вариантов удерживайте Ctrl (Cmd на Mac)</small>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </div>
                </form>
                
                <div class="code-block">
&lt;!-- Одиночный выбор --&gt;
&lt;select name="Tech"&gt;
    &lt;option value="Стиральная машина"&gt;Стиральная машина&lt;/option&gt;
&lt;/select&gt;

&lt;!-- Множественный выбор --&gt;
&lt;select name="Production[]" multiple size=4&gt;
    &lt;option value="LG"&gt;LG&lt;/option&gt;
&lt;/select&gt;

&lt;?php
// Одиночный выбор
echo "Техника: " . $_POST['Tech'];

// Множественный выбор (массив)
if (isset($_POST['Production'])) {
    echo "Производители: ";
    foreach ($_POST['Production'] as $producer) {
        echo $producer . " ";
    }
}
?&gt;
                </div>
            </div>
            
            <div class="card">
                <h2>📝 Задание лабораторной работы</h2>
                <h3>Цель работы:</h3>
                <p>Научиться использовать элементы HTML-форм совместно с языком PHP.</p>
                
                <h3>Содержание отчета:</h3>
                <ol class="tasks-list">
                    <li>Название лабораторной работы</li>
                    <li>Описание совместного использования PHP и HTML-форм</li>
                    <li>Примеры кода для каждого типа элементов форм</li>
                    <li>Результаты выполнения примеров</li>
                </ol>
                
                <h3>Вопросы для защиты работы:</h3>
                <ol class="tasks-list">
                    <li>Как совместно использовать PHP и текстовые поля HTML-форм?</li>
                    <li>Как совместно использовать PHP и флажки HTML-форм?</li>
                    <li>Как совместно использовать PHP и переключатели HTML-форм?</li>
                    <li>Как совместно использовать PHP и списки HTML-форм?</li>
                </ol>
                
                <div class="example-grid">
                    <div class="example-card">
                        <h4>Полная форма примера</h4>
                        <p>Пример комплексной формы со всеми типами элементов</p>
                        <a href="lab11_complete_form.php" class="example-btn">Перейти к примеру →</a>
                    </div>
                    
                    <div class="example-card">
                        <h4>Обработчик форм</h4>
                        <p>Универсальный обработчик для всех типов форм</p>
                        <a href="lab11_processor.php" class="example-btn">Смотреть код →</a>
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
            <p>© 2025 Лабораторная работа №11 - PHP и HTML-формы | Портовое управление</p>
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