<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: carrito.php");
    exit;
}


if (!isset($_SESSION['usuario_id']) || empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['flash_mensaje'] = "Error de seguridad al procesar el pedido.";
    $_SESSION['flash_tipo'] = "error";
    header("Location: carrito.php");
    exit;
}

$idUsuario = (int)$_SESSION['usuario_id'];
$carrito = $_SESSION['carrito'];
$totalPedido = 0;

foreach ($carrito as $item) {
    $totalPedido += $item['precio'] * $item['cantidad'];
}


mysqli_begin_transaction($conexion);

try {
    
    $sqlPedido = "INSERT INTO pedidos (id_usuario, total) VALUES (?, ?)";
    $stmtPedido = mysqli_prepare($conexion, $sqlPedido);
    mysqli_stmt_bind_param($stmtPedido, "id", $idUsuario, $totalPedido);
    mysqli_stmt_execute($stmtPedido);
    $idPedidoGenerado = mysqli_insert_id($conexion);
    mysqli_stmt_close($stmtPedido);

   
    $sqlDetalle = "INSERT INTO detalle_pedidos (id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
    $stmtDetalle = mysqli_prepare($conexion, $sqlDetalle);

    $sqlStock = "UPDATE productos SET stock = stock - ? WHERE id_producto = ? AND stock >= ?";
    $stmtStock = mysqli_prepare($conexion, $sqlStock);

    foreach ($carrito as $item) {
        $idProd = (int)$item['id'];
        $cant = (int)$item['cantidad'];
        $precio = (float)$item['precio'];

        mysqli_stmt_bind_param($stmtDetalle, "iiid", $idPedidoGenerado, $idProd, $cant, $precio);
        mysqli_stmt_execute($stmtDetalle);

        mysqli_stmt_bind_param($stmtStock, "iii", $cant, $idProd, $cant);
        mysqli_stmt_execute($stmtStock);
    }

    mysqli_stmt_close($stmtDetalle);
    mysqli_stmt_close($stmtStock);

    mysqli_commit($conexion);

    
    $_SESSION['carrito'] = [];
    $_SESSION['flash_mensaje'] = "¡Pedido #$idPedidoGenerado registrado con éxito en Mascotopía! Gracias por tu compra.";
    $_SESSION['flash_tipo'] = "exito";
    header("Location: index.php");
    exit;

} catch (Exception $e) {
    mysqli_rollback($conexion);
    $_SESSION['flash_mensaje'] = "Hubo un problema al registrar tu pedido. Intenta nuevamente.";
    $_SESSION['flash_tipo'] = "error";
    header("Location: carrito.php");
    exit;
}