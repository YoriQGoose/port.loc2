<?php
session_start();
require_once 'check_access.php';
require_once 'permissions.php';

// Проверяем права доступа
if (!canView()) {
    $_SESSION['access_error'] = "У вас нет прав для доступа к лабораторной работе";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа №13 - Стандартные операторы PHP</title>
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
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
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
            text-align: center;
        }
        
        h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .nav-btn {
            padding: 12px 25px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }
        
        .nav-btn.secondary {
            background: #2196F3;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .card h3 {
            color: #4A00E0;
            margin-bottom: 15px;
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .operator-list {
            list-style: none;
            padding: 0;
        }
        
        .operator-list li {
            padding: 10px 15px;
            margin-bottom: 8px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #4A00E0;
        }
        
        .example-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border: 1px solid #e0e0e0;
        }
        
        .example-box h4 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Consolas', monospace;
            overflow-x: auto;
            margin: 15px 0;
        }
        
        .result-box {
            background: #e9f7ef;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border: 1px solid #c3e6cb;
        }
        
        .result-box.error {
            background: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Лабораторная работа №13</h1>
            <p class="subtitle">Использование стандартных операторов языка PHP при обработке данных пользователя из форм</p>
        </header>
        
        <div class="nav-buttons">
            <a href="#if-operators" class="nav-btn">Булевые операторы и IF</a>
            <a href="#comparison" class="nav-btn secondary">Операторы сравнения</a>
            <a href="#logical" class="nav-btn">Логические операторы</a>
        </div>
        
        <div class="content-grid">
            <!-- Карточка 1: Булевые операторы -->
            <div id="if-operators" class="card">
                <h3>⚙️ Булевые операторы и IF</h3>
                <p>Оператор if используется для проверки правильности введенных данных пользователем.</p>
                
                <ul class="operator-list">
                    <li>Проверка на деление на ноль</li>
                    <li>Валидация введенных данных</li>
                    <li>Условное выполнение кода</li>
                </ul>
                
                <div class="example-box">
                    <h4>Пример: Деление чисел</h4>
                    <p>Проверка, чтобы не произошло деление на нуль.</p>
                    <a href="lab13_example1.php" class="nav-btn" style="padding: 8px 15px; font-size: 14px;">Перейти к примеру</a>
                </div>
            </div>
            
            <!-- Карточка 2: Операторы сравнения -->
            <div id="comparison" class="card">
                <h3>📊 Операторы сравнения</h3>
                <p>Операторы ">", "<", "==", "!=" для сравнения значений.</p>
                
                <ul class="operator-list">
                    <li>> - Больше</li>
                    <li>< - Меньше</li>
                    <li>== - Равенство</li>
                    <li>!= - Неравенство</li>
                </ul>
                
                <div class="example-box">
                    <h4>Пример: Угадай число</h4>
                    <p>Программа "загадывает" число от 1 до 10.</p>
                    <a href="lab13_example2.php" class="nav-btn" style="padding: 8px 15px; font-size: 14px;">Перейти к примеру</a>
                </div>
            </div>
            
            <!-- Карточка 3: Логические операторы -->
            <div id="logical" class="card">
                <h3>🔗 Логические операторы</h3>
                <p>AND, OR, XOR, NOT для комбинирования условий.</p>
                
                <ul class="operator-list">
                    <li>and (&&) - Логическое И</li>
                    <li>or (||) - Логическое ИЛИ</li>
                    <li>xor - Исключающее ИЛИ</li>
                    <li>! - Логическое НЕ</li>
                </ul>
                
                <div class="example-box">
                    <h4>Пример: Аренда автомобиля</h4>
                    <p>Проверка возраста и наличия прав.</p>
                    <a href="lab13_example3.php" class="nav-btn" style="padding: 8px 15px; font-size: 14px;">Перейти к примеру</a>
                </div>
            </div>
        </div>
        
        <!-- Теоретическая информация -->
        <div class="card" style="margin-top: 30px;">
            <h3>📚 Теоретическое обоснование</h3>
            <p><strong>Цель работы:</strong> научиться использовать стандартные операторы языка PHP при обработке данных пользователя из форм.</p>
            
            <div class="example-box">
                <h4>Ключевые моменты:</h4>
                <ul class="operator-list">
                    <li>Проверка на правильность введенной информации</li>
                    <li>Предотвращение ошибок (деление на ноль и др.)</li>
                    <li>Сравнение значений для принятия решений</li>
                    <li>Комбинирование условий с помощью логических операторов</li>
                </ul>
            </div>
            
            <div class="example-box">
                <h4>Пример кода с оператором IF:</h4>
                <div class="code-block">
&lt;?php<br>
if (isset($_POST['posted'])) {<br>
&nbsp;&nbsp;$first_number = $_POST['first_number'];<br>
&nbsp;&nbsp;$second_number = $_POST['second_number'];<br>
&nbsp;&nbsp;if ($second_number == 0) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo "На ноль делить нельзя!!!";<br>
&nbsp;&nbsp;} else {<br>
&nbsp;&nbsp;&nbsp;&nbsp;$answer = $first_number / $second_number;<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo "Ответ: " . $answer;<br>
&nbsp;&nbsp;}<br>
}<br>
?&gt;
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" class="back-btn">← Назад на главную</a>
        </div>
    </div>
</body>
</html>