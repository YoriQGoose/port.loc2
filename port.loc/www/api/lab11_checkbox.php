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
    <title>Обработка флажков - Лабораторная работа №11</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .result { background: #e8f5e9; border: 2px solid #28a745; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .back-btn { display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .checkbox-result { background: white; padding: 10px; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Результат обработки флажков (Checkbox)</h1>
        
        <div class="result">
            <h3>1. Статус студента ОГУ:</h3>
            <div class="checkbox-result">
                <?php 
                if (isset($_POST['Choice'])) {
                    echo "✅ Вы студент ОГУ: " . htmlspecialchars($_POST['Choice']);
                } else {
                    echo "❌ Вы не отметили, что являетесь студентом ОГУ";
                }
                ?>
            </div>
            
            <h3>2. Выбранные языки программирования:</h3>
            <div class="checkbox-result">
                <?php
                $languages = [];
                for ($i = 1; $i <= 5; $i++) {
                    $key = 'Choice' . $i;
                    if (isset($_POST[$key])) {
                        $languages[] = htmlspecialchars($_POST[$key]);
                    }
                }
                
                if (count($languages) > 0) {
                    echo "✅ Вы выбрали " . count($languages) . " языков: ";
                    echo implode(', ', $languages);
                } else {
                    echo "❌ Вы не выбрали ни одного языка программирования";
                }
                ?>
            </div>
            
            <h4>Все переданные данные POST:</h4>
            <pre><?php print_r($_POST); ?></pre>
            
            <p><em>Особенность флажков: если флажок не отмечен, переменная не передается на сервер</em></p>
        </div>
        
        <a href="lab11_index.php" class="back-btn">Вернуться к лабораторной работе</a>
        <a href="index.php" class="back-btn" style="background: #6c757d;">На главную</a>
    </div>
</body>
</html>