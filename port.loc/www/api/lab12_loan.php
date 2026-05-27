<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Инициализация переменных
$errors = [];
$form_data = [];
$calculation_result = null;
$decision = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['posted'])) {
    // Сбор данных
    $form_data = [
        'FirstName' => trim($_POST['FirstName'] ?? ''),
        'LastName' => trim($_POST['LastName'] ?? ''),
        'Age' => $_POST['Age'] ?? '',
        'Address' => trim($_POST['Address'] ?? ''),
        'Salary' => $_POST['Salary'] ?? '',
        'Loan' => $_POST['Loan'] ?? ''
    ];
    
    // Валидация данных
    if (empty($form_data['FirstName'])) {
        $errors[] = "Необходимо ввести имя";
    }
    
    if (empty($form_data['LastName'])) {
        $errors[] = "Необходимо ввести фамилию";
    }
    
    if (empty($form_data['Age']) || !is_numeric($form_data['Age'])) {
        $errors[] = "Необходимо ввести корректный возраст";
    } elseif ($form_data['Age'] < 18 || $form_data['Age'] > 100) {
        $errors[] = "Возраст должен быть от 18 до 100 лет";
    }
    
    if (empty($form_data['Address'])) {
        $errors[] = "Необходимо ввести адрес";
    }
    
    if (empty($form_data['Salary']) || !is_numeric($form_data['Salary'])) {
        $errors[] = "Необходимо выбрать размер заработной платы";
    }
    
    $valid_loans = ['1000', '5000', '10000'];
    if (empty($form_data['Loan']) || !in_array($form_data['Loan'], $valid_loans)) {
        $errors[] = "Необходимо выбрать сумму кредита";
    }
    
    // Если ошибок нет, производим расчет
    if (empty($errors)) {
        // Расчет кредита по формуле из лабораторной работы
        $SalaryAllowance = $form_data['Salary'] / 5;
        $AgeAllowance = floor($form_data['Age'] / 10) - 1;
        $LoanAllowance = $SalaryAllowance * $AgeAllowance;
        
        $calculation_result = [
            'SalaryAllowance' => $SalaryAllowance,
            'AgeAllowance' => $AgeAllowance,
            'LoanAllowance' => $LoanAllowance
        ];
        
        // Принятие решения
        if ($form_data['Loan'] <= $LoanAllowance) {
            $decision = 'approved';
        } else {
            $decision = 'rejected';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кредитная заявка - Лабораторная работа №12</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .container { max-width: 1000px; margin: 0 auto; }
        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .result-box { background: #fff3e0; border: 2px solid #fd7e14; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .error-message { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .success-message { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .warning-message { background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .back-btn { display: inline-block; padding: 10px 20px; background: #fd7e14; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; margin-right: 10px; }
        h1 { color: #fd7e14; margin-bottom: 20px; }
        .data-item { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .calculation-box { background: #e8f4fc; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .decision-approved { background: #d4edda; padding: 30px; border-radius: 10px; text-align: center; font-size: 24px; margin: 20px 0; }
        .decision-rejected { background: #f8d7da; padding: 30px; border-radius: 10px; text-align: center; font-size: 24px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🏦 Результат обработки кредитной заявки</h1>
            
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <h3>❌ Обнаружены ошибки:</h3>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p style="margin-top: 10px;">Пожалуйста, вернитесь назад и исправьте ошибки.</p>
                    </div>
                <?php else: ?>
                    <div class="result-box">
                        <h3>📋 Данные заявки:</h3>
                        
                        <div class="data-item">
                            <p><strong>Заявитель:</strong> <?php echo htmlspecialchars($form_data['FirstName'] . ' ' . $form_data['LastName']); ?></p>
                            <p><strong>Возраст:</strong> <?php echo htmlspecialchars($form_data['Age']); ?> лет</p>
                            <p><strong>Адрес:</strong> <?php echo nl2br(htmlspecialchars($form_data['Address'])); ?></p>
                            <p><strong>Заработная плата:</strong> 
                                <?php 
                                $salary_text = '';
                                switch ($form_data['Salary']) {
                                    case '0': $salary_text = 'До $10,000'; break;
                                    case '10000': $salary_text = '$10,000 - $25,000'; break;
                                    case '25000': $salary_text = '$25,000 - $50,000'; break;
                                    case '50000': $salary_text = 'Свыше $50,000'; break;
                                    default: $salary_text = 'Не указано';
                                }
                                echo $salary_text;
                                ?>
                            </p>
                            <p><strong>Запрашиваемая сумма кредита:</strong> $<?php echo number_format($form_data['Loan'], 0, '.', ','); ?></p>
                        </div>
                        
                        <div class="calculation-box">
                            <h3>🧮 Расчет кредитоспособности:</h3>
                            
                            <p><strong>1. Норма зарплаты:</strong> Зарплата / 5</p>
                            <p style="margin-left: 20px;">= $<?php echo number_format($form_data['Salary'], 0, '.', ','); ?> / 5 = <strong>$<?php echo number_format($calculation_result['SalaryAllowance'], 0, '.', ','); ?></strong></p>
                            
                            <p><strong>2. Возрастной ценз:</strong> floor(Возраст / 10) - 1</p>
                            <p style="margin-left: 20px;">= floor(<?php echo $form_data['Age']; ?> / 10) - 1 = <strong><?php echo $calculation_result['AgeAllowance']; ?></strong></p>
                            
                            <p><strong>3. Допустимая сумма кредита:</strong> Норма зарплаты × Возрастной ценз</p>
                            <p style="margin-left: 20px;">= $<?php echo number_format($calculation_result['SalaryAllowance'], 0, '.', ','); ?> × <?php echo $calculation_result['AgeAllowance']; ?> = <strong>$<?php echo number_format($calculation_result['LoanAllowance'], 0, '.', ','); ?></strong></p>
                        </div>
                        
                        <?php if ($decision === 'approved'): ?>
                            <div class="decision-approved">
                                ✅ <strong>ЗАЯВКА ОДОБРЕНА!</strong><br>
                                <span style="font-size: 18px;">
                                    Да, <?php echo htmlspecialchars($form_data['FirstName'] . ' ' . $form_data['LastName']); ?>, 
                                    мы удовлетворим Вашу заявку на кредит!
                                </span>
                                <p style="font-size: 16px; margin-top: 10px;">
                                    Допустимая сумма: $<?php echo number_format($calculation_result['LoanAllowance'], 0, '.', ','); ?><br>
                                    Запрашиваемая сумма: $<?php echo number_format($form_data['Loan'], 0, '.', ','); ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="decision-rejected">
                                ❌ <strong>ЗАЯВКА ОТКЛОНЕНА</strong><br>
                                <span style="font-size: 18px;">
                                    Извините, <?php echo htmlspecialchars($form_data['FirstName'] . ' ' . $form_data['LastName']); ?>, 
                                    в настоящее время мы не можем принять Вашу заявку.
                                </span>
                                <p style="font-size: 16px; margin-top: 10px;">
                                    Допустимая сумма: $<?php echo number_format($calculation_result['LoanAllowance'], 0, '.', ','); ?><br>
                                    Запрашиваемая сумма: $<?php echo number_format($form_data['Loan'], 0, '.', ','); ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="warning-message">
                            <h4>⚠️ Важное примечание:</h4>
                            <p>Это учебный пример расчета кредитоспособности. В реальных банках используются более сложные алгоритмы оценки кредитных рисков.</p>
                        </div>
                        
                        <h4>Все переданные данные POST:</h4>
                        <pre style="background: white; padding: 15px; border-radius: 5px; overflow: auto;"><?php print_r($_POST); ?></pre>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="error-message">
                    <h3>⚠️ Форма не отправлена</h3>
                    <p>Для отображения результатов отправьте форму из основной страницы лабораторной работы.</p>
                </div>
            <?php endif; ?>
            
            <a href="lab12_index.php" class="back-btn">Вернуться к лабораторной работе</a>
            <a href="index.php" class="back-btn" style="background: #6c757d;">На главную</a>
        </div>
    </div>
</body>
</html>