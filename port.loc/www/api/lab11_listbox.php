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
    <title>Обработка списков - Лабораторная работа №11</title>
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
        <h1>Результат обработки списков (Select)</h1>
        
        <div class="result">
            <h3>1. Выбранная бытовая техника:</h3>
            <div style="background: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo isset($_POST['Tech']) ? "<strong>" . htmlspecialchars($_POST['Tech']) . "</strong>" : "Не выбрано"; ?>
            </div>
            
            <h3>2. Выбранные фирмы-производители:</h3>
            <div style="background: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php
                if (isset($_POST['Production']) && is_array($_POST['Production'])) {
                    echo "Вы выбрали " . count($_POST['Production']) . " фирм:<br>";
                    echo "<ul>";
                    foreach ($_POST['Production'] as $producer) {
                        echo "<li>" . htmlspecialchars($producer) . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "❌ Вы не выбрали ни одной фирмы";
                }
                ?>
            </div>
            
            <h4>Все переданные данные POST:</h4>
            <pre><?php print_r($_POST); ?></pre>
            
            <p><em>Особенность списков: для множественного выбора нужно использовать массив (name="Production[]")</em></p>
        </div>
        
        <a href="lab11_index.php" class="back-btn">Вернуться к лабораторной работе</a>
        <a href="index.php" class="back-btn" style="background: #6c757d;">На главную</a>
    </div>
</body>
</html>