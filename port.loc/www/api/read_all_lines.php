<!DOCTYPE html>
<html>
<head>
    <title>Чтение из текстовых файлов - Все строки</title>
</head>
<body>
    <h3>3. Чтение всех строк из файла</h3>
    <?php
    $f = fopen("unitednations.txt", "r");
    
    if ($f) {
        echo "<h4>Содержимое файла unitednations.txt:</h4>";
        echo "<div style='border:1px solid #ccc; padding:10px; background:#f9f9f9;'>";
        
        // Читать построчно до конца файла
        $lineNumber = 1;
        while(!feof($f)) {
            $line = fgets($f);
            if ($line !== false && trim($line) !== '') {
                echo "Строка {$lineNumber}: " . htmlspecialchars($line) . "<br>";
                $lineNumber++;
            }
        }
        
        echo "</div>";
        fclose($f);
    } else {
        echo "❌ Ошибка открытия файла";
    }
    ?>
    
    <br><br>
    <a href="read_and_parse.php">➡️ Перейти к разбору строк и созданию ссылок</a>
</body>
</html>