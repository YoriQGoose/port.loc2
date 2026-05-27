<?php
// Функции для проверки разрешений

function canView() {
    return in_array('view', $_SESSION['permissions'] ?? []);
}

function canAdd() {
    return in_array('add', $_SESSION['permissions'] ?? []);
}

function canEdit() {
    return in_array('edit', $_SESSION['permissions'] ?? []);
}

function canDelete() {
    return in_array('delete', $_SESSION['permissions'] ?? []);
}

function canManageUsers() {
    return in_array('manage_users', $_SESSION['permissions'] ?? []);
}

// Проверка разрешений с выводом сообщения
function requirePermission($permission) {
    if (!in_array($permission, $_SESSION['permissions'] ?? [])) {
        $_SESSION['access_error'] = "У вас нет прав для выполнения этого действия";
        header("Location: index.php");
        exit();
    }
}

// Получить доступные таблицы для текущего пользователя
function getAllowedTables() {
    $tables = ['employees', 'cargoes', 'vessels', 'operations', 'customers'];
    $allowed = [];
    
    foreach ($tables as $table) {
        if (canView()) {
            $allowed[] = $table;
        }
    }
    
    return $allowed;
}
?>