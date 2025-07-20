<?php

use Dom\Document;

require_once '../../backend/auditor/archivos_solicitados.php';
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
    <link rel="stylesheet" href="../../../componentes/css/auditor/recibir_documentos.css">
    <link rel="stylesheet" href="../../../componentes/css/auditor/modal_aprobar_expediente.css">
    <script src="../../../componentes/js/auditor/recibir_documentos.js"></script>
    <link rel="stylesheet" href="../../../componentes/css/documentador/visor.css">
    <script src="../../../componentes/js/admin/panel.js"></script>
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
                    <a href="#" id="gestion-usuarios" class="activo">
                        <i class="bi bi-file-earmark-text" ></i>
                        Gestión Archivos
                    </a>
                    <ul class="sub_menu gestion-submenu" id="sub_menu">
                        <li><a href="#" class="submenu-activo"><i class="bi bi-envelope-paper"></i>pendientes</a></li>
                        <li><a href="archivos_auditor.php"><i class="bi bi-eye"></i>Archivos</a></li>
                        <li><a href="solicitar_documento.php"><i class="bi bi-file-earmark-plus"></i> Solicitar archivos</a></li>
                        <li><a href="archivo_historico.php"  > <i class="bi bi-clock-history"></i> Archivo historico</a></li>
                    </ul>
                </li>
                
                <li>
                    <a href="../../vistas/auditor/pista_auditoria.php">
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
            
            <div id="titulo">

                <h1>Solicitudes de documentos</h1>
                <p>Revisa y aprueba documentos pendientes de validación</p>

            </div>


            <div class="navegacion-doc-expe">
                <div class="botones-navegacion">
                    <button type="button"  id="btn-documentos" data-tipo="documento"><i class="bi bi-file-earmark"></i>Documentos</button>
                    <button type="button" id="btn-expedientes" data-tipo="expediente"><i class="bi bi-folder"></i>Expedientes</button>
                </div>
            </div>

            <!-- Contenedor para expedientes -->
            <div id="contenedor-expedientes" class="contenedor-tipo">
                <?php
                if ($resultado_expediente->num_rows > 0) {
                    while ($expediente = $resultado_expediente->fetch_assoc()) {
                ?>
                        <article class="carta" id="carta_expediente" data-tipo="expediente">
                            <div class="info">
                                <div id="icono"><i class="bi bi-folder"></i></div>
                                <div id="info">
                                    <h3><?php echo htmlspecialchars($expediente['nombre']); ?></h3>
                                    <p>Expediente</p>
                                </div>
                            </div>
                            <p id="descripcion_expediente"><?php echo htmlspecialchars($expediente['descripcion']); ?></p>
                            <div id="autor_fecha">
                                <p><i class="bi bi-person-fill"></i><?php echo htmlspecialchars($expediente['nombre_autor']." ". $expediente['apellidos']); ?></p>
                                <p><i class="bi bi-calendar-fill"></i> <?php echo htmlspecialchars($expediente['fecha_creacion']); ?></p>
                            </div>
                            <div id="botones">  
                                <button type="button" class="aprobado btn-aprobar" data-id="<?php echo $expediente['id_expediente']; ?>">Aprobar</button>
                                <button type="button" class="rechazado " id="expe_rechazado">Rechazar</button>
                            </div>
                        </article>
                <?php
                    }
                } else {
                ?>
                    <div class="mensaje-vacio" id="mensaje-expedientes-vacio">
                        <p>No hay expedientes en revisión.</p>
                    </div>
                <?php
                }
                ?>
            </div>
            
            <!-- Contenedor para documentos -->
            <div id="contenedor-documentos" class="contenedor-tipo">
                <?php
                if($resultado_documento->num_rows > 0){
                    while($documento = $resultado_documento->fetch_assoc()){ 
                ?>
                    <article class="carta" id="carta_documento" data-tipo="documento">
                        <div class="info">
                            <div id="icono"><i class="bi bi-file-earmark"></i></div>
                            <div id="info">
                                <h3><?php echo htmlspecialchars($documento['titulo']);?></h3>
                                <p><?php echo htmlspecialchars($documento['categoria']);?></p>
                                <p class="expediente-info">
                                    Expediente Destino: 
                                    <strong><?php echo htmlspecialchars($documento['expediente'] ?? 'expediente_ejemplo'); ?></strong>
                                </p>
                            </div>
                        </div>
                        <div id="autor_fecha">
                            <p><i class="bi bi-person-fill"></i><?php echo htmlspecialchars($documento['nombres'] ." ". $documento['apellidos']);?> </p>
                            <p><i class="bi bi-calendar-fill"></i> <?php echo htmlspecialchars($documento['fecha_creacion']);?></p>
                        </div>
                        <div id="botones">  
                        <button type="button" class="ver btn_ver_modal escritorio" 
                                onclick="verDocumento('<?= urlencode($documento['titulo'] . '.' . $documento['tipo']) ?>', '<?= strtolower($documento['tipo']) ?>')">
                            <i class="bi bi-eye"></i> Ver
                        </button>

                        

                    
                        <button type="button" class="ver btn_ver_nueva_ventana movil" 
                                onclick="abrirNuevaVentana('<?= urlencode($documento['titulo'] . '.' . $documento['tipo']) ?>')">
                            <i class="bi bi-eye"></i> Ver
                        </button>

                            
                            <button type="button" class="aprobado" data-id="<?php echo $documento['id_documento']; ?>">Aprobar</button>
                            <button type="button" class="rechazado doc" data-id="<?php echo $documento['id_documento']; ?>">Rechazar</button>
                        </div>
                    </article>
                <?php
                    }
                } else {
                ?>
                    <div class="mensaje-vacio" id="mensaje-documentos-vacio">
                        <p>No hay documentos en revisión.</p>
                    </div>
                <?php
                }
                ?>
            </div>
        </section>

    </main>

    

    
<!-- modal aprobar documento -->
<div id="modal_confirmar_documento" class="modal_confirmar">
    <form class="modal_contenedor" action="../../backend/auditor/aprobar_expediente_documento.php" method="POST">
        <span class="close">&times;</span>
        <h3>¿Confirmas la aprobación de este documento?</h3>
        <p>¿Estás seguro de que deseas aprobar este documento? Esta acción no se puede deshacer y el documento pasará al siguiente estado del flujo de trabajo.</p>
        <div class="botones_modal">
            <button type="submit" class="btn_aprobar">Aprobar</button>
            <button type="button" class="btn_cancelar">Cancelar</button>
        </div>
        
        <input type="hidden" name="datos_documento" value="">
        <input type="hidden" name="accion" value="aprobar_documento">
        <input type="hidden" name="usuario_destinatario" value="">
        <input type="hidden" name="titulo" value="">
        <input type="hidden" name="categoria" value="">
        <input type="hidden" name="expediente" value="">
    </form>
</div>
<!-- modal aprobar expediente -->
<div id="modal_confirmar_expediente" class="modal_confirmar">
    <form class="modal_contenedor" action="../../backend/auditor/aprobar_expediente_documento.php" method="POST">
        <span class="close">&times;</span>
        <h3>¿Confirmas la aprobación de este expediente?</h3>
        <p>¿Estás seguro de que deseas aprobar este expediente? Esta acción no se puede deshacer y el expediente pasará al siguiente estado del flujo de trabajo.</p>
        <div class="botones_modal">
            <button type="submit" class="btn_aprobar">Aprobar</button>
            <button type="button" class="btn_cancelar">Cancelar</button>
        </div>
        
        <input type="hidden" name="datos_expediente" value="">
        <input type="hidden" name="usuario_destinatario" value="">
        <input type="hidden" name="nombre_expediente" value="">
        <input type="hidden" name="accion" value="aprobar_expediente">
    </form>
</div>

<!-- modal rechazar expediente -->
<div id="modal_rechazar_expediente" class="modal_confirmar">
    <form class="modal_contenedor" action="../../backend/auditor/aprobar_expediente_documento.php" method="POST">
        <span class="close">&times;</span>
        <h3>¿Confirmas el rechazo de este expediente?</h3>
        <p>Por favor, especifica el motivo del rechazo. Esta acción no se puede deshacer.</p>
        
        <div class="campo_motivo">
            <label for="motivo_expediente">Motivo del rechazo:</label>
            <textarea id="motivo_expediente" name="motivo_rechazo" rows="4" placeholder="Describe el motivo del rechazo..." required></textarea>
        </div>
        
        <div class="botones_modal">
            <button type="submit" class="btn_rechazar">Rechazar</button>
            <button type="button" class="btn_cancelar">Cancelar</button>
        </div>
        
        <input type="hidden" name="datos_expediente" value="">
        <input type="hidden" name="usuario_destinatario" value="">
        <input type="hidden" name="nombre_expediente" value="">
        <input type="hidden" name="accion" value="rechazar_expediente">
    </form>
</div>

<!-- modal rechazar documento -->
<div id="modal_rechazar_documento" class="modal_confirmar">
    <form class="modal_contenedor" action="../../backend/auditor/aprobar_expediente_documento.php" method="POST">
        <span class="close">&times;</span>
        <h3>¿Confirmas el rechazo de este documento?</h3>
        <p>Por favor, especifica el motivo del rechazo. Esta acción no se puede deshacer.</p>
        
        <div class="campo_motivo">
            <label for="motivo_documento">Motivo del rechazo:</label>
            <textarea id="motivo_documento" name="motivo_rechazo" rows="4" placeholder="Describe el motivo del rechazo..." required></textarea>
        </div>
        
        <div class="botones_modal">
            <button type="submit" class="btn_rechazar">Rechazar</button>
            <button type="button" class="btn_cancelar">Cancelar</button>
        </div>
        
        <input type="hidden" name="datos_documento" value="">
        <input type="hidden" name="usuario_destinatario" value="">
        <input type="hidden" name="titulo" value="">
        <input type="hidden" name="categoria" value="">
        <input type="hidden" name="expediente" value="">
        <input type="hidden" name="accion" value="rechazar_documento">
    </form>
</div>
<!-- Modal para visualizar documentos -->
<div id="modal_visor">
    <div id="modal_visor_content">
        <span id="cerrar_visor">&times;</span>
        <iframe id="visor_documento" src=""></iframe>
    </div>
</div>
    <script src="../../../componentes/js/documentador/visor.js" ></script>
    <?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>
</body>
</html>