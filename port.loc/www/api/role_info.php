<?php
session_start();
require_once 'check_access.php';
require_once 'permissions.php';

// Определяем роли и их разрешения
$roles = [
    'admin' => [
        'name' => 'Администратор',
        'description' => 'Полный доступ ко всем функциям системы',
        'permissions' => ['view', 'add', 'edit', 'delete', 'manage_users']
    ],
    'operator' => [
        'name' => 'Оператор',
        'description' => 'Может добавлять и редактировать данные, но не удалять',
        'permissions' => ['view', 'add', 'edit']
    ],
    'viewer' => [
        'name' => 'Наблюдатель',
        'description' => 'Только просмотр данных без возможности изменений',
        'permissions' => ['view']
    ]
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Информация о ролях</title>
    <style>
        /* Стили из оригинального файла role_info.php */
        .role-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .role-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .role-card.admin {
            border-top: 5px solid #dc3545;
        }
        
        .role-card.operator {
            border-top: 5px solid #fd7e14;
        }
        
        .role-card.viewer {
            border-top: 5px solid #6c757d;
        }
        
        .role-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .role-title {
            font-size: 20px;
            font-weight: bold;
        }
        
        .role-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        
        .badge-admin { background: #dc3545; }
        .badge-operator { background: #fd7e14; }
        .badge-viewer { background: #6c757d; }
        
        .permission-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .permission-item.has-permission {
            background: #d4edda;
            color: #155724;
        }
        
        .permission-icon {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .has-permission .permission-icon {
            color: #28a745;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .permission-info {
            background: #e9f7ef;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👥 Управление ролями и правами доступа</h1>
        
        <div class="permission-info">
            <p>Текущий пользователь: <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Пользователь'); ?></strong> 
               (Роль: <span class="role-badge badge-<?php echo $_SESSION['user_role'] ?? 'viewer'; ?>">
               <?php echo $_SESSION['user_role'] ?? 'не определена'; ?></span>)</p>
        </div>
        
        <div class="role-container">
            <?php foreach ($roles as $roleKey => $role): ?>
                <div class="role-card <?php echo $roleKey; ?>">
                    <div class="role-header">
                        <div class="role-title"><?php echo $role['name']; ?></div>
                        <span class="role-badge badge-<?php echo $roleKey; ?>">
                            <?php echo $roleKey; ?>
                        </span>
                    </div>
                    
                    <p><?php echo $role['description']; ?></p>
                    
                    <h4>Разрешения:</h4>
                    <?php 
                    $allPermissions = [
                        'view' => '👁️ Просмотр данных',
                        'add' => '➕ Добавление данных', 
                        'edit' => '✏️ Редактирование данных',
                        'delete' => '🗑️ Удаление данных',
                        'manage_users' => '👥 Управление пользователями'
                    ];
                    ?>
                    
                    <?php foreach ($allPermissions as $permKey => $permName): ?>
                        <div class="permission-item <?php echo in_array($permKey, $role['permissions']) ? 'has-permission' : ''; ?>">
                            <span class="permission-icon">
                                <?php echo in_array($permKey, $role['permissions']) ? '✓' : '✗'; ?>
                            </span>
                            <?php echo $permName; ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if ($roleKey === ($_SESSION['user_role'] ?? '')): ?>
                        <div style="margin-top: 15px; padding: 10px; background: #fff3cd; border-radius: 5px;">
                            ⭐ Это ваша текущая роль
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="index.php" class="button">← Назад на главную</a>
        </div>
    </div>
</body>
</html>