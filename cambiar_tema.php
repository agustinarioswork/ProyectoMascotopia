<?php
$tema = $_GET['tema'] ?? 'claro';

if (in_array($tema, ['claro', 'oscuro'], true)) {
    setcookie('tema_mascotopia', $tema, [
        'expires' => time() + (30 * 24 * 60 * 60), // Dura 30 días
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
}

// Volver a la página anterior
$paginaAnterior = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: " . $paginaAnterior);
exit;