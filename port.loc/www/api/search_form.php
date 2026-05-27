<?php
session_start();
require_once 'check_access.php';
require_once 'permissions.php';

// Проверяем, есть ли право на поиск/просмотр
if (!canView()) {
    $_SESSION['access_error'] = "У вас нет прав для поиска данных";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Поиск данных в портовой БД</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .search-container { border: 1px solid #ccc; padding: 20px; margin: 20px 0; background: #f9f9f9; }
        label { display: inline-block; width: 200px; margin: 10px 0; }
        input, select { padding: 8px; margin: 5px 0; width: 300px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; margin: 10px 5px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .results-container { margin: 20px 0; }
        .form-group { margin: 15px 0; }
    </style>
</head>
<body>
    <h1>Поиск данных в портовой базе данных</h1>
    
    <div class="search-container">
        <form method="GET" action="search_results.php">
            <div class="form-group">
                <label>Выберите таблицу для поиска:</label>
                <select name="table" id="tableSelect" required onchange="updateSearchFields()">
                    <option value="">-- Выберите таблицу --</option>
                    <option value="employees">Сотрудники</option>
                    <option value="cargo">Грузы</option>
                    <option value="vessels">Суда</option>
                    <option value="operations">Операции</option>
                    <option value="storage_zones">Складские зоны</option>
                </select>
            </div>
            
            <div id="searchFields">
                <!-- Динамически заполняемые поля поиска -->
                <div class="form-group">
                    <label>Поле для поиска:</label>
                    <select name="field" id="fieldSelect">
                        <option value="">-- Выберите поле --</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Тип сравнения:</label>
                    <select name="operator" id="operatorSelect">
                        <option value="=">Равно</option>
                        <option value="LIKE">Содержит</option>
                        <option value=">">Больше</option>
                        <option value="<">Меньше</option>
                        <option value=">=">Больше или равно</option>
                        <option value="<=">Меньше или равно</option>
                        <option value="!=">Не равно</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Значение для поиска:</label>
                    <input type="text" name="value" id="valueInput" placeholder="Введите значение для поиска">
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" name="search">Выполнить поиск</button>
                <button type="button" onclick="resetForm()">Сбросить</button>
                <a href="form_all.php" style="margin-left: 20px;">Вернуться к вводу данных</a>
            </div>
        </form>
    </div>
    
    <div class="search-container">
        <h3>Быстрый поиск по типовым условиям:</h3>
        <p><a href="search_results.php?table=cargo&field=status&operator==&value=В ожидании">Грузы в ожидании</a></p>
        <p><a href="search_results.php?table=cargo&field=weight_kg&operator=>&value=10000">Грузы тяжелее 10 тонн</a></p>
        <p><a href="search_results.php?table=employees&field=department&operator=LIKE&value=грузовой">Сотрудники грузового отдела</a></p>
        <p><a href="search_results.php?table=vessels&field=current_status&operator==&value=В порту">Суда в порту</a></p>
        <p><a href="search_results.php?table=operations&field=operation_type&operator==&value=Разгрузка">Операции разгрузки</a></p>
    </div>
    <div style="margin: 20px 0;">
    <a href="update_form.php" style="padding: 10px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin: 0 5px;">Обновление данных</a>
    <a href="delete_form.php" style="padding: 10px 15px; background: #f44336; color: white; text-decoration: none; border-radius: 5px; margin: 0 5px;">Удаление данных</a>
	</div>
    <script>
        // Объект с полями для каждой таблицы
        const tableFields = {
            'employees': [
                {name: 'first_name', label: 'Имя'},
                {name: 'last_name', label: 'Фамилия'},
                {name: 'position', label: 'Должность'},
                {name: 'department', label: 'Отдел'},
                {name: 'hire_date', label: 'Дата приема'},
                {name: 'salary', label: 'Зарплата'},
                {name: 'email', label: 'Email'},
                {name: 'phone', label: 'Телефон'}
            ],
            'cargo': [
                {name: 'cargo_code', label: 'Код груза'},
                {name: 'cargo_type', label: 'Тип груза'},
                {name: 'weight_kg', label: 'Вес (кг)'},
                {name: 'owner_company', label: 'Компания-владелец'},
                {name: 'destination_port', label: 'Порт назначения'},
                {name: 'arrival_date', label: 'Дата прибытия'},
                {name: 'status', label: 'Статус'}
            ],
            'vessels': [
                {name: 'vessel_name', label: 'Название судна'},
                {name: 'imo_number', label: 'IMO номер'},
                {name: 'flag_country', label: 'Страна флага'},
                {name: 'vessel_type', label: 'Тип судна'},
                {name: 'current_status', label: 'Текущий статус'},
                {name: 'port_of_registry', label: 'Порт приписки'}
            ],
            'operations': [
                {name: 'operation_type', label: 'Тип операции'},
                {name: 'operation_date', label: 'Дата операции'},
                {name: 'location', label: 'Место проведения'},
                {name: 'equipment_used', label: 'Оборудование'}
            ],
            'storage_zones': [
                {name: 'zone_code', label: 'Код зоны'},
                {name: 'zone_name', label: 'Название зоны'},
                {name: 'zone_type', label: 'Тип зоны'},
                {name: 'max_capacity_ton', label: 'Макс. вместимость'}
            ]
        };
        
        function updateSearchFields() {
            const tableSelect = document.getElementById('tableSelect');
            const fieldSelect = document.getElementById('fieldSelect');
            const selectedTable = tableSelect.value;
            
            // Очищаем список полей
            fieldSelect.innerHTML = '<option value="">-- Выберите поле --</option>';
            
            // Заполняем список полями выбранной таблицы
            if (selectedTable && tableFields[selectedTable]) {
                tableFields[selectedTable].forEach(field => {
                    const option = document.createElement('option');
                    option.value = field.name;
                    option.textContent = field.label;
                    fieldSelect.appendChild(option);
                });
            }
            
            // Показываем/скрываем блок с полями поиска
            document.getElementById('searchFields').style.display = selectedTable ? 'block' : 'none';
        }
        
        function resetForm() {
            document.getElementById('tableSelect').selectedIndex = 0;
            document.getElementById('fieldSelect').innerHTML = '<option value="">-- Выберите поле --</option>';
            document.getElementById('valueInput').value = '';
            document.getElementById('searchFields').style.display = 'none';
        }
        
        // Инициализация при загрузке
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchFields').style.display = 'none';
        });
    </script>
	
</body>
</html>