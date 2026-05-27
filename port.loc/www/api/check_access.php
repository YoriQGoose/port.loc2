<?php
session_start();

// Если пользователь не авторизован, перенаправляем на страницу входа
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Определяем разрешения для каждой роли
$role_permissions = [
    'admin' => ['view', 'add', 'edit', 'delete', 'manage_users'],
    'operator' => ['view', 'add', 'edit'],
    'viewer' => ['view']
];

// Инициализируем разрешения пользователя
if (isset($_SESSION['user_role']) && !isset($_SESSION['permissions'])) {
    $role = $_SESSION['user_role'];
    $_SESSION['permissions'] = $role_permissions[$role] ?? ['view'];
}

// Функция для проверки прав доступа
function checkAccess($required_role) {
    $user_role = $_SESSION['user_role'] ?? 'guest';
    $role_hierarchy = [
        'viewer' => 1,
        'operator' => 2,
        'admin' => 3
    ];
    
    $user_level = $role_hierarchy[$user_role] ?? 0;
    $required_level = $role_hierarchy[$required_role] ?? 0;
    
    return $user_level >= $required_level;
}

// Функция для проверки и перенаправления при недостаточных правах
function requireAccess($required_role) {
    if (!checkAccess($required_role)) {
        $_SESSION['access_error'] = "У вас недостаточно прав для доступа к этой странице";
        header("Location: index.php");
        exit();
    }
}
?>