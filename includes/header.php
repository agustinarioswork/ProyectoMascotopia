<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$temaActivo = $_COOKIE['tema_mascotopia'] ?? 'claro';
if (!in_array($temaActivo, ['claro', 'oscuro'], true)) {
    $temaActivo = 'claro';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotopía</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?= htmlspecialchars($temaActivo, ENT_QUOTES, 'UTF-8') ?>">
    <header>
        <div class="logo">
            <h2>Mascotopía</h2>
        </div>