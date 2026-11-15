<?php 
require_once 'config/db.php'; 
require_once 'includes/header.php'; 
require_once 'includes/nav.php'; 

$carrito = $_SESSION['carrito'] ?? [];
$totalGeneral = 0;
?>

<main>
    <?php if (isset($_SESSION['flash_mensaje'])): ?>
        <div class="alerta alerta-<?= htmlspecialchars($_SESSION['flash_tipo'] ?? 'exito', ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($_SESSION['flash_mensaje'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']); ?>
    <?php endif; ?>

    <h1 style="margin-bottom: 25px;">Tu Carrito de Compras</h1>

    <?php if (empty($carrito)): ?>
        <div style="text-align: center; padding: 50px 20px; border: 2px dashed #ccc; border-radius: 15px;">
            <p style="font-size: 1.2rem; margin-bottom: 20px;">Tu carrito está vacío por el momento.</p>
            <a href="productos.php" class="btn-principal">Ir a ver productos</a>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #ff9f1c;">
                        <th style="padding: 12px;">Producto</th>
                        <th style="padding: 12px;">Precio Unit.</th>
                        <th style="padding: 12px;">Cantidad</th>
                        <th style="padding: 12px;">Subtotal</th>
                        <th style="padding: 12px;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrito as $prod): 
                        $subtotal = $prod['precio'] * $prod['cantidad'];
                        $totalGeneral += $subtotal;
                    ?>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 12px; font-weight: 700;"><?= htmlspecialchars($prod['nombre']) ?></td>
                            <td style="padding: 12px;">$<?= number_format($prod['precio'], 0, ',', '.') ?></td>
                            <td style="padding: 12px;"><?= $prod['cantidad'] ?></td>
                            <td style="padding: 12px; font-weight: 800; color: #ff9f1c;">$<?= number_format($subtotal, 0, ',', '.') ?></td>
                            <td style="padding: 12px;">
                                <a href="carrito_accion.php?accion=eliminar&id=<?= $prod['id'] ?>" style="color: #e71d36; font-weight: 700; text-decoration: none;">Quitar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; padding: 20px; border-radius: 15px; background: rgba(255, 159, 28, 0.1);">
            <a href="carrito_accion.php?accion=vaciar" style="color: #e71d36; font-weight: 700; text-decoration: none;">Vaciar carrito</a>
            
            <div style="text-align: right;">
                <p style="font-size: 1.4rem; font-weight: 800; margin-bottom: 15px;">
                    Total a pagar: <span style="color: #ff9f1c;">$<?= number_format($totalGeneral, 0, ',', '.') ?></span>
                </p>

                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <form action="procesar_pedido.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <button type="submit" class="btn-principal" style="border: none; cursor: pointer;">Confirmar Pedido</button>
                    </form>
                <?php else: ?>
                    <p style="color: #e71d36; font-weight: 700; margin-bottom: 10px;">Debes iniciar sesión para confirmar tu pedido.</p>
                    <button type="button" class="btn-auth" onclick="toggleAuthPanel()">Iniciar sesión / Registrar</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php require_once 'includes/footer.php'; ?>