<?php 
require_once 'config/db.php'; 
require_once 'includes/header.php'; 
require_once 'includes/nav.php'; 

$especie = $_GET['especie'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$grupo = $_GET['grupo'] ?? '';

$tituloPagina = "Catálogo Completo de Productos";
$bannerInfo = null;

if ($especie === 'Perro') {
$bannerInfo = [
'imagen' => 'img/banner-perros.jpg',
'titulo' => 'Todo para tu peludo',
'color'  => '#e61c66',
'links'  => ['Alimentos', 'Accesorios', 'Juguetes'],
'param'  => 'especie=Perro'
];
} elseif ($especie === 'Gato') {
$bannerInfo = [
'imagen' => 'img/banner-gatos.jpg',
'titulo' => 'Todo para tu michi',
'color'  => '#e5a900',
'links'  => ['Alimentos', 'Accesorios', 'Juguetes'],
'param'  => 'especie=Gato'
];
} elseif ($grupo === 'exoticos' || in_array($especie, ['Peces', 'Aves', 'Conejos'])) {
$bannerInfo = [
'imagen' => 'img/banner-exoticos.jpg',
'titulo' => 'Todo para tu exótico',
'color'  => '#2ec4b6',
'links'  => ['Alimentos', 'Juguetes', 'Jaulas'],
'param'  => !empty($especie) ? "especie=$especie" : 'grupo=exoticos'
];
}

if ($grupo === 'exoticos' && !empty($categoria)) {
$sql = "SELECT * FROM productos WHERE activo = 1 AND especie IN ('Peces', 'Aves', 'Conejos') AND categoria = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $categoria);
$tituloPagina = "Exóticos - " . htmlspecialchars($categoria);
} elseif ($grupo === 'exoticos') {
$sql = "SELECT * FROM productos WHERE activo = 1 AND especie IN ('Peces', 'Aves', 'Conejos')";
$stmt = mysqli_prepare($conexion, $sql);
$tituloPagina = "Productos para Mascotas Exóticas";
} elseif (!empty($especie) && !empty($categoria)) {
$sql = "SELECT * FROM productos WHERE activo = 1 AND especie = ? AND categoria = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ss", $especie, $categoria);
$tituloPagina = htmlspecialchars($especie) . " - " . htmlspecialchars($categoria);
} elseif (!empty($especie)) {
$sql = "SELECT * FROM productos WHERE activo = 1 AND especie = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $especie);
$tituloPagina = "Productos para " . htmlspecialchars($especie);
} else {
$sql = "SELECT * FROM productos WHERE activo = 1";
$stmt = mysqli_prepare($conexion, $sql);
}

mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>

<?php if ($bannerInfo): ?>
    <section class="hero-categoria" style="background-color: <?= $bannerInfo['color'] ?>; background-image: linear-gradient(to top, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.15) 60%), url('<?= $bannerInfo['imagen'] ?>');">
    <div class="hero-categoria-contenido">
    <h1><?= htmlspecialchars($bannerInfo['titulo']) ?></h1>
    <div class="subcategorias-banner">
    <?php foreach ($bannerInfo['links'] as $sub): ?>
        <a href="productos.php?<?= $bannerInfo['param'] ?>&categoria=<?= $sub ?>"><?= $sub ?></a>
        <?php endforeach; ?>
        </div>
        </div>
        </section>
        <?php endif; ?>
        
        <main>
        <?php if (isset($_SESSION['flash_mensaje'])): ?>
            <div class="alerta alerta-<?= htmlspecialchars($_SESSION['flash_tipo'] ?? 'exito', ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($_SESSION['flash_mensaje'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']); ?>
            <?php endif; ?>
            
            <div class="encabezado-catalogo">
            <h2><?= $tituloPagina ?></h2>
            <?php if (!empty($especie) || !empty($grupo)): ?>
                <a href="productos.php" class="btn-limpiar-filtro">Ver todos los productos</a>
                <?php endif; ?>
                </div>
                
                <section class="catalogo">
                <?php if (mysqli_num_rows($resultado) > 0): ?>
                    <?php while ($item = mysqli_fetch_assoc($resultado)): ?>
                        <div class="tarjeta-producto">
                        <img src="img/<?= htmlspecialchars($item['imagen'], ENT_QUOTES, 'UTF-8') ?>" 
                        onerror="this.onerror=null; this.src='img/default.jpg';" 
                        alt="<?= htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8') ?>" 
                        class="img-producto">
                        <span class="etiqueta-especie">
                        <?= htmlspecialchars($item["especie"], ENT_QUOTES, 'UTF-8') ?> • <?= htmlspecialchars($item["categoria"], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                        <h3><?= htmlspecialchars($item["nombre"], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="precio">$<?= number_format($item["precio"], 0, ',', '.') ?></p>
                        <p>
                        <?php if ($item["stock"] > 0): ?>
                            <span style="color: #2ec4b6; font-weight: bold;">En Stock (<?= $item["stock"] ?>)</span>
                            <?php else: ?>
                                <span style="color: #e71d36; font-weight: bold;">Agotado</span>
                                <?php endif; ?>
                                </p>
                                <form action="carrito_accion.php" method="POST">
                                <input type="hidden" name="accion" value="agregar">
                                <input type="hidden" name="id_producto" value="<?= $item['id_producto'] ?>">
                                <button type="submit" class="btn-carrito" <?= $item["stock"] == 0 ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : '' ?>>
                                <?= $item["stock"] > 0 ? 'Agregar al carrito' : 'Sin stock' ?>
                                </button>
                                </form>
                                </div>
                                <?php endwhile; ?>
                                <?php else: ?>
                                    <p style="text-align: center; width: 100%; padding: 40px;">No se encontraron productos en esta categoría por el momento.</p>
                                    <?php endif; ?>
                                    </section>
                                    </main>
                                    
                                    <?php 
                                    mysqli_stmt_close($stmt);
                                    require_once 'includes/footer.php'; 
                                    ?>