<?php 
require_once "..\..\backend/administrador/interfaz_usuario.php"
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Metadocs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../../../componentes/img/logopng.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../componentes/css/admin/panel.css">
    <link rel="stylesheet" href="../../../componentes/css/admin/control.css">
    <script src="../../../componentes/js/admin/panel.js"></script>
    <link rel="stylesheet" href="../../../componentes/css/admin/informacion_usuario.css">
</head>
<body>
    <header id="cabezote">
        <i class="bi bi-list" id="menu_opciones"></i>
    </header>

    <main id="cuerpo">
         <nav id="menu-lateral" class="menu-lateral">
    <figure id="img_menu">
        <img src="../../../componentes/img/image.png" alt="imagen del menu lateral">
    </figure>
    <ul>
        <!-- Opciones principales del menú -->
        <div class="menu-opciones-principales">
            <li>
                <a href="../admin/panel_control.php">
                    <i class="bi bi-bar-chart-line"></i>
                    Panel Control
                </a>
            </li>

            <li class="gestion_usuario">
                <a href="#" id="gestion-usuarios" class="activo"><i class="bi bi-people"></i> Gestión Usuarios</a>
                <ul class="sub_menu gestion-submenu" id="sub_menu">
                    <li><a href="../../vistas/admin/creacion_usuario.php"><i class="bi bi-person-plus"></i> Crear usuario</a></li>
                    <li><a href="../admin/ver_usuarios.php" class="submenu-activo"><i class="bi bi-eye"></i> Ver usuario</a></li>
                </ul>
            </li>
            
            <li><a href="../admin/admin_reporte.php"><i class="bi bi-file-earmark-text"></i> Reportes</a></li>
            
            <li class="gestion-usuarios">
                <a href="#" id="cerrado-usuarios"><i class="bi bi-person"></i> Admin</a>
                <ul class="sub_menu usuario-submenu" id="sub_menu">
                    <li><a href="../log/informacion_usuario.php"><i class="bi bi-info-circle"></i> Info usuario</a></li>
                    <li><a href=""><i class="bi bi-key-fill"></i> Cambiar contraseña</a></li>
                </ul>
            </li>
            
            <li class="solo_mobil">
                <a href="#" id="solo_mobil"><i class="bi bi-arrow-left"></i> Volver</a>
            </li>
        </div>

        <!-- Botón cerrar sesión separado -->
        <li class="cerrar-sesion-separado">
            <a href="#" id="cerrar_sesion"><i class="bi bi-box-arrow-left"></i>Cerrar sesión</a>
        </li>
    </ul>
</nav>
            <section class="contenido-usuario">
                <h1 class="titulo-usaurio">Informacion del Usuario</h1>
                <div class="contenedor-general">
                    <div class="info-usuarios">
                        <img src="../../../componentes/img/usuario.png" alt="logo de usuario" class="avatar-usuario">
                        <div class="nombre-usuario"><?=htmlspecialchars($fila["nombres"])?></div>
                    </div>

                    <div class="contenedor-datos"> 
                        <div class="datos">
                            <label>Descripción laboral</label>
                            <div class="valor"><?=htmlspecialchars($mensaje)?></div>
                        </div>

                        <div class="datos">
                            <label>Nombre</label>
                            <div class="valor"><?= htmlspecialchars($fila["nombres"]) ?></div>  
                        </div>

                        <div class="datos">
                            <label>Apellido</label>
                            <div class="valor"><?= htmlspecialchars($fila["apellidos"]) ?></div>
                        </div>

                        <div class="datos">
                            <label>Correo Electrónico</label>
                            <div class="valor"><?= htmlspecialchars($fila["correo"]) ?></div>
                        </div>

                        <div class="datos">
                            <label>Número telefónico</label>
                            <div class="valor"><?= htmlspecialchars($fila["telefono"]) ?></div>
                        </div>

                        <div class="datos">
                            <label>Cédula</label>
                            <div class="valor"><?= htmlspecialchars($fila["cedula"]) ?></div>
                        </div>

                        <div class="datos">
                            <label>Área</label>
                            <div class="valor"><?= htmlspecialchars($fila["area"]) ?></div>
                        </div>

                        <div class="datos">
                            <label>Rol</label>
                            <div class="valor"><?= htmlspecialchars($fila["rol"]) ?></div>
                        </div>
                    </div> <!-- <- Cierre correcto -->
                </div>

            </section>
    </main>
    <?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>
</body>
</html>
