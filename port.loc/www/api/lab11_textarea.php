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
    <title>Обработка текстовой области - Лабораторная работа №11</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .result { background: #e8f5e9; border: 2px solid #28a745; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .back-btn { display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Результат обработки текстовой области (POST метод)</h1>
        
        <div class="result">
            <h3>Ваши любимые Web-сайты:</h3>
            <div style="white-space: pre-wrap; background: white; padding: 10px; border-radius: 5px;">
                <?php echo isset($_POST['Websites']) ? htmlspecialchars($_POST['Websites']) : 'Не указано'; ?>
            </div>
            
            <h4>Переданные данные POST:</h4>
            <pre><?php print_r($_POST); ?></pre>
            
            <h4>Обработанный текст (с переносами строк):</h4>
            <div style="background: white; padding: 10px; border-radius: 5px;">
                <?php echo isset($_POST['Websites']) ? nl2br(htmlspecialchars($_POST['Websites'])) : 'Не указано'; ?>
            </div>
            
            <p><em>Обратите внимание: данные передаются скрыто (не видны в URL)</em></p>
        </div>
        
        <a href="lab11_index.php" class="back-btn">Вернуться к лабораторной работе</a>
        <a href="index.php" class="back-btn" style="background: #6c757d;">На главную</a>
    </div>
</body>
</html>