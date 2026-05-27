<?php
session_start();
require_once 'check_access.php';
require_once 'permissions.php';

// Проверяем, есть ли право на редактирование
if (!canEdit()) {
    $_SESSION['access_error'] = "У вас нет прав для редактирования данных";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Обновление данных в портовой БД</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .form-container { border: 1px solid #ccc; padding: 20px; margin: 20px 0; background: #f9f9f9; }
        label { display: inline-block; width: 200px; margin: 10px 0; }
        input, select { padding: 8px; margin: 5px 0; width: 300px; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; margin: 10px 5px; }
        .button-red { background: #f44336; }
        .message { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .record-info { background: #e3f2fd; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .nav { margin: 20px 0; }
        .nav a { padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 0 5px; }
        .tab { overflow: hidden; border: 1px solid #ccc; background: #f1f1f1; }
        .tab button { background-color: inherit; float: left; border: none; outline: none; cursor: pointer; padding: 14px 16px; transition: 0.3s; }
        .tab button:hover { background-color: #ddd; }
        .tab button.active { background-color: #ccc; }
        .tabcontent { display: none; padding: 20px; border: 1px solid #ccc; border-top: none; }
    </style>
</head>
<body>
    <h1>Обновление данных в портовой базе данных</h1>
    
    <div class="nav">
        <a href="form_all.php">Ввод данных</a>
        <a href="search_form.php">Поиск данных</a>
        <a href="view_all.php">Просмотр данных</a>
        <a href="delete_form.php">Удаление данных</a>
    </div>
    
    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'employees')">Сотрудники</button>
        <button class="tablinks" onclick="openTab(event, 'cargo')">Грузы</button>
        <button class="tablinks" onclick="openTab(event, 'vessels')">Суда</button>
        <button class="tablinks" onclick="openTab(event, 'operations')">Операции</button>
        <button class="tablinks" onclick="openTab(event, 'customers')">Клиенты</button>
    </div>
    
    <!-- Форма для обновления сотрудников -->
    <div id="employees" class="tabcontent" style="display: block;">
        <div class="form-container">
            <h2>Обновление данных сотрудника</h2>
            <form method="POST" action="update_process.php">
                <input type="hidden" name="table" value="employees">
                
                <label>ID сотрудника для обновления:</label>
                <input type="number" name="record_id" required placeholder="Введите ID сотрудника"><br>
                
                <h3>Новые данные:</h3>
                <label>Имя:</label><input type="text" name="first_name"><br>
                <label>Фамилия:</label><input type="text" name="last_name"><br>
                <label>Должность:</label><input type="text" name="position"><br>
                <label>Отдел:</label><input type="text" name="department"><br>
                <label>Дата приема:</label><input type="date" name="hire_date"><br>
                <label>Зарплата:</label><input type="number" step="0.01" name="salary"><br>
                <label>Email:</label><input type="email" name="email"><br>
                <label>Телефон:</label><input type="text" name="phone"><br>
                
                <button type="submit" name="update">Обновить данные</button>
            </form>
        </div>
    </div>
    
    <!-- Форма для обновления грузов -->
    <div id="cargo" class="tabcontent">
        <div class="form-container">
            <h2>Обновление данных груза</h2>
            <form method="POST" action="update_process.php">
                <input type="hidden" name="table" value="cargoes">
                
                <label>ID груза для обновления:</label>
                <input type="number" name="record_id" required placeholder="Введите ID груза"><br>
                
                <h3>Новые данные:</h3>
                <label>Код груза:</label><input type="text" name="cargo_code"><br>
                <label>Описание:</label><textarea name="description" rows="3" cols="40"></textarea><br>
                <label>Тип груза:</label>
                <select name="cargo_type">
                    <option value="">-- Не изменять --</option>
                    <option value="Контейнер">Контейнер</option>
                    <option value="Навалочный">Навалочный</option>
                    <option value="Генеральный">Генеральный</option>
                    <option value="Жидкий">Жидкий</option>
                    <option value="Рефрижераторный">Рефрижераторный</option>
                </select><br>
                <label>Вес (кг):</label><input type="number" step="0.01" name="weight_kg"><br>
                <label>Объем (м³):</label><input type="number" step="0.01" name="volume_m3"><br>
                <label>Компания-владелец:</label><input type="text" name="owner_company"><br>
                <label>Порт назначения:</label><input type="text" name="destination_port"><br>
                <label>Дата прибытия:</label><input type="date" name="arrival_date"><br>
                <label>Статус:</label>
                <select name="status">
                    <option value="">-- Не изменять --</option>
                    <option value="В ожидании">В ожидании</option>
                    <option value="Разгрузка">Разгрузка</option>
                    <option value="На складе">На складе</option>
                    <option value="Погрузка">Погрузка</option>
                    <option value="Отправлен">Отправлен</option>
                </select><br>
                
                <button type="submit" name="update">Обновить данные</button>
            </form>
        </div>
    </div>
    
    <!-- Форма для обновления судов -->
    <div id="vessels" class="tabcontent">
        <div class="form-container">
            <h2>Обновление данных судна</h2>
            <form method="POST" action="update_process.php">
                <input type="hidden" name="table" value="vessels">
                
                <label>ID судна для обновления:</label>
                <input type="number" name="record_id" required placeholder="Введите ID судна"><br>
                
                <h3>Новые данные:</h3>
                <label>Название судна:</label><input type="text" name="vessel_name"><br>
                <label>IMO номер:</label><input type="text" name="imo_number"><br>
                <label>Страна флага:</label><input type="text" name="flag_country"><br>
                <label>Тип судна:</label><input type="text" name="vessel_type"><br>
                <label>Вместимость (тонн):</label><input type="number" step="0.01" name="capacity_tonnage"><br>
                <label>Год постройки:</label><input type="number" name="year_built" min="1900" max="2024"><br>
                <label>Статус:</label>
                <select name="current_status">
                    <option value="">-- Не изменять --</option>
                    <option value="В порту">В порту</option>
                    <option value="В пути">В пути</option>
                    <option value="На ремонте">На ремонте</option>
                    <option value="В ожидании">В ожидании</option>
                </select><br>
                <label>Порт приписки:</label><input type="text" name="port_of_registry"><br>
                
                <button type="submit" name="update">Обновить данные</button>
            </form>
        </div>
    </div>
    
    <!-- Форма для обновления операций -->
    <div id="operations" class="tabcontent">
        <div class="form-container">
            <h2>Обновление данных операции</h2>
            <form method="POST" action="update_process.php">
                <input type="hidden" name="table" value="operations">
                
                <label>ID операции для обновления:</label>
                <input type="number" name="record_id" required placeholder="Введите ID операции"><br>
                
                <h3>Новые данные:</h3>
                <label>ID груза:</label><input type="number" name="cargo_id"><br>
                <label>ID судна:</label><input type="number" name="vessel_id"><br>
                <label>ID сотрудника:</label><input type="number" name="employee_id"><br>
                <label>Тип операции:</label>
                <select name="operation_type">
                    <option value="">-- Не изменять --</option>
                    <option value="Разгрузка">Разгрузка</option>
                    <option value="Погрузка">Погрузка</option>
                    <option value="Перемещение">Перемещение</option>
                    <option value="Инспекция">Инспекция</option>
                </select><br>
                <label>Дата и время операции:</label><input type="datetime-local" name="operation_date"><br>
                <label>Место проведения:</label><input type="text" name="location"><br>
                <label>Длительность (минут):</label><input type="number" name="duration_minutes"><br>
                <label>Используемое оборудование:</label><input type="text" name="equipment_used"><br>
                <label>Примечания:</label><textarea name="notes" rows="3" cols="40"></textarea><br>
                
                <button type="submit" name="update">Обновить данные</button>
            </form>
        </div>
    </div>
    
    <!-- Форма для обновления клиентов -->
    <div id="customers" class="tabcontent">
        <div class="form-container">
            <h2>Обновление данных клиента</h2>
            <form method="POST" action="update_process.php">
                <input type="hidden" name="table" value="customers">
                
                <label>ID клиента для обновления:</label>
                <input type="number" name="record_id" required placeholder="Введите ID клиента"><br>
                
                <h3>Новые данные:</h3>
                <label>Название компании:</label><input type="text" name="company_name"><br>
                <label>Контактное лицо:</label><input type="text" name="contact_person"><br>
                <label>Email:</label><input type="email" name="email"><br>
                <label>Телефон:</label><input type="text" name="phone"><br>
                <label>Адрес:</label><input type="text" name="address"><br>
                <label>Город:</label><input type="text" name="city"><br>
                <label>Страна:</label><input type="text" name="country"><br>
                
                <button type="submit" name="update">Обновить данные</button>
            </form>
        </div>
    </div>
    
    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
</body>
</html>