<?php
session_start();
require_once 'check_access.php';
require_once 'permissions.php';

if (!canView()) {
    $_SESSION['access_error'] = "У вас нет прав для доступа";
    header("Location: index.php");
    exit();
}

// Обработка всех примеров в одном файле
$example = isset($_GET['example']) ? $_GET['example'] : 'switch';
$result_message = '';
$result_class = '';

// Данные для примера 1 (SWITCH)
$destination = '';
$grade = '';
$total_price = 0;

// Данные для примера 2 (WHILE)
$loan_amount = '';
$monthly_payment = '';
$duration = 0;
$interest_rate = 0;

// Данные для примера 3 (DO WHILE)
$number = '';
$is_prime = false;
$iterations = 0;

// Данные для примера 4 (FOR)
$children_count = 0;
$children_names = [];
$stage = 'input';

// Обработка формы примера 1 (SWITCH)
if (isset($_POST['example1_submit'])) {
    $example = 'switch';
    $destination = $_POST['destination'] ?? '';
    $grade = $_POST['grade'] ?? '';
    
    if (empty($destination) || empty($grade)) {
        $result_message = 'Пожалуйста, выберите город и класс отеля';
        $result_class = 'error';
    } else {
        $base_price = 500;
        $city_modifier = 1;
        $star_modifier = 1;
        $destgrade = $destination . $grade;
        
        switch($destgrade) {
            case "Alaniathree":
                $city_modifier = 1.5; $star_modifier = 3; break;
            case "Alaniafour":
                $city_modifier = 1.5; $star_modifier = 4; break;
            case "Alaniafive":
                $city_modifier = 1.5; $star_modifier = 5; break;
            case "Kimerthree":
                $city_modifier = 2; $star_modifier = 3; break;
            case "Kimerfour":
                $city_modifier = 2; $star_modifier = 4; break;
            case "Kimerfive":
                $city_modifier = 2; $star_modifier = 5; break;
            case "Antaliathree":
                $city_modifier = 3.5; $star_modifier = 3; break;
            case "Antaliafour":
                $city_modifier = 3.5; $star_modifier = 4; break;
            case "Antaliafive":
                $city_modifier = 3.5; $star_modifier = 5; break;
            default:
                $result_message = "Пожалуйста, выберите корректные параметры";
                $result_class = 'error';
                break;
        }
        
        if ($result_class != 'error') {
            $total_price = $base_price * $city_modifier * $star_modifier;
            $result_message = "Недельная стоимость проживания в отеле:";
            $result_class = 'success';
        }
    }
}

// Обработка формы примера 2 (WHILE)
if (isset($_POST['example2_submit'])) {
    $example = 'while';
    $loan_amount = (float)($_POST['loan'] ?? 0);
    $monthly_payment = (float)($_POST['month'] ?? 0);
    
    if ($loan_amount <= 0 || $monthly_payment <= 0) {
        $result_message = 'Пожалуйста, введите корректные значения';
        $result_class = 'error';
    } else {
        switch(true) {
            case ($loan_amount <= 1000): $interest_rate = 8.0; break;
            case ($loan_amount <= 5000): $interest_rate = 11.5; break;
            case ($loan_amount <= 10000): $interest_rate = 15.0; break;
            default: $interest_rate = 18.0; break;
        }
        
        $remaining_loan = $loan_amount;
        $duration = 0;
        $monthly_interest = $interest_rate / 12 / 100;
        
        while ($remaining_loan > 0) {
            $duration++;
            $month_interest = $remaining_loan * $monthly_interest;
            $principal_payment = $monthly_payment - $month_interest;
            
            if ($principal_payment <= 0) {
                $result_message = 'Ежемесячный платеж слишком мал для покрытия процентов!';
                $result_class = 'error';
                $duration = 0;
                break;
            }
            
            $remaining_loan -= $principal_payment;
            
            if ($duration > 600) {
                $result_message = 'Срок кредита превышает 50 лет. Увеличьте ежемесячный платеж.';
                $result_class = 'error';
                break;
            }
        }
        
        if ($duration > 0 && $duration <= 600) {
            $years = floor($duration / 12);
            $months = $duration % 12;
            $result_message = "Для погашения кредита {$loan_amount} ₽ под {$interest_rate}% годовых";
            $result_message .= " с ежемесячным платежом {$monthly_payment} ₽";
            $result_class = 'success';
        }
    }
}

// Обработка формы примера 3 (DO WHILE)
if (isset($_POST['example3_submit'])) {
    $example = 'dowhile';
    $number = trim($_POST['number'] ?? '');
    
    if (!is_numeric($number) || $number <= 0 || floor($number) != $number) {
        $result_message = 'Пожалуйста, введите целое положительное число';
        $result_class = 'error';
    } else {
        $number = (int)$number;
        $is_prime = true;
        $iterations = 0;
        
        if ($number == 1) {
            $is_prime = false;
        } elseif ($number == 2) {
            $is_prime = true;
        } else {
            $divisor = 2;
            $limit = floor(sqrt($number));
            
            do {
                $iterations++;
                if ($number % $divisor == 0) {
                    $is_prime = false;
                    break;
                }
                $divisor++;
            } while ($divisor <= $limit);
        }
        
        if ($is_prime) {
            $result_message = "Число <strong>{$number}</strong> является простым!";
            $result_class = 'success';
        } else {
            $result_message = "Число <strong>{$number}</strong> не является простым.";
            $result_class = 'error';
        }
    }
}

// Обработка формы примера 4 (FOR)
if (isset($_POST['example4_submit'])) {
    $example = 'for';
    $children_count = (int)($_POST['children_count'] ?? 0);
    
    if ($children_count <= 0 || $children_count > 20) {
        $result_message = 'Пожалуйста, введите число от 1 до 20';
        $result_class = 'error';
        $stage = 'input';
    } else {
        $stage = 'names';
    }
}


if (isset($_POST['example4_names_submit'])) {
    $example = 'for';
    $children_names = $_POST['child'] ?? [];
    $children_count = count($children_names);
    $stage = 'results';
    
    if ($children_count > 0) {
        $result_message = "Введено $children_count имен детей";
        $result_class = 'success';
    } else {
        $result_message = "Не введено ни одного имени";
        $result_class = 'error';
    }
}

// Сброс формы примера 4
if (isset($_POST['example4_reset'])) {
    $example = 'for';
    $children_count = 0;
    $children_names = [];
    $stage = 'input';
    $result_message = '';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа №14 - Все примеры</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(135deg, #9C27B0 0%, #673AB7 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        
        .tab {
            padding: 15px 25px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: #6c757d;
            border-right: 1px solid #dee2e6;
            transition: all 0.3s ease;
            flex: 1;
            text-align: center;
        }
        
        .tab:hover {
            background: #e9ecef;
        }
        
        .tab.active {
            background: white;
            color: #9C27B0;
            border-bottom: 3px solid #9C27B0;
        }
        
        .tab-content {
            display: none;
            padding: 30px;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .example-header {
            color: #9C27B0;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .example-description {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .form-container {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .result-box {
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            font-size: 16px;
        }
        
        input[type="radio"] {
            margin-right: 8px;
        }
        
        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }
        
        .radio-option {
            flex: 1;
            min-width: 150px;
            padding: 15px;
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .radio-option:hover {
            border-color: #9C27B0;
            background: #f3e5f5;
        }
        
        button {
            background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(156, 39, 176, 0.3);
        }
        
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        
        .code-example {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            font-family: 'Consolas', monospace;
            overflow-x: auto;
        }
        
        .output-box {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            font-family: monospace;
        }
        
        .children-inputs {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .child-input {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }
        
        .price-display {
            font-size: 32px;
            font-weight: bold;
            color: #9C27B0;
            text-align: center;
            margin: 20px 0;
        }
        
        .duration-display {
            font-size: 28px;
            font-weight: bold;
            color: #3F51B5;
            text-align: center;
            margin: 20px 0;
        }
        
        .number-display {
            font-size: 36px;
            font-weight: bold;
            color: #009688;
            text-align: center;
            margin: 20px 0;
        }
        
        .children-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .child-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #4CAF50;
            text-align: center;
        }
        
        .child-number {
            background: #4CAF50;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Лабораторная работа №14</h1>
            <p class="subtitle">Оператор SWITCH и циклы в PHP - Все примеры в одном месте</p>
        </header>
        
        <div class="tabs">
            <button class="tab <?php echo $example == 'switch' ? 'active' : ''; ?>" onclick="showTab('switch')">
                🔀 SWITCH
            </button>
            <button class="tab <?php echo $example == 'while' ? 'active' : ''; ?>" onclick="showTab('while')">
                🔄 WHILE
            </button>
            <button class="tab <?php echo $example == 'dowhile' ? 'active' : ''; ?>" onclick="showTab('dowhile')">
                ⚡ DO WHILE
            </button>
            <button class="tab <?php echo $example == 'for' ? 'active' : ''; ?>" onclick="showTab('for')">
                🎯 FOR
            </button>
        </div>
        
        <!-- Пример 1: Оператор SWITCH -->
        <div id="switch-tab" class="tab-content <?php echo $example == 'switch' ? 'active' : ''; ?>">
            <h2 class="example-header">🔀 Пример 1: Оператор SWITCH</h2>
            <p class="example-description">
                Использование оператора SWITCH для расчета стоимости тура в зависимости от города и класса отеля.
            </p>
            
            <div class="form-container">
                <form method="POST" action="?example=switch">
                    <div class="form-group">
                        <label>Выберите город:</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="destination" value="Alania" <?php echo ($destination == 'Alania') ? 'checked' : ''; ?> required>
                                <strong>Алания</strong><br>
                                <small>×1.5</small>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="destination" value="Kimer" <?php echo ($destination == 'Kimer') ? 'checked' : ''; ?> required>
                                <strong>Кемер</strong><br>
                                <small>×2.0</small>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="destination" value="Antalia" <?php echo ($destination == 'Antalia') ? 'checked' : ''; ?> required>
                                <strong>Анталия</strong><br>
                                <small>×3.5</small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Выберите класс отеля:</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="grade" value="three" <?php echo ($grade == 'three') ? 'checked' : ''; ?> required>
                                <strong>3 звезды</strong><br>
                                <small>×3</small>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="grade" value="four" <?php echo ($grade == 'four') ? 'checked' : ''; ?> required>
                                <strong>4 звезды</strong><br>
                                <small>×4</small>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="grade" value="five" <?php echo ($grade == 'five') ? 'checked' : ''; ?> required>
                                <strong>5 звезд</strong><br>
                                <small>×5</small>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" name="example1_submit">Рассчитать стоимость</button>
                </form>
            </div>
            
            <?php if ($example == 'switch' && $result_message): ?>
                <div class="result-box <?php echo $result_class; ?>">
                    <h3>Результат:</h3>
                    <p><?php echo $result_message; ?></p>
                    <?php if ($total_price > 0): ?>
                        <div class="price-display"><?php echo number_format($total_price, 0, ',', ' '); ?> ₽</div>
                        <p>Базовая цена: 500 ₽ × Коэффициент города × Коэффициент отеля</p>
                    <?php endif; ?>
                </div>
                
                <div class="code-example">
                    <strong>Пример кода SWITCH:</strong><br>
                    switch($destgrade) {<br>
                    &nbsp;&nbsp;case "Kimerthree":<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;$price = $base_price * 2 * 3;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;break;<br>
                    &nbsp;&nbsp;case "Kimerfour":<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;$price = $base_price * 2 * 4;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;break;<br>
                    &nbsp;&nbsp;// ... другие case<br>
                    &nbsp;&nbsp;default:<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;echo "Неверный выбор";<br>
                    }
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Пример 2: Цикл WHILE -->
        <div id="while-tab" class="tab-content <?php echo $example == 'while' ? 'active' : ''; ?>">
            <h2 class="example-header">🔄 Пример 2: Цикл WHILE</h2>
            <p class="example-description">
                Расчет срока погашения кредита с использованием цикла WHILE.
            </p>
            
            <div class="form-container">
                <form method="POST" action="?example=while">
                    <div class="form-group">
                        <label>Сумма кредита (₽):</label>
                        <input type="number" name="loan" value="<?php echo htmlspecialchars($loan_amount); ?>" 
                               min="100" max="100000" step="100" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Ежемесячный платеж (₽):</label>
                        <input type="number" name="month" value="<?php echo htmlspecialchars($monthly_payment); ?>" 
                               min="10" max="50000" step="10" required>
                    </div>
                    
                    <button type="submit" name="example2_submit">Рассчитать срок</button>
                </form>
            </div>
            
            <?php if ($example == 'while' && $result_message): ?>
                <div class="result-box <?php echo $result_class; ?>">
                    <h3>Результат:</h3>
                    <p><?php echo $result_message; ?></p>
                    <?php if ($duration > 0 && $result_class == 'success'): ?>
                        <div class="duration-display">
                            <?php 
                            $years = floor($duration / 12);
                            $months = $duration % 12;
                            if ($years > 0) {
                                echo $years . ' лет ' . $months . ' месяцев';
                            } else {
                                echo $months . ' месяцев';
                            }
                            ?>
                        </div>
                        <p>Процентная ставка: <?php echo $interest_rate; ?>% годовых</p>
                    <?php endif; ?>
                </div>
                
                <div class="code-example">
                    <strong>Пример кода WHILE:</strong><br>
                    while ($remaining_loan > 0) {<br>
                    &nbsp;&nbsp;$duration++;<br>
                    &nbsp;&nbsp;$month_interest = $remaining_loan * $monthly_interest;<br>
                    &nbsp;&nbsp;$principal_payment = $monthly_payment - $month_interest;<br>
                    &nbsp;&nbsp;$remaining_loan -= $principal_payment;<br>
                    }
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Пример 3: Цикл DO WHILE -->
        <div id="dowhile-tab" class="tab-content <?php echo $example == 'dowhile' ? 'active' : ''; ?>">
            <h2 class="example-header">⚡ Пример 3: Цикл DO WHILE</h2>
            <p class="example-description">
                Проверка числа на простоту с использованием цикла DO WHILE.
            </p>
            
            <div class="form-container">
                <form method="POST" action="?example=dowhile">
                    <div class="form-group">
                        <label>Введите целое положительное число:</label>
                        <input type="number" name="number" value="<?php echo htmlspecialchars($number); ?>" 
                               min="1" max="1000000" required>
                    </div>
                    
                    <button type="submit" name="example3_submit">Проверить</button>
                </form>
            </div>
            
            <?php if ($example == 'dowhile' && $result_message): ?>
                <div class="result-box <?php echo $result_class; ?>">
                    <h3>Результат:</h3>
                    <div class="number-display"><?php echo $number; ?></div>
                    <p><?php echo $result_message; ?></p>
                    <?php if ($iterations > 0): ?>
                        <p>Количество проверок: <?php echo $iterations; ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="code-example">
                    <strong>Пример кода DO WHILE:</strong><br>
                    do {<br>
                    &nbsp;&nbsp;$iterations++;<br>
                    &nbsp;&nbsp;if ($number % $divisor == 0) {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;$is_prime = false;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;break;<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;$divisor++;<br>
                    } while ($divisor <= $limit);
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Пример 4: Цикл FOR -->
        <div id="for-tab" class="tab-content <?php echo $example == 'for' ? 'active' : ''; ?>">
            <h2 class="example-header">🎯 Пример 4: Цикл FOR</h2>
            <p class="example-description">
                Создание динамических полей формы с использованием цикла FOR.
            </p>
            
            <?php if ($stage == 'input'): ?>
                <div class="form-container">
                    <form method="POST" action="?example=for">
                        <div class="form-group">
                            <label>Сколько у вас детей? (1-20):</label>
                            <input type="number" name="children_count" value="<?php echo $children_count; ?>" 
                                   min="1" max="20" required>
                        </div>
                        
                        <button type="submit" name="example4_submit">Далее</button>
                    </form>
                </div>
            <?php elseif ($stage == 'names'): ?>
                <div class="form-container">
                    <form method="POST" action="?example=for">
                        <h3>Введите имена <?php echo $children_count; ?> детей:</h3>
                        
                        <div class="children-inputs">
                            <?php for ($i = 0; $i < $children_count; $i++): ?>
                                <div class="child-input">
                                    <label>Ребенок <?php echo $i + 1; ?>:</label>
                                    <input type="text" name="child[]" 
                                           placeholder="Имя ребенка <?php echo $i + 1; ?>"
                                           value="<?php echo htmlspecialchars($children_names[$i] ?? ''); ?>">
                                </div>
                            <?php endfor; ?>
                        </div>
                        
                        <div style="display: flex; gap: 15px; margin-top: 20px;">
                            <button type="submit" name="example4_names_submit">Сохранить</button>
                            <button type="submit" name="example4_reset" style="background: #6c757d;">Сброс</button>
                        </div>
                    </form>
                </div>
            <?php elseif ($stage == 'results'): ?>
                <div class="result-box <?php echo $result_class; ?>">
                    <h3>Результат:</h3>
                    <p><?php echo $result_message; ?></p>
                    
                    <?php if (count($children_names) > 0): ?>
                        <div class="children-list">
                            <?php foreach ($children_names as $index => $name): ?>
                                <?php if (!empty($name)): ?>
                                    <div class="child-card">
                                        <div class="child-number"><?php echo $index + 1; ?></div>
                                        <strong><?php echo htmlspecialchars($name); ?></strong>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="?example=for" style="margin-top: 20px;">
                        <button type="submit" name="example4_reset">Новый ввод</button>
                    </form>
                </div>
                
                <div class="code-example">
                    <strong>Пример кода FOR:</strong><br>
                    for ($i = 0; $i < $children_count; $i++) {<br>
                    &nbsp;&nbsp;echo '&lt;input type="text" name="child[]"&gt;';<br>
                    }
                </div>
            <?php endif; ?>
        </div>
        
        <div style="padding: 30px; border-top: 1px solid #dee2e6;">
            <a href="index.php" class="back-btn">← Назад на главную</a>
        </div>
    </div>
    
    <script>
        function showTab(tabName) {
            // Обновляем URL без перезагрузки страницы
            window.history.pushState({}, '', '?example=' + tabName);
            
            // Скрываем все вкладки
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Показываем выбранную вкладку
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Обновляем активную кнопку таба
            const tabButtons = document.querySelectorAll('.tab');
            tabButtons.forEach(button => {
                button.classList.remove('active');
                if (button.onclick.toString().includes(tabName)) {
                    button.classList.add('active');
                }
            });
        }
        
        // При загрузке страницы показываем правильную вкладку
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const example = urlParams.get('example') || 'switch';
            showTab(example);
        });
    </script>
</body>
</html>