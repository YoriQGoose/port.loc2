<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пример 1: Одна переменная</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
        }
        h1 { color: #333; margin-bottom: 30px; text-align: center; }
        .result-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 5px solid #4A00E0;
        }
        .url-box {
            background: #2d2d2d;
            color: #00ff00;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            word-break: break-all;
        }
        .code-box {
            background: #f1f3f4;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            border: 1px dashed #ccc;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #4A00E0 0%, #8E2DE2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px 5px;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74,0,224,0.3);
        }
        .form-group {
            margin: 20px 0;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 16px;
        }
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔤 Пример 1: Передача одной переменной через URL</h1>
        
        <div class="code-box">
            <strong>Код PHP:</strong><br>
            // Получаем значение параметра 'name' из URL<br>
            $name = $_GET['name'];<br>
            <br>
            // Выводим результат<br>
            echo "Привет, " . htmlspecialchars($name) . "!";
        </div>
        
        <?php
        // Проверяем, передан ли параметр 'name'
        if (isset($_GET['name'])) {
            $name = htmlspecialchars($_GET['name']);
            $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        ?>
            <div class="result-box">
                <h2>📊 Результат:</h2>
                <p style="font-size: 24px; color: #4A00E0; margin: 15px 0;">
                    🎉 <strong>Привет, <?php echo $name; ?>!</strong>
                </p>
                
                <p><strong>Текущий URL:</strong></p>
                <div class="url-box">
                    <?php echo $current_url; ?>
                </div>
                
                <p><strong>Переданный параметр:</strong></p>
                <div class="code-box">
                    $_GET['name'] = "<?php echo $name; ?>"
                </div>
            </div>
        <?php } else { ?>
            <div class="result-box">
                <h2>⚠️ Параметр не передан</h2>
                <p>Пожалуйста, передайте параметр 'name' через URL.</p>
                <p>Например: <code>lab9_example1.php?name=Иван</code></p>
            </div>
        <?php } ?>
        
        <div class="form-group">
            <h3>📝 Тест передачи параметра:</h3>
            <form method="GET" action="">
                <input type="text" name="name" placeholder="Введите ваше имя" 
                       value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>">
                <button type="submit" class="btn" style="margin-top: 10px; width: 100%;">Отправить</button>
            </form>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <h3>📎 Примеры ссылок:</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
                <a href="lab9_example1.php?name=Алексей" class="btn">Алексей</a>
                <a href="lab9_example1.php?name=Мария" class="btn">Мария</a>
                <a href="lab9_example1.php?name=Дмитрий" class="btn">Дмитрий</a>
                <a href="lab9_example1.php?name=Анна" class="btn">Анна</a>
            </div>
        </div>
        
        <div class="back-link">
            <a href="lab9_index.php" class="btn">← Назад к лабораторной работе</a>
        </div>
    </div>
</body>
</html>