<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Скрытые поля - Лабораторная работа №12</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .result-box { background: #fff3e0; border: 2px solid #fd7e14; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .hidden-data { background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #fd7e14; margin: 15px 0; font-family: monospace; }
        .back-btn { display: inline-block; padding: 10px 20px; background: #fd7e14; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; margin-right: 10px; }
        h1 { color: #fd7e14; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🔒 Результат обработки скрытых полей</h1>
            
            <div class="result-box">
                <h3>📋 Было три варианта (переданы через скрытые поля):</h3>
                <div class="hidden-data">
                    <?php
                    echo "1. " . (isset($_GET['Hidden1']) ? htmlspecialchars($_GET['Hidden1']) : 'Не передано') . "<br>";
                    echo "2. " . (isset($_GET['Hidden2']) ? htmlspecialchars($_GET['Hidden2']) : 'Не передано') . "<br>";
                    echo "3. " . (isset($_GET['Hidden3']) ? htmlspecialchars($_GET['Hidden3']) : 'Не передано') . "<br>";
                    ?>
                </div>
                
                <h3>🎯 Вы выбрали:</h3>
                <div style="font-size: 24px; padding: 20px; background: white; border-radius: 10px; text-align: center;">
                    <strong><?php echo isset($_GET['ListBox']) ? htmlspecialchars($_GET['ListBox']) : 'Не выбрано'; ?></strong>
                </div>
                
                <h4>Все переданные данные GET:</h4>
                <pre style="background: white; padding: 15px; border-radius: 5px; overflow: auto;"><?php print_r($_GET); ?></pre>
                
                <h4>URL с параметрами:</h4>
                <p style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; word-break: break-all;">
                    <?php echo $_SERVER['REQUEST_URI']; ?>
                </p>
                
                <div style="background: #e8f4fc; padding: 15px; border-radius: 8px; margin-top: 20px;">
                    <h4>📝 Пояснение:</h4>
                    <p>Скрытые поля (<code>&lt;input type="hidden"&gt;</code>) передают данные на сервер, но не отображаются пользователю в форме. В этом примере мы передали три имени через скрытые поля, а пользователь выбрал одно из них через выпадающий список.</p>
                </div>
            </div>
            
            <a href="lab12_index.php" class="back-btn">Вернуться к лабораторной работе</a>
            <a href="index.php" class="back-btn" style="background: #6c757d;">На главную</a>
        </div>
    </div>
</body>
</html>