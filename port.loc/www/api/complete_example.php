<!DOCTYPE html>
<html>
<head>
    <title>Полный пример работы с файлами</title>
</head>
<body>
    <h2>Лабораторная работа №10. Использование внешнего файла для хранения и загрузки данных</h2>
    
    <h3>5. Использование функции fread() для чтения всего файла</h3>
    <?php
    // Использование fread для чтения всего содержимого файла
    $filename = "unitednations.txt";
    $fileSize = filesize($filename);
    
    if ($fileSize > 0) {
        $f = fopen($filename, "r");
        $content = fread($f, $fileSize);
        fclose($f);
        
        echo "<h4>Весь файл, прочитанный с помощью fread():</h4>";
        echo "<pre style='background:#f9f9f9; padding:10px; border:1px solid #ddd;'>";
        echo htmlspecialchars($content);
        echo "</pre>";
        
        echo "<p><strong>Размер файла:</strong> " . $fileSize . " байт</p>";
        
        // Разбиваем на строки для обработки
        $lines = explode("\n", $content);
        echo "<h4>Обработанные данные:</h4>";
        echo "<ul>";
        foreach ($lines as $line) {
            if (trim($line) !== '') {
                $parts = explode(",", $line);
                if (count($parts) >= 2) {
                    echo "<li>" . htmlspecialchars(trim($parts[0])) . 
                         " (<a href='http://" . htmlspecialchars(trim($parts[1])) . 
                         "' target='_blank'>" . htmlspecialchars(trim($parts[1])) . "</a>)</li>";
                }
            }
        }
        echo "</ul>";
    } else {
        echo "❌ Файл пуст или не существует";
    }
    ?>
    
    <hr>
    
    <h3>Сравнение методов чтения файлов:</h3>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
        <tr>
            <th>Метод</th>
            <th>Лучше всего подходит для</th>
            <th>Преимущества</th>
            <th>Недостатки</th>
        </tr>
        <tr>
            <td><strong>fgets()</strong></td>
            <td>Чтение больших файлов по строкам</td>
            <td>Экономия памяти, можно обрабатывать файлы любого размера</td>
            <td>Нужно организовывать цикл</td>
        </tr>
        <tr>
            <td><strong>fread()</strong></td>
            <td>Чтение маленьких файлов целиком</td>
            <td>Простота использования, весь файл в одной переменной</td>
            <td>Требует много памяти для больших файлов</td>
        </tr>
    </table>
    
    <hr>
    
    <h3>Навигация по примерам:</h3>
    <ol>
        <li><a href="simple_open.php">Простое открытие и закрытие файла</a></li>
        <li><a href="read_first_line.php">Чтение первой строки (fgets)</a></li>
        <li><a href="read_all_lines.php">Чтение всех строк (while + feof)</a></li>
        <li><a href="read_and_parse.php">Разбор строк и создание ссылок (explode)</a></li>
        <li><a href="complete_example.php">Полный пример с fread</a></li>
    </ol>
</body>
</html>