<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Обработка формы
$form_submitted = false;
$button_pressed = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_submitted = true;
    $button_pressed = $_POST['submit_btn'] ?? 'unknown';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кнопки форм - Лабораторная работа №12</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .result-box { background: #fff3e0; border: 2px solid #fd7e14; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .back-btn { display: inline-block; padding: 10px 20px; background: #fd7e14; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; margin-right: 10px; }
        h1 { color: #fd7e14; margin-bottom: 20px; }
        .button-demo { display: flex; gap: 10px; margin: 20px 0; flex-wrap: wrap; }
        .button-demo button { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🔄 Результат обработки кнопок формы</h1>
            
            <?php if ($form_submitted): ?>
                <div class="result-box">
                    <h3>✅ Форма успешно отправлена!</h3>
                    <p>Была нажата кнопка: <strong><?php echo htmlspecialchars($button_pressed); ?></strong></p>
                    
                    <h4>📋 Полученные данные:</h4>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0;">
                        <p><strong>Имя:</strong> <?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : 'Не указано'; ?></p>
                        <p><strong>Фамилия:</strong> <?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : 'Не указано'; ?></p>
                        <p><strong>Комментарий:</strong> <?php echo isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : 'Не указано'; ?></p>
                    </div>
                    
                    <h4>Все переданные данные POST:</h4>
                    <pre style="background: white; padding: 15px; border-radius: 5px; overflow: auto;"><?php print_r($_POST); ?></pre>
                </div>
                
                <div style="background: #e8f4fc; padding: 15px; border-radius: 8px; margin-top: 20px;">
                    <h4>📝 Типы кнопок в HTML:</h4>
                    <div class="button-demo">
                        <button type="submit" style="background: #28a745; color: white;">Submit (отправка формы)</button>
                        <button type="reset" style="background: #dc3545; color: white;">Reset (сброс формы)</button>
                        <button type="button" style="background: #17a2b8; color: white;" onclick="alert('Это обычная кнопка')">Button (обычная)</button>
                    </div>
                    <ul style="margin-top: 15px; margin-left: 20px;">
                        <li><strong>type="submit"</strong> - отправляет данные формы на сервер</li>
                        <li><strong>type="reset"</strong> - сбрасывает все поля формы к исходным значениям</li>
                        <li><strong>type="button"</strong> - обычная кнопка, не влияет на форму (используется с JavaScript)</li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="result-box">
                    <h3>⚠️ Форма не отправлена</h3>
                    <p>Для отображения результатов отправьте форму из основной страницы лабораторной работы.</p>
                    
                    <div style="background: #e8f4fc; padding: 15px; border-radius: 8px; margin-top: 20px;">
                        <h4>📝 Демонстрация кнопок прямо здесь:</h4>
                        <form method="POST" action="">
                            <div style="margin-bottom: 15px;">
                                <label>Тестовое поле:</label>
                                <input type="text" name="test_field" value="Пример значения" style="width: 100%; padding: 8px; margin-top: 5px;">
                            </div>
                            
                            <div class="button-demo">
                                <button type="submit" name="submit_btn" value="send" style="background: #28a745; color: white;">
                                    📤 Отправить форму
                                </button>
                                <button type="reset" style="background: #dc3545; color: white;">
                                    🗑️ Сбросить форму
                                </button>
                                <button type="button" style="background: #17a2b8; color: white;" onclick="alert('Это JavaScript действие!')">
                                    ⚡ JavaScript кнопка
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            
            <a href="lab12_index.php" class="back-btn">Вернуться к лабораторной работе</a>
            <a href="index.php" class="back-btn" style="background: #6c757d;">На главную</a>
        </div>
    </div>
</body>
</html>