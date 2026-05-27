<?php
session_start();
require_once 'check_access.php';
require_once 'permissions.php';

// Проверяем, есть ли право на добавление
if (!canAdd()) {
    $_SESSION['access_error'] = "У вас нет прав для добавления данных";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ввод данных в портовую БД</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .form-container { border: 1px solid #ccc; padding: 20px; margin: 10px 0; background: #f9f9f9; }
        label { display: inline-block; width: 150px; margin: 5px 0; }
        input, select { padding: 5px; margin: 5px 0; width: 300px; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; margin: 10px 5px; }
        .tab { overflow: hidden; border: 1px solid #ccc; background: #f1f1f1; }
        .tab button { background-color: inherit; float: left; border: none; outline: none; cursor: pointer; padding: 14px 16px; transition: 0.3s; }
        .tab button:hover { background-color: #ddd; }
        .tab button.active { background-color: #ccc; }
        .tabcontent { display: none; padding: 20px; border: 1px solid #ccc; border-top: none; }
        .message { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <h1>Ввод данных в портовую базу данных</h1>
    
    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'employees')">Сотрудники</button>
        <button class="tablinks" onclick="openTab(event, 'cargo')">Грузы</button>
        <button class="tablinks" onclick="openTab(event, 'vessels')">Суда</button>
        <button class="tablinks" onclick="openTab(event, 'operations')">Операции</button>
        <button class="tablinks" onclick="openTab(event, 'storage')">Складские зоны</button>
    </div>
    
    <!-- Форма для сотрудников -->
    <div id="employees" class="tabcontent" style="display: block;">
        <div class="form-container">
            <h2>Добавление сотрудника</h2>
            <form method="POST" action="insert_data.php">
                <input type="hidden" name="table" value="employees">
                <label>Имя:</label><input type="text" name="first_name" required><br>
                <label>Фамилия:</label><input type="text" name="last_name" required><br>
                <label>Должность:</label><input type="text" name="position" required><br>
                <label>Отдел:</label><input type="text" name="department"><br>
                <label>Дата приема (ГГГГ-ММ-ДД):</label><input type="date" name="hire_date" required><br>
                <label>Зарплата:</label><input type="number" step="0.01" name="salary"><br>
                <label>Email:</label><input type="email" name="email"><br>
                <label>Телефон:</label><input type="text" name="phone"><br>
                <button type="submit" name="submit">Добавить сотрудника</button>
            </form>
        </div>
    </div>
    
    <!-- Форма для грузов -->
    <div id="cargo" class="tabcontent">
        <div class="form-container">
            <h2>Добавление груза</h2>
            <form method="POST" action="insert_data.php">
                <input type="hidden" name="table" value="cargo">
                <label>Код груза:</label><input type="text" name="cargo_code" required><br>
                <label>Описание:</label><textarea name="description" rows="3" cols="40"></textarea><br>
                <label>Тип груза:</label>
                <select name="cargo_type" required>
                    <option value="Контейнер">Контейнер</option>
                    <option value="Навалочный">Навалочный</option>
                    <option value="Генеральный">Генеральный</option>
                    <option value="Жидкий">Жидкий</option>
                    <option value="Рефрижераторный">Рефрижераторный</option>
                </select><br>
                <label>Вес (кг):</label><input type="number" step="0.01" name="weight_kg" required><br>
                <label>Объем (м³):</label><input type="number" step="0.01" name="volume_m3"><br>
                <label>Компания-владелец:</label><input type="text" name="owner_company"><br>
                <label>Порт назначения:</label><input type="text" name="destination_port"><br>
                <label>Дата прибытия:</label><input type="date" name="arrival_date" required><br>
                <label>Статус:</label>
                <select name="status">
                    <option value="В ожидании">В ожидании</option>
                    <option value="Разгрузка">Разгрузка</option>
                    <option value="На складе">На складе</option>
                    <option value="Погрузка">Погрузка</option>
                    <option value="Отправлен">Отправлен</option>
                </select><br>
                <button type="submit" name="submit">Добавить груз</button>
            </form>
        </div>
    </div>
    
    <!-- Форма для судов -->
    <div id="vessels" class="tabcontent">
        <div class="form-container">
            <h2>Добавление судна</h2>
            <form method="POST" action="insert_data.php">
                <input type="hidden" name="table" value="vessels">
                <label>Название судна:</label><input type="text" name="vessel_name" required><br>
                <label>IMO номер:</label><input type="text" name="imo_number" required><br>
                <label>Страна флага:</label><input type="text" name="flag_country"><br>
                <label>Тип судна:</label><input type="text" name="vessel_type"><br>
                <label>Вместимость (тонн):</label><input type="number" step="0.01" name="capacity_tonnage"><br>
                <label>Год постройки:</label><input type="number" name="year_built" min="1900" max="2024"><br>
                <label>Статус:</label>
                <select name="current_status">
                    <option value="В порту">В порту</option>
                    <option value="В пути">В пути</option>
                    <option value="На ремонте">На ремонте</option>
                    <option value="В ожидании">В ожидании</option>
                </select><br>
                <label>Порт приписки:</label><input type="text" name="port_of_registry"><br>
                <button type="submit" name="submit">Добавить судно</button>
            </form>
        </div>
    </div>
    
    <!-- Форма для операций -->
    <div id="operations" class="tabcontent">
        <div class="form-container">
            <h2>Добавление операции</h2>
            <form method="POST" action="insert_data.php">
                <input type="hidden" name="table" value="operations">
                <label>Код груза:</label><input type="number" name="cargo_id" required><br>
                <label>ID судна:</label><input type="number" name="vessel_id" required><br>
                <label>ID сотрудника:</label><input type="number" name="employee_id" required><br>
                <label>Тип операции:</label>
                <select name="operation_type" required>
                    <option value="Разгрузка">Разгрузка</option>
                    <option value="Погрузка">Погрузка</option>
                    <option value="Перемещение">Перемещение</option>
                    <option value="Инспекция">Инспекция</option>
                </select><br>
                <label>Дата и время операции:</label><input type="datetime-local" name="operation_date" required><br>
                <label>Место проведения:</label><input type="text" name="location"><br>
                <label>Длительность (минут):</label><input type="number" name="duration_minutes"><br>
                <label>Используемое оборудование:</label><input type="text" name="equipment_used"><br>
                <label>Примечания:</label><textarea name="notes" rows="3" cols="40"></textarea><br>
                <button type="submit" name="submit">Добавить операцию</button>
            </form>
        </div>
    </div>
    
    <!-- Форма для складских зон -->
    <div id="storage" class="tabcontent">
        <div class="form-container">
            <h2>Добавление складской зоны</h2>
            <form method="POST" action="insert_data.php">
                <input type="hidden" name="table" value="storage_zones">
                <label>Код зоны:</label><input type="text" name="zone_code" required><br>
                <label>Название зоны:</label><input type="text" name="zone_name" required><br>
                <label>Тип зоны:</label>
                <select name="zone_type" required>
                    <option value="Открытая">Открытая</option>
                    <option value="Закрытая">Закрытая</option>
                    <option value="Холодильная">Холодильная</option>
                    <option value="Опасные грузы">Опасные грузы</option>
                </select><br>
                <label>Макс. вместимость (т):</label><input type="number" step="0.01" name="max_capacity_ton"><br>
                <label>Температурный режим:</label><input type="text" name="temperature_condition"><br>
                <label>Координаты:</label><input type="text" name="location_coordinates"><br>
                <label>Активна:</label>
                <select name="is_active">
                    <option value="1">Да</option>
                    <option value="0">Нет</option>
                </select><br>
                <button type="submit" name="submit">Добавить зону</button>
            </form>
        </div>
    </div>
    <div style="margin: 20px 0;">
    <a href="update_form.php" style="padding: 10px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin: 0 5px;">Обновление данных</a>
    <a href="delete_form.php" style="padding: 10px 15px; background: #f44336; color: white; text-decoration: none; border-radius: 5px; margin: 0 5px;">Удаление данных</a>
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