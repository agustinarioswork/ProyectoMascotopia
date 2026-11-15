<nav>
<ul class="menu">
<li><a href="index.php">INICIO</a></li>
<li><a href="productos.php">CATÁLOGO</a></li>

<li><a href="productos.php?especie=Perro">PERRO</a>
<ul class="submenu">
<li><a href="productos.php?especie=Perro&categoria=Alimentos">Alimentos</a></li>
<li><a href="productos.php?especie=Perro&categoria=Accesorios">Accesorios</a></li>
<li><a href="productos.php?especie=Perro&categoria=Juguetes">Juguetes</a></li>
</ul>
</li>

<li><a href="productos.php?especie=Gato">GATO</a>
<ul class="submenu">
<li><a href="productos.php?especie=Gato&categoria=Alimentos">Alimentos</a></li>
<li><a href="productos.php?especie=Gato&categoria=Accesorios">Accesorios</a></li>
<li><a href="productos.php?especie=Gato&categoria=Juguetes">Juguetes</a></li>
</ul>
</li>

<li><a href="#">EXÓTICO</a>
<ul class="submenu">
<li class="has-submenu-2"><a href="productos.php?especie=Peces">Peces &raquo;</a>
<ul class="submenu-2">
<li><a href="productos.php?especie=Peces&categoria=Alimentos">Alimentos</a></li>
<li><a href="productos.php?especie=Peces&categoria=Juguetes">Juguetes</a></li>
<li><a href="productos.php?especie=Peces&categoria=Jaulas">Jaulas</a></li>
</ul>
</li>
<li class="has-submenu-2"><a href="productos.php?especie=Aves">Aves &raquo;</a>
<ul class="submenu-2">
<li><a href="productos.php?especie=Aves&categoria=Alimentos">Alimentos</a></li>
<li><a href="productos.php?especie=Aves&categoria=Juguetes">Juguetes</a></li>
<li><a href="productos.php?especie=Aves&categoria=Jaulas">Jaulas</a></li>
</ul>
</li>
<li class="has-submenu-2"><a href="productos.php?especie=Conejos">Conejos &raquo;</a>
<ul class="submenu-2">
<li><a href="productos.php?especie=Conejos&categoria=Alimentos">Alimentos</a></li>
<li><a href="productos.php?especie=Conejos&categoria=Juguetes">Juguetes</a></li>
<li><a href="productos.php?especie=Conejos&categoria=Jaulas">Jaulas</a></li>
</ul>
</li>
</ul>
</li>

<!-- <li><a href="contacto.php">CONTACTO</a></li> Para el final--> 

<?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
    <li><a href="admin/crud.php" style="color: #ffd166;">PANEL ADMIN</a></li>
    <?php endif; ?>
    </ul>
    </nav>
    
    <div class="controles-derecha">
    <div class="selector-tema">
    <?php if ($temaActivo === 'claro'): ?>
        <a href="cambiar_tema.php?tema=oscuro" class="btn-tema" title="Activar Modo Oscuro">🌙</a>
        <?php else: ?>
            <a href="cambiar_tema.php?tema=claro" class="btn-tema" title="Activar Modo Claro">☀️</a>
            <?php endif; ?>
            </div>
            <?php 
            $totalItemsCarrito = 0;
            if (!empty($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $c) {
            $totalItemsCarrito += $c['cantidad'];
            }
            }
            ?>
            <a href="carrito.php" class="btn-tema" title="Ver Carrito" style="font-size: 1rem; border-radius: 20px; padding: 6px 12px; color: white; font-weight: 800;">
            🛒 <?= $totalItemsCarrito ?>
            </a>
            
            <div class="auth-container">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <span class="saludo-usuario">Hola, <?= htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8') ?></span>
                <a href="logout.php" class="btn-auth btn-salir">Salir</a>
                <?php else: ?>
                    <button type="button" class="btn-auth" onclick="toggleAuthPanel()">Iniciar sesión / Registrar</button>
                    
                    <div id="auth-panel" class="auth-panel oculto">
                    <div class="caja-formulario">
                    <h3>Iniciar Sesión</h3>
                    <form action="login.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="text" name="usuario" placeholder="Correo electrónico" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <button type="submit" class="btn-submit">Ingresar</button>
                    </form>
                    </div>
                    
                    <div class="separador"></div>
                    
                    <div class="caja-formulario">
                    <h3>¡Crear Cuenta!</h3>
                    <form action="registro.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="email" name="email" placeholder="Correo electrónico" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <button type="submit" class="btn-submit">Registrar</button>
                    </form>
                    </div>
                    </div>
                    <?php endif; ?>
                    </div>
                    </div>
                    </header>