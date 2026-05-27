<?php
session_start();

// Сохраняем ID сессии для логов
$session_id = session_id();

// Полностью уничтожаем сессию
session_unset();
session_destroy();
session_write_close();

// Удаляем куку сессии
setcookie(session_name(), '', time() - 3600, '/');

echo json_encode([
    'success' => true,
    'message' => 'Сессия завершена',
    'session_id' => $session_id
]);
?>