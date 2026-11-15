<?php 
require_once 'config/db.php'; 
require_once 'includes/header.php'; 
require_once 'includes/nav.php'; 


$sqlDestacados = "SELECT id_producto, nombre, precio, stock, especie, imagen FROM productos WHERE activo = 1 LIMIT 4";
$resultadoDestacados = mysqli_query($conexion, $sqlDestacados);
?>

<main>
<?php if (isset($_SESSION['flash_mensaje'])): ?>
    <div class="alerta alerta-<?= htmlspecialchars($_SESSION['flash_tipo'] ?? 'exito', ENT_QUOTES, 'UTF-8') ?>" style="max-width: 1200px; margin: 20px auto 0;">
        <?= htmlspecialchars($_SESSION['flash_mensaje'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_mensaje'], $_SESSION['flash_tipo']); ?>
<?php endif; ?>


<section class="hero-full" style="background-image: linear-gradient(rgba(0,0,0,0.40), rgba(0,0,0,0.50)), url('img/banner-principal.jpg');">
    <div class="hero-full-contenido">
        <h1>Todo el amor y cuidado para tu mejor amigo</h1>
        <p>En <strong>Mascotopía</strong> encuentras alimentos premium, accesorios y juguetes para perros, gatos y mascotas exóticas.</p>
        <a href="productos.php" class="btn-principal">Explorar Catálogo Completo</a>
    </div>
</section>

<main>
   
    <section class="categorias-visuales">
        <a href="productos.php?especie=Perro" class="banner-cat bg-perro" style="background-image: linear-gradient(to top, rgba(0,0,0,0.60) 0%, rgba(0,0,0,0.05) 50%), url('img/card-perros.jpg');">
            <div class="overlay-cat">
                <span class="subtitulo-cat">TODO PARA</span>
                <h3>PERROS</h3>
            </div>
        </a>

        <a href="productos.php?especie=Gato" class="banner-cat bg-gato" style="background-image: linear-gradient(to top, rgba(0,0,0,0.60) 0%, rgba(0,0,0,0.05) 50%), url('img/card-gatos.jpg');">
            <div class="overlay-cat">
                <span class="subtitulo-cat">TODO PARA</span>
                <h3>GATOS</h3>
            </div>
        </a>

        <a href="productos.php?grupo=exoticos" class="banner-cat bg-exotico" style="background-image: linear-gradient(to top, rgba(0,0,0,0.60) 0%, rgba(0,0,0,0.05) 50%), url('img/card-exoticos.jpg');">
            <div class="overlay-cat">
                <span class="subtitulo-cat">TODO PARA</span>
                <h3>EXÓTICOS</h3>
            </div>
        </a>
    </section>
    
   
    <section class="seccion-destacados">
    <h2 class="titulo-seccion">Productos Destacados</h2>
    <div class="catalogo">
    <?php while ($item = mysqli_fetch_assoc($resultadoDestacados)): ?>
        <div class="tarjeta-producto">
        <img src="img/<?= htmlspecialchars($item['imagen'], ENT_QUOTES, 'UTF-8') ?>" 
        onerror="this.onerror=null; this.src='img/default.jpg';" 
        alt="<?= htmlspecialchars($item['nombre'], ENT_QUOTES, 'UTF-8') ?>" 
        class="img-producto">
        <span class="etiqueta-especie"><?= htmlspecialchars($item["especie"], ENT_QUOTES, 'UTF-8') ?></span>
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
                </div>
                </section>
                </main>
                
                <?php require_once 'includes/footer.php'; ?>