<?php


require_once '../../helpers/verificacion_roles.php';

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

    <script src="../../../componentes/js/admin/panel.js"></script>
    <script src="../../../componentes/js/admin/obtener_actividad.js"></script>
    <link rel="stylesheet" href="../../../componentes/css/admin/admin_actividades.css">
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
                <a href="../admin/panel_control.php" >
                    <i class="bi bi-bar-chart-line"></i>
                    Panel Control
                </a>
            </li>

              <li>
                <a href="../admin/pista_auditoria.php" class="activo">
                    <i class="bi bi-journal-check"></i>
                    Actividades usuarios
                </a>
            </li>

            <li class="gestion_usuario">
                <a href="#" id="gestion-usuarios"><i class="bi bi-people"></i> Gestión Usuarios</a>
                <ul class="sub_menu gestion-submenu" id="sub_menu">
                    <li><a href="../../vistas/admin/creacion_usuario.php"><i class="bi bi-person-plus"></i> Crear usuario</a></li>
                    <li><a href="../admin/ver_usuarios.php" ><i class="bi bi-eye"></i> Ver usuario</a></li>
                </ul>
            </li>
            
       
            
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
       
   

<section id="admin-contenido" class="admin">
   
    <div class="registro-actividades">
        <div class="registro-header">
            <h2>
                <i class="bi bi-activity"></i>
                Registro de Actividades
            </h2>
            <p class="registro-descripcion">
                Historial de acciones realizadas por los usuarios del sistema
            </p>
        </div>

        <div class="filtros-container">
            <div class="filtro-busqueda">
                <i class="bi bi-search"></i>
                <input type="text" id="busqueda" placeholder="Buscar por nombre, acción o título...">
            </div>
            <div class="filtros-selectores">
                <div class="filtro-selector">
                    <label for="filtro-accion">Acción:</label>
                    <select id="filtro-accion">
                        <option value="">Todas las acciones</option>
                        <option value="Aprobó">Aprobó</option>
                        <option value="Editó">Editó</option>
                        <option value="Solicitó">Solicitó</option>
                        <option value="Rechazó">Rechazó</option>
                          <option value="Subió">Subió</option>
                
                    </select>
                </div>
                <div class="filtro-selector">
                    <label for="filtro-archivo">Archivo:</label>
                    <select id="filtro-archivo">
                        <option value="">Todos los archivos</option>
                        <option value="documento">Documento</option>
                        <option value="expediente">Expediente</option>
                        <option value="null">Sin archivo</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="tabla-container">
            <table class="tabla-actividades">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Rol</th>
                        <th>Acción</th>
                        <th>Archivo</th>
                        <th>Título</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody id="tabla-body">
                    <!-- Datos se cargan aquí -->
                </tbody>
            </table>

            <div class="actividades-cards" id="cards-container">
                <!-- Vista móvil -->
            </div>

            <div class="mensaje-sin-resultados" id="sin-resultados" >
                <i class="bi bi-search"></i>
                <p>No se encontraron registros que coincidan con los filtros aplicados.</p>
            </div>

            
        </div>
     <!-- Contenedor de paginación corregido -->
<div class="paginacion-container" id="paginacion-container">
    <div class="paginacion-info">
        <span id="info-registros">
            Mostrando <span id="rango-inicio">1</span>-<span id="rango-fin">10</span> 
            de <span id="total-registros">0</span> registros
        </span>
    </div>
    
    <div class="paginacion-controles">
        <button type="button" id="btn-anterior" class="btn-paginacion">
            <i class="bi bi-chevron-left"></i>
            Anterior
        </button>
        
        <div class="numeros-pagina" id="numeros-pagina">
            <!-- Los números se generan dinámicamente -->
        </div>
        
        <button type="button" id="btn-siguiente" class="btn-paginacion">
            Siguiente
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>

<!-- Indicador de carga -->
<div class="loading-indicator" id="loading-indicator" style="display: none;">
    <div class="spinner"></div>
    <p>Cargando registros...</p>
</div>

     


</section>


     

     
    </main>

    
    <?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>
</body>

</html>