<?php 

require_once '../../helpers/verificacion_roles.php';

AutorizacionRol('auditor');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditor | Metadocs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../../../componentes/img/logopng.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../componentes/css/admin/panel.css">
    <link rel="stylesheet" href="../../../componentes/css/admin/control.css">
    <script src="../../../componentes/js/auditor/obtener_actividades.js"></script>

    <script src="../../../componentes/js/admin/panel.js"></script>

    <link rel="stylesheet" href="../../../componentes/css/auditor/pista_auditoria.css">
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
                    <a href="auditor_inicio.php" >
                        <i class="bi bi-house-door"></i>
                        Inicio
                    </a>
                </li>
                <li class="gestion_usuario">
                    <a href="#" id="gestion-usuarios" >
                        <i class="bi bi-file-earmark-text" ></i>
                        Gestión Archivos
                    </a>
                    <ul class="sub_menu gestion-submenu" id="sub_menu">
                        <li><a href="recibir_documentos.php"><i class="bi bi-envelope-paper"></i>pendientes</a></li>
                        <li><a href="archivos_auditor.php"><i class="bi bi-eye"></i>Archivos</a></li>
                        <li><a href="solicitar_documento.php"><i class="bi bi-file-earmark-plus"></i> Solicitar archivos</a></li>
                        <li><a href="archivo_historico.php"  > <i class="bi bi-clock-history"></i> Archivo historico</a></li>
                    </ul>
                </li>
                
                <li>
                    <a href="#" class="activo">
                        <i class="bi bi-list-check"></i>
                        Pista auditoria
                    </a>
                </li>
                
                <li class="gestion-usuarios">
                    <a href="#" id="cerrado-usuarios" >
                        <i class="bi bi-person"></i>
                        Auditor
                    </a>
                    <ul class="sub_menu usuario-submenu" id="sub_menu">
                        
                        <li><a href="../log/informacion_usuario.php"><i class="bi bi-info-circle"></i> Info auditor</a></li>
                        <li><a href=""><i class="bi bi-key-fill"></i> Cambiar contraseña</a></li>
                    </ul>
                </li>

                <li class="solo_mobil">
                    <a href="#" id="solo_mobil">
                        <i class="bi bi-arrow-left-circle"></i>
                        Volver
                    </a>
                </li>
            </div>

            <li class="cerrar-sesion-separado">
            <a href="#" id="cerrar_sesion"><i class="bi bi-box-arrow-left"></i>Cerrar sesión</a>
        </li>
            </ul>
        </nav>
        
        <section id="admin-contenido" class="admin">

            <div class="registro-actividades">
        <div class="registro-header">
            <h2>
                <i class="bi bi-activity"></i>
                Registro de Actividades
            </h2>
            <p class="registro-descripcion">
                Historial de acciones realizadas por los usuarios documentadores del sistema
            </p>
        </div>

        <div class="filtros-container">
            <div class="filtro-busqueda">
                <i class="bi bi-search"></i>
                <input type="text" id="busqueda" placeholder="Buscar por nombre, acción o título...">
            </div>
            <div class="filtros-selectores">
               
                <div class="filtro-selector">
                    <label for="filtro-archivo">Archivo:</label>
                    <select id="filtro-archivo">
                        <option value="">Todos los archivos</option>
                        <option value="Documento">Documento</option>
                        <option value="Expediente">Expediente</option>
                    
                    </select>
                </div>
            </div>
        </div>

      
        <div class="tabla-container">
            <!-- Vista de tabla para tablet y desktop -->
            <table class="tabla-actividades">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Acción</th>
                      
                        <th>Archivo</th>
                        <th>Título</th>
                          <th>Fecha</th>
                    </tr>
                </thead>
                <tbody id="tabla-body">
                    <!-- Los datos se cargarán dinámicamente -->
                </tbody>
            </table>

            <!-- Vista de tarjetas para móvil -->
            <div class="actividades-cards" id="cards-container">
                <!-- Las tarjetas se cargarán dinámicamente -->
            </div>

            <div class="mensaje-sin-resultados" id="sin-resultados" style="display: none;">
                <i class="bi bi-search"></i>
                <p>No se encontraron registros que coincidan con los filtros aplicados.</p>
            </div>
        </div>

        <!-- Fragmento HTML para agregar después del div tabla-container -->
<div class="paginacion-container">
    <div class="info-paginacion">
        <span id="info-registros"> 0 registros</span>
    </div>
    <div class="paginacion-controles">
        <button id="btn-anterior" class="btn-paginacion" disabled>
            <i class="bi bi-chevron-left"></i>
            Anterior
        </button>
        <div class="numeros-pagina" id="numeros-pagina">
            <!-- Los números se generarán dinámicamente -->
        </div>
        <button id="btn-siguiente" class="btn-paginacion" disabled>
            Siguiente
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>

<!-- Agregar este script al final del body -->

    </div>
        </section>



</main>
<?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>
</body>
</html>
