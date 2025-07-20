<?php 

require_once '../../helpers/verificacion_roles.php';

AutorizacionRol('documentador');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentador | Metadocs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../../../componentes/img/logopng.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../componentes/css/admin/panel.css">
    <link rel="stylesheet" href="../../../componentes/css/admin/control.css">
    <script src="../../../componentes/js/admin/panel.js"></script>
    <link rel="stylesheet" href="../../../componentes/css/documentador/inicio_documentador.css">
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
             <div class="menu-opciones-principales">
                <li>
                    <a href="" class="activo">
                        <i class="bi bi-house-door"></i>
                        Inicio
                    </a>
                </li>
                    <li>
                    <a href="ver_documentos.php">
                        <i class="bi bi-file-earmark-text"></i>
                        Archivos
                    </a>
                </li>
                
                <li>
                    <a href="solicitudes_doc.php">
                        <i class="bi bi-envelope-paper"></i>
                            Solicitudes
                    </a>
                </li>
                    <!-- cerrado sesion -->  
                <li class="gestion-usuarios">
                    <a href="#" id="cerrado-usuarios">
                        <i class="bi bi-person"></i>
                        Documentador
                    </a>
                    <ul class="sub_menu usuario-submenu" id="sub_menu">
                       
                        <li><a href="../documentador/info_documentador.php"><i class="bi bi-info-circle"></i> Info documentador</a></li>
                        <li><a href="../log/nueva_contraseña.php"><i class="bi bi-key-fill"></i> Cambiar contraseña</a></li>

                        
                    </ul>
                </li>

                <li class="solo_mobil">
                    <a href="#" id="solo_mobil">
                        <i class="bi bi-arrow-left-circle"></i>
                        Volver
                    </a>
                </li>
             </div>
              <li class="cerrar-sesion-separado"><a href="#" id="cerrar_sesion"><i class="bi bi-box-arrow-left"></i>Cerrar sesion</a></li>
            </ul>
        </nav>
        
        <section id="admin-contenido" class="admin">
            <h1 class="titulo-documentador">Bienvenido Documentador</h1>

            <h2 class="titulo-mediano">selecciona a donde quieres dirigirte</h2>

            <div class="contenedor-general">
                
                <a href="solicitudes_doc.php" class ="card-link">
                    <div class="card-opcion">
                        <img src="https://cdn-icons-png.flaticon.com/128/8521/8521942.png" alt="solicitudes">
                        <label for="">Solicitudes</label>
                    </div>
                </a>
                
                <a href="ver_documentos.php" class="card-link">
                    <div class="card-opcion">
                        <img src="https://cdn-icons-png.flaticon.com/128/10650/10650289.png" alt="ver documentos">
                        <label>Documentos</label>
                    </div>
                </a>

                <a href="info_documentador.php" class="card-link">
                    <div class="card-opcion">
                        <img src="https://cdn-icons-png.flaticon.com/128/5655/5655237.png" alt="informacion del documentador">
                        <label>Informacion del documentador</label>
                    </div>
                </a>

            </div>

        </section>


</main>

    <?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>
</body>
</html>
