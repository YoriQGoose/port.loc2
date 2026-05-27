<?php
session_start();

// Обновляем время последней активности
$_SESSION['last_activity'] = time();

echo json_encode([
    'success' => true,
    'message' => 'Сессия обновлена',
    'timestamp' => time(),
    'session_id' => session_id()
]);
?>