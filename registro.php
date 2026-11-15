<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}


$tokenRecibido = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'] ?? '', $tokenRecibido)) {
    $_SESSION['flash_mensaje'] = "Error de seguridad (Token inválido). Intenta nuevamente.";
    $_SESSION['flash_tipo'] = "error";
    header("Location: index.php");
    exit;
}


$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    $_SESSION['flash_mensaje'] = "Ingresa un correo válido y una contraseña de al menos 6 caracteres.";
    $_SESSION['flash_tipo'] = "error";
    header("Location: index.php");
    exit;
}


$sqlCheck = "SELECT id_usuario FROM usuarios WHERE email = ?";
$stmtCheck = mysqli_prepare($conexion, $sqlCheck);
mysqli_stmt_bind_param($stmtCheck, "s", $email);
mysqli_stmt_execute($stmtCheck);
mysqli_stmt_store_result($stmtCheck);

if (mysqli_stmt_num_rows($stmtCheck) > 0) {
    mysqli_stmt_close($stmtCheck);
    $_SESSION['flash_mensaje'] = "Ese correo ya está registrado. Inicia sesión.";
    $_SESSION['flash_tipo'] = "error";
    header("Location: index.php");
    exit;
}
mysqli_stmt_close($stmtCheck);


$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$rol = 'cliente';

$sqlInsert = "INSERT INTO usuarios (email, password_hash, rol) VALUES (?, ?, ?)";
$stmtInsert = mysqli_prepare($conexion, $sqlInsert);
mysqli_stmt_bind_param($stmtInsert, "sss", $email, $passwordHash, $rol);

if (mysqli_stmt_execute($stmtInsert)) {
    $_SESSION['flash_mensaje'] = "¡Cuenta creada con éxito! Ya puedes iniciar sesión.";
    $_SESSION['flash_tipo'] = "exito";
} else {
    $_SESSION['flash_mensaje'] = "Ocurrió un error al registrar la cuenta.";
    $_SESSION['flash_tipo'] = "error";
}

mysqli_stmt_close($stmtInsert);
header("Location: index.php");
exit;