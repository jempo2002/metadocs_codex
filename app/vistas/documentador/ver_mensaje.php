<?php 
require_once '../../helpers/verificacion_roles.php';
AutorizacionRol('documentador');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensaje | Metadocs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../../../componentes/img/logopng.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../componentes/css/admin/panel.css">
    <link rel="stylesheet" href="../../../componentes/css/admin/control.css">
    <link rel="stylesheet" href="../../../componentes/css/documentador/solicitudes_doc.css">
    <link rel="stylesheet" href="../../../componentes/css/documentador/ver_mensaje.css">
    <script src="../../../componentes/js/admin/panel.js" defer></script>
</head>
<body>
    <header id="cabezote">
        <i class="bi bi-list" id="menu_opciones"></i>
    </header>

    <main id="cuerpo">
        <nav id="menu-lateral" class="menu-lateral">
            <figure id="img_menu">
                <img src="../../../componentes/img/Imagen de WhatsApp 2025-05-01 a las 11.52.47_deffc20c.jpg" alt="imagen del menu lateral">
            </figure>
            <ul>
                <li><a href="documentador_inicio.php"><i class="bi bi-house-door"></i>Inicio</a></li>
                <li><a href="ver_documentos.php"><i class="bi bi-file-earmark-text"></i>Documentos</a></li>
                <li><a href="" class="activo"><i class="bi bi-envelope-paper"></i>Solicitudes</a></li>
                <li class="gestion-usuarios">
                    <a href="#" id="cerrado-usuarios">
                        <i class="bi bi-person"></i>Usuario
                    </a>
                    <ul class="sub_menu usuario-submenu" id="sub_menu">
                        <li>
                            <form action="../../backend/login/cerrar_sesion.php" method="post">
                                <button type="submit"><i class="bi bi-box-arrow-left"></i>Cerrar sesión</button>
                            </form>
                        </li>
                        <li><a href="info_documentador.php"><i class="bi bi-info-circle"></i> Info usuario</a></li>
                        <li><a href=""><i class="bi bi-key-fill"></i> Cambiar contraseña</a></li>
                    </ul>
                </li>
                <li class="solo_mobil">
                    <a href="#" id="solo_mobil"><i class="bi bi-arrow-left-circle"></i>Volver</a>
                </li>
            </ul>
        </nav>

        <section class="contenedor-principal">
        
            <div class="mensaje-detalle">
                <h2>Juan Pérez <span class="rol">(Auditor)</span></h2>
                <div class="fecha">Enviado el: 12 de junio de 2025</div>
                <div class="descripcion">
                    Hola jorge llanos,<br><br>
                    Lo que pasa es que estoy necesitando el documento de validación de la graduacion del sena para el área de administración. Por favor revísalo y me puedes confirmar si está completo o si falta algo para aprobarlo.<br><br>
                    Gracias de antemano.
                </div>
            </div>
            <div class="boton-volver">
                <a href="solicitudes_doc.php">← Volver</a>
            </div>

        </section>
    </main>
</body>
</html>
