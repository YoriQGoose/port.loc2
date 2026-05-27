<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обработка GET-параметров</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
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
        .success-box {
            background: #d4edda;
            color: #155724;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #c3e6cb;
            margin: 20px 0;
        }
        .param-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 15px 0;
            border-left: 5px solid #a8edea;
        }
        .url-display {
            background: #2d2d2d;
            color: #00ff00;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            word-break: break-all;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(168,237,234,0.3);
        }
        .code-box {
            background: #f1f3f4;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            border: 1px dashed #ccc;
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
        .info-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid #a8edea;
        }
        .info-item h3 {
            color: #333;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✅ Обработка GET-параметров</h1>
        
        <?php
        // Получаем параметры из URL
        $username = isset($_GET['username']) ? htmlspecialchars($_GET['username']) : null;
        $city = isset($_GET['city']) ? htmlspecialchars($_GET['city']) : null;
        $hobby = isset($_GET['hobby']) ? htmlspecialchars($_GET['hobby']) : null;
        
        $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        
        // Проверяем, есть ли параметры
        if ($username && $city && $hobby) {
        ?>
            <div class="success-box">
                <h2>🎉 Данные успешно получены!</h2>
                <p>Следующие параметры были переданы через URL:</p>
            </div>
            
            <div class="info-grid">
                <div class="info-item">
                    <h3>👤 Имя</h3>
                    <p style="font-size: 24px; color: #333;"><?php echo $username; ?></p>
                </div>
                
                <div class="info-item">
                    <h3>🏙️ Город</h3>
                    <p style="font-size: 24px; color: #333;"><?php echo $city; ?></p>
                </div>
                
                <div class="info-item">
                    <h3>🎯 Хобби</h3>
                    <p style="font-size: 24px; color: #333;"><?php echo $hobby; ?></p>
                </div>
            </div>
            
            <div class="param-card">
                <h3>📝 Сводная информация:</h3>
                <p style="font-size: 20px; margin: 15px 0;">
                    Привет, <strong><?php echo $username; ?></strong>!<br>
                    Рад, что ты из города <strong><?php echo $city; ?></strong>.<br>
                    Твоё хобби <strong><?php echo $hobby; ?></strong> звучит интересно!
                </p>
            </div>
            
            <div class="param-card">
                <h3>🔗 Текущий URL:</h3>
                <div class="url-display">
                    <?php echo $current_url; ?>
                </div>
                <p style="margin-top: 10px; color: #666;">
                    Обратите внимание на адресную строку браузера. Все параметры видны после знака <code>?</code>.
                </p>
            </div>
            
            <div class="param-card">
                <h3>💻 Код PHP для получения параметров:</h3>
                <div class="code-box">
                    // Получение параметров из URL<br>
                    $username = $_GET['username'];<br>
                    $city = $_GET['city'];<br>
                    $hobby = $_GET['hobby'];<br>
                    <br>
                    // Проверка и вывод<br>
                    if ($username && $city && $hobby) {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;echo "Привет, $username из города $city!";<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;echo "Твое хобби: $hobby";<br>
                    }
                </div>
            </div>
            
            <div class="param-card">
                <h3>🔍 Анализ URL:</h3>
                <p><strong>Протокол:</strong> http://</p>
                <p><strong>Домен:</strong> <?php echo $_SERVER['HTTP_HOST']; ?></p>
                <p><strong>Файл:</strong> lab9_process.php</p>
                <p><strong>Параметры:</strong> ?username=...&city=...&hobby=...</p>
                <p><strong>Разделитель параметров:</strong> & (амперсанд)</p>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <h3>📋 Переданные параметры:</h3>
                <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 12px; text-align: left;">Ключ</th>
                        <th style="padding: 12px; text-align: left;">Значение</th>
                        <th style="padding: 12px; text-align: left;">PHP-код</th>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #dee2e6;">username</td>
                        <td style="padding: 12px; border-bottom: 1px solid #dee2e6;"><?php echo $username; ?></td>
                        <td style="padding: 12px; border-bottom: 1px solid #dee2e6;"><code>$_GET['username']</code></td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #dee2e6;">city</td>
                        <td style="padding: 12px; border-bottom: 1px solid #dee2e6;"><?php echo $city; ?></td>
                        <td style="padding: 12px; border-bottom: 1px solid #dee2e6;"><code>$_GET['city']</code></td>
                    </tr>
                    <tr>
                        <td style="padding: 12px;">hobby</td>
                        <td style="padding: 12px;"><?php echo $hobby; ?></td>
                        <td style="padding: 12px;"><code>$_GET['hobby']</code></td>
                    </tr>
                </table>
            </div>
            
        <?php } else { ?>
            <div class="param-card" style="background: #fff3cd; border-color: #ffeaa7;">
                <h2>⚠️ Не все параметры переданы</h2>
                <p>Для корректной работы необходимо передать все три параметра:</p>
                <ul style="margin: 15px 0; margin-left: 20px;">
                    <li><strong>username</strong> - имя пользователя</li>
                    <li><strong>city</strong> - город</li>
                    <li><strong>hobby</strong> - хобби</li>
                </ul>
                <p>Пример корректного URL:</p>
                <div class="url-display">
                    lab9_process.php?username=Иван&city=Москва&hobby=программирование
                </div>
            </div>
        <?php } ?>
        
        <div class="back-link">
            <a href="lab9_index.php" class="btn">← Назад к лабораторной работе</a>
            <?php if ($username && $city && $hobby): ?>
                <a href="lab9_process.php?username=<?php echo urlencode($username); ?>&city=<?php echo urlencode($city); ?>&hobby=<?php echo urlencode($hobby); ?>" class="btn">🔄 Обновить страницу</a>
                <a href="lab9_form.php" class="btn">📝 Новая форма</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>