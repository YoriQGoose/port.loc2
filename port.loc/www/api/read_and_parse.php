<!DOCTYPE html>
<html>
<head>
    <title>Чтение из текстовых файлов - Создание ссылок</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .link-list { list-style-type: none; padding: 0; }
        .link-item { 
            background: #f5f5f5; 
            margin: 5px 0; 
            padding: 10px; 
            border-radius: 5px;
            border-left: 4px solid #4CAF50;
        }
        .link-item a { 
            color: #2196F3; 
            text-decoration: none; 
            font-weight: bold; 
        }
        .link-item a:hover { color: #0d8bf2; }
        .link-desc { color: #666; font-size: 14px; margin-left: 10px; }
        .data-table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .data-table th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h3>4. Разбор строк и создание ссылок из файла данных</h3>
    
    <h4>Список организаций (ссылки):</h4>
    <ul class="link-list">
    <?php
    $f = fopen("unitednations.txt", "r");
    
    if ($f) {
        // Читать построчно до конца файла
        while (!feof($f)) {
            $line = fgets($f);
            
            if ($line !== false && trim($line) !== '') {
                // Создать массив с запятой-разделителем
                $arrM = explode(",", $line);
                
                // Проверяем, что массив содержит минимум 3 элемента
                if (count($arrM) >= 3) {
                    $name = trim($arrM[0]);
                    $domain = trim($arrM[1]);
                    $description = trim($arrM[2]);
                    
                    // Записать ссылки (получить данные из массива)
                    echo "<li class='link-item'>";
                    echo "<a href='http://" . htmlspecialchars($domain) . "' target='_blank'>";
                    echo htmlspecialchars($name) . "</a>";
                    echo "<span class='link-desc'> - " . htmlspecialchars($description) . "</span>";
                    echo "</li>";
                }
            }
        }
        fclose($f);
    } else {
        echo "❌ Ошибка открытия файла";
    }
    ?>
    </ul>
    
    <h4>Табличное представление данных:</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th>№</th>
                <th>Название организации</th>
                <th>Веб-сайт</th>
                <th>Описание</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $f = fopen("unitednations.txt", "r");
        
        if ($f) {
            $counter = 1;
            while (!feof($f)) {
                $line = fgets($f);
                
                if ($line !== false && trim($line) !== '') {
                    $arrM = explode(",", $line);
                    
                    if (count($arrM) >= 3) {
                        echo "<tr>";
                        echo "<td>" . $counter . "</td>";
                        echo "<td>" . htmlspecialchars(trim($arrM[0])) . "</td>";
                        echo "<td><a href='http://" . htmlspecialchars(trim($arrM[1])) . "' target='_blank'>" . 
                             htmlspecialchars(trim($arrM[1])) . "</a></td>";
                        echo "<td>" . htmlspecialchars(trim($arrM[2])) . "</td>";
                        echo "</tr>";
                        $counter++;
                    }
                }
            }
            fclose($f);
        }
        ?>
        </tbody>
    </table>
    
    <br>
    <a href="complete_example.php">➡️ Посмотреть полный пример</a>
</body>
</html>