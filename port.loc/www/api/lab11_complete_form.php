<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Обработка формы, если она отправлена
$formSubmitted = false;
$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formSubmitted = true;
    $formData = $_POST;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Комплексная форма - Лабораторная работа №11</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .container { max-width: 1000px; margin: 0 auto; }
        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #444; }
        .form-control { width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 16px; }
        .checkbox-group, .radio-group { display: flex; flex-direction: column; gap: 10px; margin: 15px 0; }
        .checkbox-item, .radio-item { display: flex; align-items: center; gap: 10px; }
        .btn { display: inline-block; padding: 12px 25px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .result-box { background: #e8f5e9; border: 2px solid #28a745; padding: 20px; border-radius: 10px; margin-top: 30px; }
        .back-btn { display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1 style="color: #28a745; margin-bottom: 20px;">📋 Комплексная форма со всеми элементами</h1>
            
            <form method="POST" action="">
                <!-- Текстовое поле -->
                <div class="form-group">
                    <label class="form-label">Имя пользователя:</label>
                    <input type="text" name="username" class="form-control" placeholder="Введите ваше имя">
                </div>
                
                <!-- Текстовая область -->
                <div class="form-group">
                    <label class="form-label">О себе:</label>
                    <textarea name="about" class="form-control" rows="4" placeholder="Расскажите о себе"></textarea>
                </div>
                
                <!-- Флажки -->
                <div class="form-group">
                    <label class="form-label">Выберите ваши увлечения:</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item"><input type="checkbox" name="hobbies[]" value="Спорт"> <label>Спорт</label></div>
                        <div class="checkbox-item"><input type="checkbox" name="hobbies[]" value="Чтение"> <label>Чтение</label></div>
                        <div class="checkbox-item"><input type="checkbox" name="hobbies[]" value="Музыка"> <label>Музыка</label></div>
                        <div class="checkbox-item"><input type="checkbox" name="hobbies[]" value="Путешествия"> <label>Путешествия</label></div>
                    </div>
                </div>
                
                <!-- Переключатели -->
                <div class="form-group">
                    <label class="form-label">Ваш уровень образования:</label>
                    <div class="radio-group">
                        <div class="radio-item"><input type="radio" name="education" value="Среднее"> <label>Среднее</label></div>
                        <div class="radio-item"><input type="radio" name="education" value="Среднее специальное"> <label>Среднее специальное</label></div>
                        <div class="radio-item"><input type="radio" name="education" value="Высшее"> <label>Высшее</label></div>
                        <div class="radio-item"><input type="radio" name="education" value="Ученая степень"> <label>Ученая степень</label></div>
                    </div>
                </div>
                
                <!-- Списки -->
                <div class="form-group">
                    <label class="form-label">Ваш город:</label>
                    <select name="city" class="form-control">
                        <option value="">Выберите город</option>
                        <option value="Москва">Москва</option>
                        <option value="Санкт-Петербург">Санкт-Петербург</option>
                        <option value="Екатеринбург">Екатеринбург</option>
                        <option value="Оренбург">Оренбург</option>
                    </select>
                </div>
                
                <!-- Множественный список -->
                <div class="form-group">
                    <label class="form-label">Языки, которыми вы владеете:</label>
                    <select name="languages[]" multiple size="4" class="form-control">
                        <option value="Русский">Русский</option>
                        <option value="Английский">Английский</option>
                        <option value="Немецкий">Немецкий</option>
                        <option value="Французский">Французский</option>
                        <option value="Испанский">Испанский</option>
                    </select>
                </div>
                
                <button type="submit" class="btn">Отправить форму</button>
            </form>
            
            <?php if ($formSubmitted): ?>
            <div class="result-box">
                <h3>📊 Результаты обработки формы:</h3>
                
                <div class="form-group">
                    <h4>Имя пользователя:</h4>
                    <p><?php echo isset($formData['username']) ? htmlspecialchars($formData['username']) : 'Не указано'; ?></p>
                </div>
                
                <div class="form-group">
                    <h4>О себе:</h4>
                    <p><?php echo isset($formData['about']) ? nl2br(htmlspecialchars($formData['about'])) : 'Не указано'; ?></p>
                </div>
                
                <div class="form-group">
                    <h4>Увлечения:</h4>
                    <p>
                    <?php 
                    if (isset($formData['hobbies']) && is_array($formData['hobbies'])) {
                        echo implode(', ', array_map('htmlspecialchars', $formData['hobbies']));
                    } else {
                        echo 'Не выбраны';
                    }
                    ?>
                    </p>
                </div>
                
                <div class="form-group">
                    <h4>Образование:</h4>
                    <p><?php echo isset($formData['education']) ? htmlspecialchars($formData['education']) : 'Не выбрано'; ?></p>
                </div>
                
                <div class="form-group">
                    <h4>Город:</h4>
                    <p><?php echo isset($formData['city']) ? htmlspecialchars($formData['city']) : 'Не выбран'; ?></p>
                </div>
                
                <div class="form-group">
                    <h4>Языки:</h4>
                    <p>
                    <?php 
                    if (isset($formData['languages']) && is_array($formData['languages'])) {
                        echo implode(', ', array_map('htmlspecialchars', $formData['languages']));
                    } else {
                        echo 'Не выбраны';
                    }
                    ?>
                    </p>
                </div>
                
                <h4>Все данные формы (массив $_POST):</h4>
                <pre style="background: white; padding: 10px; border-radius: 5px; overflow: auto;"><?php print_r($formData); ?></pre>
            </div>
            <?php endif; ?>
        </div>
        
        <a href="lab11_index.php" class="back-btn">Вернуться к лабораторной работе</a>
        <a href="index.php" class="back-btn" style="background: #6c757d;">На главную</a>
    </div>
</body>
</html>