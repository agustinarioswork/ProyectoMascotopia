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

$email = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['flash_mensaje'] = "Por favor completa todos los campos.";
    $_SESSION['flash_tipo'] = "error";
    header("Location: index.php");
    exit;
}


$sql = "SELECT id_usuario, email, password_hash, rol, activo FROM usuarios WHERE email = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);


if ($usuario && $usuario['activo'] == 1 && password_verify($password, $usuario['password_hash'])) {
    session_regenerate_id(true);
    
    $_SESSION['usuario_id'] = $usuario['id_usuario'];
    $_SESSION['email'] = $usuario['email'];
    $_SESSION['rol'] = $usuario['rol'];
    
    $_SESSION['flash_mensaje'] = "¡Bienvenido de nuevo a Mascotopía!";
    $_SESSION['flash_tipo'] = "exito";
} else {
    $_SESSION['flash_mensaje'] = "Correo o contraseña incorrectos.";
    $_SESSION['flash_tipo'] = "error";
}

header("Location: index.php");
exit;