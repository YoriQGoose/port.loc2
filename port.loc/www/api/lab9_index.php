<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа №9 - Передача переменных через URL</title>
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
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(135deg, #4A00E0 0%, #8E2DE2 100%);
            color: white;
            padding: 30px;
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
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }
        
        .nav-btn {
            padding: 10px 25px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .nav-btn:hover {
            background: white;
            color: #4A00E0;
            transform: translateY(-2px);
        }
        
        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 30px;
        }
        
        .example-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            border: 1px solid #e9ecef;
        }
        
        .example-section h2 {
            color: #4A00E0;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4A00E0;
        }
        
        .code-block {
            background: #2d2d2d;
            color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            margin: 15px 0;
            overflow-x: auto;
        }
        
        .url-example {
            background: #e9f7ef;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            color: #2d2d2d;
        }
        
        .form-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            border: 2px dashed #4A00E0;
            margin-top: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            border-color: #4A00E0;
            outline: none;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #4A00E0 0%, #8E2DE2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 0, 224, 0.3);
        }
        
        .result-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-top: 30px;
            min-height: 200px;
        }
        
        .result-section h3 {
            color: #4A00E0;
            margin-bottom: 15px;
        }
        
        .url-display {
            background: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            word-break: break-all;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            color: #666;
            border-top: 1px solid #dee2e6;
            background: #f8f9fa;
        }
        
        .theory {
            grid-column: 1 / -1;
            background: #e9f7ef;
            padding: 25px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .theory h2 {
            color: #2d2d2d;
            margin-bottom: 15px;
        }
        
        .theory p {
            line-height: 1.6;
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }
            
            .nav-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .nav-btn {
                width: 100%;
                max-width: 300px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🔄 Лабораторная работа №9</h1>
            <div class="subtitle">Передача переменных через URL (GET-параметры)</div>
            <div class="nav-buttons">
                <a href="lab9_example1.php" class="nav-btn">Пример 1: Одна переменная</a>
                <a href="lab9_example2.php" class="nav-btn">Пример 2: Несколько переменных</a>
                <a href="lab9_form.php" class="nav-btn">Интерактивная форма</a>
            </div>
        </header>
        
        <div class="content">
            <div class="example-section">
                <h2>📘 Теория</h2>
                <p><strong>URL (Uniform Resource Locator)</strong> - это адрес ресурса в интернете.</p>
                <p>Пример URL с GET-параметрами:</p>
                <div class="url-example">
                    http://localhost/page.php?<strong>name=John</strong>&<strong>age=25</strong>
                </div>
                <p><strong>Структура URL с параметрами:</strong></p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li><code>http://localhost/</code> - протокол и домен</li>
                    <li><code>page.php</code> - имя файла</li>
                    <li><code>?</code> - разделитель между адресом и параметрами</li>
                    <li><code>name=John</code> - первый параметр (переменная=значение)</li>
                    <li><code>&</code> - разделитель между параметрами</li>
                    <li><code>age=25</code> - второй параметр</li>
                </ul>
            </div>
            
            <div class="example-section">
                <h2>🔧 Как получить параметры в PHP</h2>
                <p>В PHP GET-параметры доступны через суперглобальный массив <code>$_GET</code>:</p>
                <div class="code-block">
// Получить значение параметра 'name'<br>
$name = $_GET['name'];<br>
<br>
// Получить значение параметра 'age'<br>
$age = $_GET['age'];<br>
<br>
// Проверить существование параметра<br>
if (isset($_GET['name'])) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo "Привет, " . $_GET['name'];<br>
}
                </div>
                <p><strong>Важно:</strong> Всегда проверяйте существование параметров с помощью <code>isset()</code>.</p>
            </div>
            
            <div class="theory">
                <h2>📝 Примеры использования</h2>
                <p><strong>1. Поиск по сайту:</strong></p>
                <div class="url-example">
                    /search.php?<strong>query=php</strong>&<strong>category=programming</strong>
                </div>
                
                <p><strong>2. Пагинация:</strong></p>
                <div class="url-example">
                    /articles.php?<strong>page=2</strong>&<strong>limit=10</strong>
                </div>
                
                <p><strong>3. Фильтрация товаров:</strong></p>
                <div class="url-example">
                    /shop.php?<strong>category=electronics</strong>&<strong>price_min=100</strong>&<strong>price_max=500</strong>
                </div>
                
                <p><strong>Преимущества GET-параметров:</strong></p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>✅ Можно сохранить URL с результатами</li>
                    <li>✅ Можно отправить ссылку другому пользователю</li>
                    <li>✅ Простота использования и отладки</li>
                    <li>✅ Кэширование браузером</li>
                </ul>
                
                <p><strong>Недостатки:</strong></p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>❌ Ограничение длины URL (обычно 2048 символов)</li>
                    <li>❌ Параметры видны в адресной строке</li>
                    <li>❌ Нельзя передавать файлы</li>
                    <li>❌ Не подходит для конфиденциальных данных</li>
                </ul>
            </div>
            
            <div class="example-section">
                <h2>🎯 Практическое задание</h2>
                <p>Создайте форму, которая будет передавать данные через URL и выводить результат.</p>
                <div class="form-container">
                    <form action="lab9_process.php" method="GET">
                        <div class="form-group">
                            <label for="username">Имя пользователя:</label>
                            <input type="text" id="username" name="username" placeholder="Введите имя" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="city">Город:</label>
                            <input type="text" id="city" name="city" placeholder="Введите город" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="hobby">Хобби:</label>
                            <input type="text" id="hobby" name="hobby" placeholder="Введите хобби" required>
                        </div>
                        
                        <button type="submit" class="btn">Отправить через GET</button>
                    </form>
                </div>
                <p style="margin-top: 15px; font-size: 14px; color: #666;">
                    <strong>Примечание:</strong> После отправки формы обратите внимание на адресную строку браузера.
                    Вы увидите передаваемые параметры в формате: <code>?username=...&city=...&hobby=...</code>
                </p>
            </div>
            
            <div class="example-section">
                <h2>🔍 Демонстрация работы</h2>
                <p>Попробуйте перейти по этим ссылкам:</p>
                <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 15px;">
                    <a href="lab9_example1.php?name=Александр" class="btn" style="text-align: center;">Пример с именем</a>
                    <a href="lab9_example2.php?name=Мария&age=28&city=Москва" class="btn" style="text-align: center;">Пример с несколькими параметрами</a>
                    <a href="lab9_form.php?search=php&category=web&page=1" class="btn" style="text-align: center;">Пример поиска</a>
                </div>
                
                <div style="margin-top: 20px;">
                    <h3>Тестовые URL:</h3>
                    <div class="url-display">
                        lab9_example1.php?name=Иван<br>
                        lab9_example2.php?product=телефон&price=25000&brand=samsung<br>
                        lab9_form.php?filter=active&sort=date&order=desc
                    </div>
                </div>
            </div>
        </div>
        
        <footer>
            <p>© 2025 Лабораторная работа №9 - Передача переменных через URL</p>
        </footer>
    </div>
    
    <script>
        // Добавляем подсветку параметров в URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlExamples = document.querySelectorAll('.url-example');
            urlExamples.forEach(example => {
                const html = example.innerHTML;
                example.innerHTML = html.replace(/\?([^<]+)/g, '?<strong>$1</strong>');
            });
        });
    </script>
</body>
</html>