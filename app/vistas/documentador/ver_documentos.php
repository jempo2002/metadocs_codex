<?php 
require_once '../../helpers/verificacion_roles.php';
require_once '../../backend/documentador/gestor_archivos.php';
AutorizacionRol('documentador');

$padre_id = isset($_GET['id_expediente']) ? $_GET['id_expediente'] : 0;
$expediente_seleccionado = $padre_id;

// Obtener expedientes con datos de paginación
$resultado_expedientes = obtenerExpedientes($conexion_metadocs, $padre_id, $area);
$carpetas = $resultado_expedientes['expedientes'];
$pagina_expedientes = $resultado_expedientes['pagina_actual'];
$total_paginas_expedientes = $resultado_expedientes['total_paginas'];
$total_registros_expedientes = $resultado_expedientes['total_registros'];

$resultado_contenido = obtenerContenidoUnificado($conexion_metadocs, $padre_id, $area);
$contenido_unificado = $resultado_contenido['contenido'];
$pagina_actual = $resultado_contenido['pagina_actual'];
$total_paginas = $resultado_contenido['total_paginas'];
$total_registros = $resultado_contenido['total_registros'];


// Resto del código de modales...
$mostrar_modal = false;
$mostrar_modal_expediente = false;

if (isset($_SESSION['show_modal']) && $_SESSION['show_modal'] === true) {
    $mostrar_modal = true;
    unset($_SESSION['show_modal']); 
}

if (isset($_SESSION['show_modal_expediente']) && $_SESSION['show_modal_expediente'] === true) {
    $mostrar_modal_expediente = true; 
    unset($_SESSION['show_modal_expediente']); 
}
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
    <link rel="stylesheet" href="../../../componentes/css/documentador/ver_documentos.css">
    <link rel="stylesheet" href="../../../componentes/css/documentador/modal_expediente.css">
    <link rel="stylesheet" href="../../../componentes/css/auditor/modal_scanear_subir_doc.css">
    <link rel="stylesheet" href="../../../componentes/css/documentador/visor.css">
    <link rel="stylesheet" href="../../../componentes/css/documentador/archivo_revision.css">
    <script src="../../../componentes/js/documentador/ver_documentos.js"></script>
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
                    <a href="documentador_inicio.php">
                        <i class="bi bi-house-door"></i>
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="#" class="activo">
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
                
                <li class="gestion-usuarios">
                    <a href="#" id="cerrado-usuarios">
                        <i class="bi bi-person"></i>
                        Documentador
                    </a>
                    <ul class="sub_menu usuario-submenu" id="sub_menu">
                            
                        <li><a href="info_documentador.php"><i class="bi bi-info-circle"></i> info documentador</a></li>
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
                <li  class="cerrar-sesion-separado"><a href="#" id="cerrar_sesion"><i class="bi bi-box-arrow-left"></i>Cerrar sesion</a></li>
            </ul>
        </nav>
        
       <section id="admin-contenido" class="admin">
     
            
    <?php if ($expediente_seleccionado): ?>
         <!-- navegación -->
            <div class="breadcrumb">
                <a href="?">Inicio</a> <i class="bi bi-chevron-right"></i> 
                <a href="javascript:history.back()" class="back-button">Atrás</a> 
              <?php 
                $carpeta_actual = obtenerInfoExpediente($conexion_metadocs, $expediente_seleccionado);
                if ($carpeta_actual) {
                
                    echo '<i class="bi bi-chevron-right"></i> ' . htmlspecialchars($carpeta_actual['nombre']);
                } else {
                
                }
            ?>
            </div>
        <div class="title-button-container">
            <div class="title-header-row">
                <h1>Documentos</h1>
                <div class="header-buttons">
                    <button type="button" id="btn_documento">
                        <i class="bi bi-upload"></i> Subir documento
                    </button>
                    <button type="button" id="btn_crear">
                        <i class="bi bi-plus-circle"></i> Crear Carpetas
                    </button>
                </div>
            </div>
            
          
        </div>
    <?php else: ?>
        <div class="title-button-container">
            <h1>Carpetas</h1>
        </div>
    <?php endif; ?>
    
    <div class="buscar-documentos">
        <input type="text" class="input-buscar" placeholder="Buscar carpeta o archivo...">
        <?php if (!$expediente_seleccionado): ?>
            <button class="btn-crear" id="btn_crear">Crear expediente</button>
        <?php endif; ?>
    </div>

    <article class="tabla-documentos">
        <table>
            <thead>
                <tr class="table-cabeza">
                    <th>NOMBRE</th>
                    <th>TIPO</th>
                    <th>FECHA SUBIDA</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($contenido_unificado)): ?>
                    <?php foreach ($contenido_unificado as $item): ?>
                        <?php if ($item['tipo_contenido'] === 'expediente'): ?>
                            <tr class="documentos" data-url="?id_expediente=<?= $item['id']; ?>">
                                <td class="documento-nombre">
                                    <a href="?id_expediente=<?= $item['id']; ?>">
                                        <i class="bi bi-folder2"></i> <?= htmlspecialchars($item['nombre']); ?>
                                    </a>
                                </td>
                                <td class="documento-tipo">expediente</td>
                                <td class="documento-fecha"><?= htmlspecialchars($item['fecha_creacion']); ?></td>
                                <td class="documento-accion">
                                    <button class="btn_accion" data-id="<?= $item['id']; ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr class="documentos" data-document-id="<?= $item['id'] ?>">
                                <td class="documento-nombre">
                                    <i class="bi bi-file-earmark-text"></i> 
                                    <?= htmlspecialchars($item['nombre']); ?>
                                </td>
                                <td class="documento-tipo"><?= htmlspecialchars($item['tipo']); ?></td>
                                <td class="documento-fecha"><?= htmlspecialchars($item['fecha_creacion']); ?></td>
                                <td class="documento-accion">
                                    <button class="btn_accion btn_ver_modal escritorio" onclick="verDocumento('<?= urlencode($item['nombre'] . '.' . $item['tipo']) ?>', '<?= strtolower($item['tipo']) ?>')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn_accion btn_ver_nueva_ventana movil" onclick="abrirNuevaVentana('<?= urlencode($item['titulo'] . '.' . $item['tipo']) ?>')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="no-content">
                            <?php if ($expediente_seleccionado): ?>
                                No hay contenido para mostrar en este expediente.
                            <?php else: ?>
                                No hay expedientes para mostrar.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </article>

    <!-- Paginación unificada -->
    <?php if ($total_paginas > 1): ?>
        <div class="paginacion" style="text-align:center; margin-top:20px;">
            <?php if ($pagina_actual > 1): ?>
                <a href="?pagina=<?php echo $pagina_actual - 1; ?><?php echo $padre_id ? '&id_expediente=' . $padre_id : ''; ?>" class="btn-paginacion">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?pagina=<?php echo $i; ?><?php echo $padre_id ? '&id_expediente=' . $padre_id : ''; ?>" 
                   class="btn-paginacion <?php echo ($i == $pagina_actual) ? 'activa' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($pagina_actual < $total_paginas): ?>
                <a href="?pagina=<?php echo $pagina_actual + 1; ?><?php echo $padre_id ? '&id_expediente=' . $padre_id : ''; ?>" class="btn-paginacion">Siguiente</a>
            <?php endif; ?>
            
            <div class="info-paginacion">
                <small>
                    Página <?php echo $pagina_actual; ?> de <?php echo $total_paginas; ?> 
                    (<?php echo $total_registros; ?> elementos total)
                </small>
            </div>
        </div>
    <?php endif; ?>

    <!-- Información cuando no hay paginación -->
    <?php if (!empty($contenido_unificado) && $total_paginas == 1): ?>
        <div class="info-paginacion" style="text-align:center; margin-top:10px;">
            <small><?php echo $total_registros; ?> elementos total</small>
        </div>
    <?php endif; ?>

</section>
    </main>

    <!-- Modal para crear expediente -->
    <div id="modal_expediente" class="modal">
        <div id="form_carpeta">
            <form action="../../backend/documentador/gestor_archivos.php" method="post">
                <div id="titulo_carpeta_header">
                    <h2>Crear expediente</h2>
                    <span class="close" id="close">&times;</span>
                </div>
                <div id="input_carpeta">
                    <label for="titulo_carpeta_input">Ingrese el título</label>
                    <input type="hidden" name="expediente_padre" value="<?= $expediente_seleccionado ?>">
                    <input type="text" id="titulo_carpeta_input" name="titulo_carpeta" placeholder="Ingrese el título del expediente" required>
                    
                    <label for="desc_carpeta_input">Descripción</label>
                    <textarea id="desc_carpeta_input" name="desc_carpeta" placeholder="Ingrese la descripción del expediente" required></textarea>
                </div>
                <div id="btn_carpeta">
                    <button type="submit" name="accion" value="subir_expediente">Crear</button>
                </div>  
            </form>
        </div>
    </div>

    <div id="modal_escanear_subir">
        <div id="modal_contenido">
            <span class="cerrar_modal_esc_sub ">&times;</span>
            
            <div id="contenido">
                <h3>Elige una opción</h3>
                
                <div id="cont_escanear_subir">
                    <a href="" id="scanear" class="esc_sub">
                        <i class="bi bi-printer"></i>
                        <p>Escanear</p>
                        <p>Escanea un documento y súbelo al sistema</p>
                    </a>
                    
                    <a href="subir_documento.php?id_expediente=<?= $padre_id ?> " id="subir" class="esc_sub">
                        <i class="bi bi-file-earmark-arrow-up"></i>
                        <p>Subir documento</p>
                        <p>Selecciona un archivo desde tu dispositivo</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualizar documentos -->
    <div id="modal_visor">
        <div id="modal_visor_content">
            <span id="cerrar_visor">&times;</span>
            <iframe id="visor_documento" src=""></iframe>
        </div>
    </div>

    <!-- Modal para editar expediente 
    <div id="editModal" class="modal">
        <div class="modal-content">
            <form action="../../backend/documentador/gestor_archivos.php" method="post">
                <div id="titulo_carpeta_header">
                    <span class="close">&times;</span>
                    <h2>Editar expediente</h2>
                </div>
                <div id="input_carpeta">
                    <input type="hidden" name="id_expediente" id="edit_expediente_id">
                    <label for="nuevo_titulo">Título</label>
                    <input type="text" id="nuevo_titulo" name="nuevo_titulo" required>
                    
                    <label for="nueva_descripcion">Descripción</label>
                    <textarea id="nueva_descripcion" name="nueva_descripcion" required></textarea>
                </div>
                <div id="btn_carpeta">
                    <input type="hidden" name="accion" value="editar_expediente">
                    <button type="submit">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>-->


    <!-- modal archivo en revision -->
<?php if($mostrar_modal): ?>
        <div class="modal-overlay-doc" id="modalOverlay">
            <div class="modal_doc_recibido">
            <span class="close" id="mrd">&times;</span>
            <div class="icon"><i class="bi bi-check2-circle"></i></div>
            <h2>Subida completada</h2>
            <p>Tu documento ha sido recibido y ya está en revisión por un auditor.</p>
            </div>
        </div>
<?php endif; ?>

<?php if($mostrar_modal_expediente): ?>
        <div class="modal-overlay-doc" id="modalOverlay">
            <div class="modal_doc_recibido">
            <span class="close" id="mrd">&times;</span>
            <div class="icon"><i class="bi bi-check2-circle"></i></div>
            <h2>Subida completada</h2>
            <p>Tu expediente ha sido recibido y ya está en revisión por un auditor.</p>
            </div>
        </div>
<?php endif; ?>

    <script src="../../../componentes/js/documentador/tabla_click.js"></script>
    <script src="../../../componentes/js/documentador/filtro_tabla.js"></script>
    <script src="../../../componentes/js/documentador/visor.js"></script>
    <?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>
    
</body>
</html>