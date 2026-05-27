<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пример 2: Несколько переменных</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            max-width: 900px;
            width: 100%;
        }
        h1 { color: #333; margin-bottom: 30px; text-align: center; }
        .result-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 5px solid #f5576c;
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px 5px;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245,87,108,0.3);
        }
        .form-group {
            margin: 15px 0;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }
        .form-row input {
            width: 100%;
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 16px;
        }
        .params-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .params-table th, .params-table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }
        .params-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .info-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #f5576c;
            text-align: center;
        }
        .info-card h3 {
            color: #f5576c;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔗 Пример 2: Передача нескольких переменных через URL</h1>
        
        <div class="code-box">
            <strong>Код PHP:</strong><br>
            // Получаем значения параметров из URL<br>
            $name = $_GET['name'] ?? '';<br>
            $age = $_GET['age'] ?? '';<br>
            $city = $_GET['city'] ?? '';<br>
            <br>
            // Проверяем и выводим результаты<br>
            if ($name && $age) {<br>
            &nbsp;&nbsp;&nbsp;&nbsp;echo "Привет, $name! Тебе $age лет.";<br>
            }
        </div>
        
        <?php
        // Получаем параметры из URL
        $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
        $age = isset($_GET['age']) ? htmlspecialchars($_GET['age']) : '';
        $city = isset($_GET['city']) ? htmlspecialchars($_GET['city']) : '';
        $hobby = isset($_GET['hobby']) ? htmlspecialchars($_GET['hobby']) : '';
        
        $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        
        // Проверяем, есть ли параметры
        $has_params = $name || $age || $city || $hobby;
        ?>
        
        <?php if ($has_params): ?>
            <div class="result-box">
                <h2>📊 Результат:</h2>
                
                <div class="info-grid">
                    <?php if ($name): ?>
                        <div class="info-card">
                            <h3>👤 Имя</h3>
                            <p style="font-size: 20px;"><?php echo $name; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($age): ?>
                        <div class="info-card">
                            <h3>🎂 Возраст</h3>
                            <p style="font-size: 20px;"><?php echo $age; ?> лет</p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($city): ?>
                        <div class="info-card">
                            <h3>🏙️ Город</h3>
                            <p style="font-size: 20px;"><?php echo $city; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($hobby): ?>
                        <div class="info-card">
                            <h3>🎯 Хобби</h3>
                            <p style="font-size: 20px;"><?php echo $hobby; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($name && $age): ?>
                    <div style="background: #e9f7ef; padding: 20px; border-radius: 10px; margin: 20px 0;">
                        <h3>📝 Собранная информация:</h3>
                        <p style="font-size: 22px; margin: 15px 0;">
                            🎉 <strong>Привет, <?php echo $name; ?>!</strong><br>
                            <?php if ($age): ?>Тебе <?php echo $age; ?> лет.<br><?php endif; ?>
                            <?php if ($city): ?>Ты из города <?php echo $city; ?>.<?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>
                
                <p><strong>Текущий URL:</strong></p>
                <div class="url-box">
                    <?php echo $current_url; ?>
                </div>
                
                <p><strong>Все переданные параметры:</strong></p>
                <table class="params-table">
                    <thead>
                        <tr>
                            <th>Параметр</th>
                            <th>Значение</th>
                            <th>PHP-код</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($name): ?>
                        <tr>
                            <td>name</td>
                            <td><?php echo $name; ?></td>
                            <td><code>$_GET['name']</code></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($age): ?>
                        <tr>
                            <td>age</td>
                            <td><?php echo $age; ?></td>
                            <td><code>$_GET['age']</code></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($city): ?>
                        <tr>
                            <td>city</td>
                            <td><?php echo $city; ?></td>
                            <td><code>$_GET['city']</code></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($hobby): ?>
                        <tr>
                            <td>hobby</td>
                            <td><?php echo $hobby; ?></td>
                            <td><code>$_GET['hobby']</code></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="result-box">
                <h2>⚠️ Параметры не переданы</h2>
                <p>Пожалуйста, передайте параметры через URL.</p>
                <p>Например: <code>lab9_example2.php?name=Иван&age=25&city=Москва</code></p>
            </div>
        <?php endif; ?>
        
        <div class="form-group">
            <h3>📝 Тест передачи параметров:</h3>
            <form method="GET" action="">
                <div class="form-row">
                    <input type="text" name="name" placeholder="Имя" value="<?php echo $name; ?>">
                    <input type="number" name="age" placeholder="Возраст" value="<?php echo $age; ?>">
                    <input type="text" name="city" placeholder="Город" value="<?php echo $city; ?>">
                    <input type="text" name="hobby" placeholder="Хобби" value="<?php echo $hobby; ?>">
                </div>
                <button type="submit" class="btn" style="width: 100%;">Отправить параметры</button>
            </form>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <h3>📎 Примеры ссылок:</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
                <a href="lab9_example2.php?name=Иван&age=25&city=Москва" class="btn">Иван, 25 лет, Москва</a>
                <a href="lab9_example2.php?name=Анна&age=30&hobby=рисование" class="btn">Анна, 30 лет, рисование</a>
                <a href="lab9_example2.php?product=ноутбук&price=75000&brand=asus" class="btn">Товар: ноутбук</a>
                <a href="lab9_example2.php?search=php&category=programming&page=2" class="btn">Поиск: PHP</a>
            </div>
        </div>
        
        <div class="back-link">
            <a href="lab9_index.php" class="btn">← Назад к лабораторной работе</a>
        </div>
    </div>
</body>
</html>