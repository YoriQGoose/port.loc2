<?php
session_start();
require_once 'check_access.php';
require_once 'permissions.php';

// Проверяем, есть ли право на удаление
if (!canDelete()) {
    $_SESSION['access_error'] = "У вас нет прав для удаления данных";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Удаление данных из портовой БД</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .form-container { border: 1px solid #ccc; padding: 20px; margin: 20px 0; background: #f9f9f9; }
        label { display: inline-block; width: 200px; margin: 10px 0; }
        input, select { padding: 8px; margin: 5px 0; width: 300px; }
        button { padding: 10px 20px; background: #f44336; color: white; border: none; cursor: pointer; margin: 10px 5px; }
        .button-confirm { background: #d32f2f; }
        .message { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .nav { margin: 20px 0; }
        .nav a { padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 0 5px; }
        .record-info { background: #ffebee; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 5px solid #f44336; }
        .confirm-dialog { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.3); z-index: 1000; }
        .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; }
    </style>
    <script>
        function showConfirmDialog(table, id) {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('confirmDialog').style.display = 'block';
            document.getElementById('confirmTable').value = table;
            document.getElementById('confirmId').value = id;
        }
        
        function hideConfirmDialog() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('confirmDialog').style.display = 'none';
        }
        
        function submitDelete() {
            document.getElementById('deleteForm').submit();
        }
    </script>
</head>
<body>
    <h1>Удаление данных из портовой базы данных</h1>
    
    <div class="nav">
        <a href="form_all.php">Ввод данных</a>
        <a href="search_form.php">Поиск данных</a>
        <a href="view_all.php">Просмотр данных</a>
        <a href="update_form.php">Обновление данных</a>
    </div>
    
    <div class="message warning">
        <h3>⚠️ Внимание!</h3>
        <p>Удаление данных является необратимой операцией. Пожалуйста, будьте осторожны.</p>
    </div>
    
    <div class="form-container">
        <h2>Удаление записи</h2>
        
        <form method="POST" action="delete_process.php" id="deleteForm">
            <label>Выберите таблицу:</label>
            <select name="table" id="tableSelect" required>
                <option value="">-- Выберите таблицу --</option>
                <option value="employees">Сотрудники</option>
                <option value="cargoes">Грузы</option>
                <option value="vessels">Суда</option>
                <option value="operations">Операции</option>
                <option value="customers">Клиенты</option>
            </select><br>
            
            <label>Введите ID записи для удаления:</label>
            <input type="number" name="record_id" id="recordId" required placeholder="Введите ID записи"><br>
            
            <button type="button" onclick="validateAndShowDialog()">Проверить и удалить</button>
            <a href="view_all.php" style="margin-left: 20px;">Отмена</a>
        </form>
    </div>
    
    <!-- Диалоговое окно подтверждения -->
    <div id="overlay" class="overlay"></div>
    <div id="confirmDialog" class="confirm-dialog">
        <h3>⚠️ Подтверждение удаления</h3>
        <p>Вы действительно хотите удалить эту запись?</p>
        <p>Эта операция необратима!</p>
        
        <form method="POST" action="delete_process.php" id="confirmForm">
            <input type="hidden" name="table" id="confirmTable">
            <input type="hidden" name="record_id" id="confirmId">
            <input type="hidden" name="confirmed" value="1">
            
            <button type="button" onclick="submitDelete()" class="button-confirm">Да, удалить</button>
            <button type="button" onclick="hideConfirmDialog()" style="background: #6c757d;">Отмена</button>
        </form>
    </div>
    
    <script>
        function validateAndShowDialog() {
            var table = document.getElementById('tableSelect').value;
            var id = document.getElementById('recordId').value;
            
            if (!table || !id) {
                alert('Пожалуйста, выберите таблицу и введите ID записи!');
                return;
            }
            
            // Проверяем существование записи
            fetch('check_record.php?table=' + table + '&id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        // Показываем информацию о записи
                        var info = 'Вы собираетесь удалить запись:\n\n';
                        for (var key in data.data) {
                            info += key + ': ' + data.data[key] + '\n';
                        }
                        info += '\nПродолжить?';
                        
                        if (confirm(info)) {
                            showConfirmDialog(table, id);
                        }
                    } else {
                        alert('Запись с ID ' + id + ' не найдена в таблице ' + table + '!');
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    alert('Ошибка при проверке записи. Продолжить удаление?');
                    showConfirmDialog(table, id);
                });
        }
    </script>
</body>
</html>