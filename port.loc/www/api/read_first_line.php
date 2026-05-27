<!DOCTYPE html>
<html>
<head>
    <title>Чтение из текстовых файлов - Первая строка</title>
</head>
<body>
    <h3>2. Чтение первой строки из файла</h3>
    <?php
    $f = fopen("unitednations.txt", "r");
    
    if ($f) {
        // Читать строку из текстового файла
        $firstLine = fgets($f);
        echo "Первая строка файла: <strong>" . htmlspecialchars($firstLine) . "</strong>";
        
        fclose($f);
    } else {
        echo "❌ Ошибка открытия файла";
    }
    ?>
    
    <br><br>
    <a href="read_all_lines.php">➡️ Перейти к чтению всех строк</a>
</body>
</html>