<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Интерактивная форма</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
        }
        h1 { color: #333; margin-bottom: 30px; text-align: center; }
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        .tab {
            padding: 12px 25px;
            background: #f8f9fa;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .tab.active {
            background: #4facfe;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .form-group {
            margin: 20px 0;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 16px;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79,172,254,0.3);
        }
        .result-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-top: 30px;
        }
        .url-display {
            background: #2d2d2d;
            color: #00ff00;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            word-break: break-all;
        }
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        .info-box {
            background: #e9f7ef;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
    </style>
    <script>
        function showTab(tabId) {
            // Скрыть все вкладки
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Убрать активный класс у всех кнопок
            document.querySelectorAll('.tab').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Показать выбранную вкладку
            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }
        
        function updateUrlPreview() {
            const form = document.getElementById('searchForm');
            const params = new URLSearchParams(new FormData(form)).toString();
            document.getElementById('urlPreview').textContent = 
                window.location.origin + window.location.pathname + '?' + params;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Обновляем превью URL при изменении формы
            document.querySelectorAll('#searchForm input').forEach(input => {
                input.addEventListener('input', updateUrlPreview);
            });
            
            // Показываем текущие параметры в URL
            updateUrlPreview();
            
            // Показываем первую вкладку
            showTab('search');
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>🔍 Интерактивная форма с GET-параметрами</h1>
        
        <div class="tabs">
            <button class="tab active" onclick="showTab('search')">🔎 Поиск</button>
            <button class="tab" onclick="showTab('filter')">🎯 Фильтр</button>
            <button class="tab" onclick="showTab('pagination')">📄 Пагинация</button>
        </div>
        
        <!-- Вкладка поиска -->
        <div id="search" class="tab-content active">
            <div class="info-box">
                <h3>Форма поиска с GET-параметрами</h3>
                <p>Эта форма демонстрирует, как передавать данные поиска через URL.</p>
            </div>
            
            <form id="searchForm" method="GET" action="">
                <div class="form-group">
                    <label for="search_query">Поисковый запрос:</label>
                    <input type="text" id="search_query" name="search" 
                           placeholder="Введите поисковый запрос"
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Категория:</label>
                        <select id="category" name="category">
                            <option value="">Все категории</option>
                            <option value="programming" <?php echo isset($_GET['category']) && $_GET['category'] == 'programming' ? 'selected' : ''; ?>>Программирование</option>
                            <option value="design" <?php echo isset($_GET['category']) && $_GET['category'] == 'design' ? 'selected' : ''; ?>>Дизайн</option>
                            <option value="marketing" <?php echo isset($_GET['category']) && $_GET['category'] == 'marketing' ? 'selected' : ''; ?>>Маркетинг</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="sort_by">Сортировать по:</label>
                        <select id="sort_by" name="sort">
                            <option value="relevance" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'relevance' ? 'selected' : ''; ?>>Релевантности</option>
                            <option value="date" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'date' ? 'selected' : ''; ?>>Дате</option>
                            <option value="price" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price' ? 'selected' : ''; ?>>Цене</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn" style="width: 100%;">Выполнить поиск</button>
            </form>
        </div>
        
        <!-- Вкладка фильтра -->
        <div id="filter" class="tab-content">
            <div class="info-box">
                <h3>Фильтрация товаров</h3>
                <p>Пример фильтрации товаров с использованием GET-параметров.</p>
            </div>
            
            <form method="GET" action="">
                <input type="hidden" name="tab" value="filter">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="min_price">Минимальная цена:</label>
                        <input type="number" id="min_price" name="min_price" 
                               placeholder="0"
                               value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="max_price">Максимальная цена:</label>
                        <input type="number" id="max_price" name="max_price" 
                               placeholder="100000"
                               value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Бренд:</label>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 10px;">
                        <label><input type="checkbox" name="brand[]" value="apple" <?php echo isset($_GET['brand']) && in_array('apple', (array)$_GET['brand']) ? 'checked' : ''; ?>> Apple</label>
                        <label><input type="checkbox" name="brand[]" value="samsung" <?php echo isset($_GET['brand']) && in_array('samsung', (array)$_GET['brand']) ? 'checked' : ''; ?>> Samsung</label>
                        <label><input type="checkbox" name="brand[]" value="xiaomi" <?php echo isset($_GET['brand']) && in_array('xiaomi', (array)$_GET['brand']) ? 'checked' : ''; ?>> Xiaomi</label>
                        <label><input type="checkbox" name="brand[]" value="sony" <?php echo isset($_GET['brand']) && in_array('sony', (array)$_GET['brand']) ? 'checked' : ''; ?>> Sony</label>
                    </div>
                </div>
                
                <button type="submit" class="btn" style="width: 100%;">Применить фильтры</button>
            </form>
        </div>
        
        <!-- Вкладка пагинации -->
        <div id="pagination" class="tab-content">
            <div class="info-box">
                <h3>Пагинация с GET-параметрами</h3>
                <p>Демонстрация передачи параметров пагинации через URL.</p>
            </div>
            
            <form method="GET" action="">
                <input type="hidden" name="tab" value="pagination">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="page">Страница:</label>
                        <input type="number" id="page" name="page" 
                               placeholder="1" min="1"
                               value="<?php echo isset($_GET['page']) ? htmlspecialchars($_GET['page']) : '1'; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="limit">Элементов на странице:</label>
                        <select id="limit" name="limit">
                            <option value="10" <?php echo isset($_GET['limit']) && $_GET['limit'] == '10' ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?php echo isset($_GET['limit']) && $_GET['limit'] == '25' ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?php echo isset($_GET['limit']) && $_GET['limit'] == '50' ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?php echo isset($_GET['limit']) && $_GET['limit'] == '100' ? 'selected' : ''; ?>>100</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="order">Порядок сортировки:</label>
                    <select id="order" name="order">
                        <option value="asc" <?php echo isset($_GET['order']) && $_GET['order'] == 'asc' ? 'selected' : ''; ?>>По возрастанию</option>
                        <option value="desc" <?php echo isset($_GET['order']) && $_GET['order'] == 'desc' ? 'selected' : ''; ?>>По убыванию</option>
                    </select>
                </div>
                
                <button type="submit" class="btn" style="width: 100%;">Перейти на страницу</button>
            </form>
        </div>
        
        <!-- Результаты -->
        <div class="result-section">
            <h3>📊 Результаты передачи параметров:</h3>
            
            <p><strong>Сгенерированный URL:</strong></p>
            <div class="url-display" id="urlPreview">
                <?php
                $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                echo $current_url;
                ?>
            </div>
            
            <?php if (!empty($_GET)): ?>
                <p><strong>Полученные GET-параметры:</strong></p>
                <div class="info-box">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f1f3f4;">
                                <th style="padding: 10px; text-align: left;">Параметр</th>
                                <th style="padding: 10px; text-align: left;">Значение</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_GET as $key => $value): ?>
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 10px;"><code><?php echo htmlspecialchars($key); ?></code></td>
                                    <td style="padding: 10px;">
                                        <?php 
                                        if (is_array($value)) {
                                            echo htmlspecialchars(implode(', ', $value));
                                        } else {
                                            echo htmlspecialchars($value);
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <p><strong>Код PHP для получения параметров:</strong></p>
                <div class="url-display" style="background: #f1f3f4; color: #333;">
                    <?php foreach ($_GET as $key => $value): ?>
                        $<?php echo htmlspecialchars($key); ?> = $_GET['<?php echo htmlspecialchars($key); ?>'];<br>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>⏳ Параметры не переданы. Заполните форму выше.</p>
            <?php endif; ?>
        </div>
        
        <div class="back-link">
            <a href="lab9_index.php" class="btn">← Назад к лабораторной работе</a>
        </div>
    </div>
</body>
</html>