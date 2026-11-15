<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';
$idProducto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : (int)($_GET['id'] ?? 0);


if ($accion === 'agregar' && $idProducto > 0) {

    $sql = "SELECT id_producto, nombre, precio, stock FROM productos WHERE id_producto = ? AND activo = 1";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idProducto);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $producto = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($producto && $producto['stock'] > 0) {
        $cantidadActual = $_SESSION['carrito'][$idProducto]['cantidad'] ?? 0;
        
        if ($cantidadActual + 1 <= $producto['stock']) {
            $_SESSION['carrito'][$idProducto] = [
                'id'       => $producto['id_producto'],
                'nombre'   => $producto['nombre'],
                'precio'   => (float)$producto['precio'],
                'cantidad' => $cantidadActual + 1
            ];
            $_SESSION['flash_mensaje'] = "Agregaste '{$producto['nombre']}' al carrito.";
            $_SESSION['flash_tipo'] = "exito";
        } else {
            $_SESSION['flash_mensaje'] = "No hay más stock disponible de '{$producto['nombre']}'.";
            $_SESSION['flash_tipo'] = "error";
        }
    }
    $volver = $_SERVER['HTTP_REFERER'] ?? 'productos.php';
    header("Location: $volver");
    exit;
}


if ($accion === 'eliminar' && $idProducto > 0) {
    unset($_SESSION['carrito'][$idProducto]);
    header("Location: carrito.php");
    exit;
}


if ($accion === 'vaciar') {
    $_SESSION['carrito'] = [];
    header("Location: carrito.php");
    exit;
}

header("Location: index.php");
exit;