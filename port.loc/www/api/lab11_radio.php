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
    <title>Обработка переключателей - Лабораторная работа №11</title>
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
        <h1>Результат обработки переключателей (Radio buttons)</h1>
        
        <div class="result">
            <h3>Вы выбрали столицу России:</h3>
            <div style="font-size: 24px; padding: 20px; background: white; border-radius: 10px; text-align: center;">
                <?php
                if (isset($_GET['Question'])) {
                    $answer = htmlspecialchars($_GET['Question']);
                    echo "<strong>" . $answer . "</strong>";
                    
                    // Проверка правильности ответа
                    if ($answer === "Москва") {
                        echo "<br><span style='color: #28a745;'>✅ Правильный ответ!</span>";
                    } else {
                        echo "<br><span style='color: #dc3545;'>❌ Неправильно. Правильный ответ - Москва</span>";
                    }
                } else {
                    echo "❌ Вы не выбрали вариант ответа";
                }
                ?>
            </div>
            
            <h4>Переданные данные GET:</h4>
            <pre><?php print_r($_GET); ?></pre>
            
            <h4>URL с параметрами:</h4>
            <p><?php echo $_SERVER['REQUEST_URI']; ?></p>
            
            <p><em>Особенность переключателей: можно выбрать только один вариант из группы</em></p>
        </div>
        
        <a href="lab11_index.php" class="back-btn">Вернуться к лабораторной работе</a>
        <a href="index.php" class="back-btn" style="background: #6c757d;">На главную</a>
    </div>
</body>
</html>