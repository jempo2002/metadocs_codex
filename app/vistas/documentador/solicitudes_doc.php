<?php 
require_once '../../helpers/verificacion_roles.php';
AutorizacionRol('documentador');
require_once '../../backend/documentador/recibir_actividades.php';
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
    <link rel="stylesheet" href="../../../componentes/css/documentador/solicitudes_doc.css">
    <link rel="stylesheet" href="../../../componentes/css/documentador/contenido_solicitud.css">
    <link rel="stylesheet" href="../../../componentes/css/documentador/modales_doc_exp.css">
    <script src="../../../componentes/js/admin/panel.js" defer></script>
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
                <li><a href="documentador_inicio.php"><i class="bi bi-house-door"></i>Inicio</a></li>
                <li><a href="ver_documentos.php"><i class="bi bi-file-earmark-text"></i>Archivos</a></li>
                <li><a href="" class="activo"><i class="bi bi-envelope-paper"></i>Solicitudes</a></li>
                <li class="gestion-usuarios">
                    <a href="#" id="cerrado-usuarios">
                        <i class="bi bi-person"></i>Usuario
                    </a>
                    <ul class="sub_menu usuario-submenu" id="sub_menu">
                     
                        <li><a href="info_documentador.php"><i class="bi bi-info-circle"></i> Info usuario</a></li>
                        <li><a href=""><i class="bi bi-key-fill"></i> Cambiar contraseña</a></li>
                    </ul>
                </li>
                <li class="solo_mobil">
                    <a href="#" id="solo_mobil"><i class="bi bi-arrow-left-circle"></i>Volver</a>
                </li>
                  </div>
                   <li  class="cerrar-sesion-separado"><a href="#" id="cerrar_sesion"><i class="bi bi-box-arrow-left"></i>Cerrar sesion</a></li>
            </ul>
        
           
        </nav>

        <section class="contenedor-principal">
            <h1>Solicitudes Recibidas</h1>

            <div class="filtro-mensajes">
                <label for="tipo-filtro">Filtrar por tipo:</label>
                <select id="tipo-filtro">
                    <option value="todos">Todos</option>
                    <option value="solicitud_documento">Solicitud de documento</option>
                    <option value="documento_aprobado">Documento aprobado</option>
                    <option value="documento_rechazado">Documento rechazado</option>
                    <option value="expediente_aprobado">Expediente aprobado</option>
                    <option value="expediente_rechazado">Expediente rechazado</option>
                </select>
            </div>

            <div class="contenedor-mensajes">
                <!-- Reemplaza la sección de generación de notificaciones en tu HTML con esto: -->

<div class="lista-mensajes" id="lista-notificaciones">
   
    <?php if (!empty($notificaciones_procesadas)): ?>
        <?php foreach ($notificaciones_procesadas as $notificacion): ?>
            <?php 
            // Escapar los datos para JavaScript de forma segura
            $datos_json = htmlspecialchars(json_encode($notificacion), ENT_QUOTES, 'UTF-8');
            ?>
            <div class="mensaje <?php echo $notificacion['es_visto'] ? 'visto' : 'no-visto'; ?> clickeable" 
                    data-tipo="<?php echo htmlspecialchars($notificacion['tipo_actividad']); ?>"
                    data-id="<?php echo $notificacion['id']; ?>"
                    data-modal="<?php echo htmlspecialchars($notificacion['modal']); ?>"
                    onclick="abrirModal('<?php echo htmlspecialchars($notificacion['modal']); ?>', <?php echo $datos_json; ?>)">
                
                <div class="icono-mensaje">
                    <i class="bi <?php echo htmlspecialchars($notificacion['icono']); ?>"></i>
                </div>
                
                <div class="contenido-mensaje">
                    <h2><?php echo htmlspecialchars($notificacion['usuario_nombre']); ?></h2>
                    <p><?php echo htmlspecialchars($notificacion['texto_tipo']); ?></p>
                </div>
                
                <div class="fecha-mensaje">
                    <p><?php echo htmlspecialchars($notificacion['tiempo_transcurrido']); ?></p>
                </div>
            </div>
            
            <?php // Opcional: Mostrar debug para cada notificación durante desarrollo ?>
            <?php // debug_notificacion($notificacion); ?>
            
        <?php endforeach; ?>
    <?php else: ?>
        <div class="mensaje-vacio">
            <p>No tienes notificaciones en este momento</p>
        </div>
    <?php endif; ?>
</div>
                </div>
            </div>
                  <div class="paginacion" style="text-align:center; margin-top:20px;">
    <?php if ($pagina_actual > 1): ?>
        <a href="?pagina=<?php echo $pagina_actual - 1; ?>" class="btn-paginacion">Anterior</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
        <a href="?pagina=<?php echo $i; ?>" class="btn-paginacion <?php echo ($i == $pagina_actual) ? 'activa' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($pagina_actual < $total_paginas): ?>
        <a href="?pagina=<?php echo $pagina + 1; ?>" class="btn-paginacion">Siguiente</a>
    <?php endif; ?>
</div>
        </section>
    </main>

    <!-- PASO 11: MODALES DINÁMICOS -->
    
    <!-- Modal solicitud documento -->
    <div id="modal-solicitud-documento" class="modal-overlay">
        <div class="modal-container">
            <button class="cerrar-modal" onclick="cerrarModal('solicitud-documento')">&times;</button>
            
            <div class="modal-header">
                <div class="icono-estado">
                    <i class="bi bi-person"></i>
                </div>
                <div class="info-usuario">
                    <h3 id="modal-solicitud-usuario">-</h3>
                    <p>Auditor</p>
                </div>
            </div>

            <div class="modal-body">
                <div class="solicitud-info">
                    <h2>Nueva Solicitud de Documento</h2>
                    <p id="modal-solicitud-mensaje">Se te ha solicitado un documento</p>
                </div>

                <div class="detalles-solicitud">
                    <div class="detalle-item">
                        <span class="detalle-label">Solicitante:</span>
                        <span class="detalle-valor" id="modal-solicitud-solicitante">-</span>
                    </div>

                    <div class="detalle-item">
                        <span class="detalle-label">Categoría:</span>
                        <span class="detalle-valor" id="modal-solicitud-categoria">-</span>
                    </div>

                    <div class="detalle-item">
                        <span class="detalle-label">Expediente:</span>
                        <span class="detalle-valor" id="modal-solicitud-expediente">-</span>
                    </div>

                    <div class="detalle-item">
                        <span class="detalle-label">Fecha de solicitud:</span>
                        <span class="detalle-valor" id="modal-solicitud-fecha">-</span>
                    </div>
                </div>

                <div class="mensaje-solicitud">
                    <strong>Detalles</strong><br>
                    <span id="modal-solicitud-detalles">-</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal documento aprobado -->
    <div id="modal-documento-aprobado" class="modal-overlay">
        <div class="modal-container">
            <button class="cerrar-modal" onclick="cerrarModal('documento-aprobado')">&times;</button>
            
            <div class="modal-header documento-aprobado">
                <div class="icono-estado">
                    <i class="bi bi-person"></i>
                </div>
                <div class="info-usuario">
                    <h3 id="modal-doc-aprobado-usuario">-</h3>
                    <p>Sistema de notificaciones</p>
                </div>
            </div>

            <div class="modal-body">
                <div class="estado-notificacion aprobado">
                    <h2>¡Documento Aprobado!</h2>
                    <p id="modal-doc-aprobado-mensaje">Tu documento ha sido revisado y aprobado exitosamente</p>
                </div>

                <div class="detalles-documento">
                    <div class="detalle-item">
                        <span class="detalle-label">Nombre del documento:</span>
                        <span class="detalle-valor" id="modal-doc-aprobado-titulo">-</span>
                    </div>
                    
                    <div class="detalle-item">
                        <span class="detalle-label">Categoría:</span>
                        <span class="detalle-valor" id="modal-doc-aprobado-categoria">-</span>
                    </div>

                    <div class="detalle-item">
                        <span class="detalle-label">Expediente:</span>
                        <span class="detalle-valor" id="modal-doc-aprobado-expediente">-</span>
                    </div>
                    
                    <div class="detalle-item">
                        <span class="detalle-label">Fecha de aprobación:</span>
                        <span class="detalle-valor" id="modal-doc-aprobado-fecha">-</span>
                    </div>

                    <div class="detalle-item">
                        <span class="detalle-label">Aprobado por:</span>
                        <span class="detalle-valor" id="modal-doc-aprobado-por">-</span>
                    </div>

                    <div class="acciones-modal">
                        <button class="btn-accion btn-secundario" onclick="cerrarModal('documento-aprobado')">Cerrar</button>
                        <button class="btn-accion btn-success">Ver documento</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal documento rechazado -->
    <div id="modal-documento-rechazado" class="modal-overlay">
        <div class="modal-container">
            <button class="cerrar-modal" onclick="cerrarModal('documento-rechazado')">&times;</button>
            
            <div class="modal-header documento-rechazado">
                <div class="icono-estado">
                    <i class="bi bi-person"></i>
                </div>
                <div class="info-usuario">
                    <h3 id="modal-doc-rechazado-usuario">-</h3>
                    <p>Sistema de notificaciones</p>
                </div>
            </div>

            <div class="modal-body">
                <div class="estado-notificacion rechazado">
                    <h2>Documento Rechazado</h2>
                    <p id="modal-doc-rechazado-mensaje">Tu documento fue rechazado y no se subió al sistema</p>
                </div>

                <div class="detalles-documento">
                    <div class="detalle-item">
                        <span class="detalle-label">Nombre del documento:</span>
                        <span class="detalle-valor" id="modal-doc-rechazado-titulo">-</span>
                    </div>
                    
                    <div class="detalle-item">
                        <span class="detalle-label">Categoría:</span>
                        <span class="detalle-valor" id="modal-doc-rechazado-categoria">-</span>
                    </div>
                    
                    <div class="detalle-item">
                        <span class="detalle-label">Fecha de rechazo:</span>
                        <span class="detalle-valor" id="modal-doc-rechazado-fecha">-</span>
                    </div>

                    <div class="detalle-item">
                        <span class="detalle-label">Rechazado por:</span>
                        <span class="detalle-valor" id="modal-doc-rechazado-por">-</span>
                    </div>
                </div>

                <div class="motivos-rechazo">
                    <strong>Motivos del rechazo:</strong>
                    <p id="modal-doc-rechazado-motivo">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal expediente aprobado -->
    <div id="modal-expediente-aprobado" class="modal-overlay">
        <div class="modal-container">
            <button class="cerrar-modal" onclick="cerrarModal('expediente-aprobado')">&times;</button>
            
            <div class="modal-header expediente-aprobado">
                <div class="icono-estado">
                    <i class="bi bi-person"></i>
                </div>
                <div class="info-usuario">
                    <h3 id="modal-exp-aprobado-usuario">-</h3>
                    <p>Sistema de notificaciones</p>
                </div>
            </div>

            <div class="modal-body">
                <div class="estado-notificacion expediente-aprobado">
                    <h2>¡Expediente Aprobado!</h2>
                    <p id="modal-exp-aprobado-mensaje">Tu expediente ha sido procesado y aprobado completamente</p>
                </div>

                <div class="detalles-documento">
                    <div class="detalle-item">
                        <span class="detalle-label">Nombre expediente:</span>
                        <span class="detalle-valor" id="modal-exp-aprobado-titulo">-</span>
                    </div>
                    
                    <div class="detalle-item">
                        <span class="detalle-label">Fecha de aprobación:</span>
                        <span class="detalle-valor" id="modal-exp-aprobado-fecha">-</span>
                    </div>
                    
                    <div class="detalle-item">
                        <span class="detalle-label">Aprobado por:</span>
                        <span class="detalle-valor" id="modal-exp-aprobado-por">-</span>
                    </div>
                </div>

                <div class="acciones-modal">
                    <button class="btn-accion btn-secundario" onclick="cerrarModal('expediente-aprobado')">Cerrar</button>
                    <button class="btn-accion btn-success">Ver Expediente</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal expediente rechazado -->
    <div id="modal-expediente-rechazado" class="modal-overlay">
        <div class="modal-container">
            <button class="cerrar-modal" onclick="cerrarModal('expediente-rechazado')">&times;</button>
            
            <div class="modal-header expediente-rechazado">
                <div class="icono-estado">
                <i class="bi bi-person"></i>
                </div>
                <div class="info-usuario">
                    <h3 id="modal-exp-rechazado-usuario">-</h3>
                    <p>Auditor</p>
                </div>
            </div>

            <div class="modal-body">
                <div class="estado-notificacion expediente-rechazado">
                    <h2>Expediente Rechazado</h2>
                    <p id="modal-exp-rechazado-mensaje">Tu expediente presenta inconsistencias que deben ser corregidas</p>
                </div>

                <div class="detalles-documento">
                    <div class="detalle-item">
                        <span class="detalle-label">Nombre expediente:</span>
                        <span class="detalle-valor" id="modal-exp-rechazado-titulo">-</span>
                    </div>
                    
                    <div class="detalle-item">
                        <span class="detalle-label">Fecha de rechazo:</span>
                        <span class="detalle-valor" id="modal-exp-rechazado-fecha">-</span>
                    </div>
                    
                    <div class="detalle-item">
                        <span class="detalle-label">Rechazado por:</span>
                        <span class="detalle-valor" id="modal-exp-rechazado-por">-</span>
                    </div>
                </div>

                <div class="motivos-rechazo">
                    <strong>Motivos del rechazo:</strong>
                    <p id="modal-exp-rechazado-motivo">-</p>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>
    <script src="../../../componentes/js/documentador/filtro_solicitud.js"></script>
    <script src="../../../componentes/js/documentador/recibir_actividad.js"></script>
    <script src="../../../componentes/js/documentador/notificacion.js"></script>
</body>
</html>