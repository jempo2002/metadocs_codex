<?php 
require_once '../../helpers/verificacion_roles.php';
require_once '../../backend/auditor/lista_doc_archivados.php';


AutorizacionRol('auditor');

// DEBUG: Verifica si la variable existe
if (isset($documentos_archivados)) {
    echo "<!-- DEBUG: Documentos encontrados: " . count($documentos_archivados) . " -->";
} else {
    echo "<!-- DEBUG: Variable documentos_archivados no existe -->";
}
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
    <link rel="stylesheet" href="../../../componentes/css/auditor/archivo_historico.css">
   
    <link rel="stylesheet" href="../../../componentes/css/documentador/visor.css">
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
                        <li><a href="recibir_documentos.php"><i class="bi bi-envelope-paper"></i>pendientes</a></li>
                        <li><a href="archivos_auditor.php"><i class="bi bi-eye"></i>Archivos</a></li>
                        <li><a href="solicitar_documento.php"><i class="bi bi-file-earmark-plus"></i> Solicitar archivos</a></li>
                        <li><a href=""  class="submenu-activo"> <i class="bi bi-clock-history"></i> Archivo historico</a></li>
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
    <div class="contenedor_archivo">

        <h1>Archivo historico</h1>

        <!-- Filtros -->
        <div class="filtros_archivo">
            <div class="grupo-filtro">
                <label class="etiqueta-filtro" for="entradaBusqueda">Buscar por nombre</label>
                <input 
                    type="text" 
                    id="entradaBusqueda" 
                    class="entrada-filtro" 
                    placeholder="Escriba para buscar..."
                    onkeyup="filtrarTabla()"
                >
            </div>
            <div class="grupo-filtro">
                <label class="etiqueta-filtro" for="filtroCategoria">Filtrar por categoría</label>
                <select id="filtroCategoria" class="seleccion-filtro" onchange="filtrarTabla()">
                    <option value="">Todas las categorías</option>
                    <option value="Estrategicos">Estrategicos</option>
                    <option value="Operativos">Operativos</option>
                    <option value="Soporte">Soporte</option>
                    <option value="Legales">Legales</option>
                    <option value="Financieros">Financieros</option>
                    <option value="Correspondencia">Correspondencia</option>
                </select>
            </div>
        </div>

        <!-- Tabla -->
        <div class="envoltorio-tabla">
            <table class="tablaArchivo" id="tablaArchivo">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Tipo</th>
                        <th>Fecha Archivado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTabla">
                    <?php if (isset($documentos_archivados) && !empty($documentos_archivados)): ?>
                        <?php foreach ($documentos_archivados as $documento): ?>
                            <tr class="documentos" data-document-id="<?= isset($documento['id_documento']) ? $documento['id_documento'] : '' ?>">
                                <td class="documento-nombre">
                                    <i class="bi bi-file-earmark-text"></i> 
                                    <?php echo htmlspecialchars($documento['titulo']); ?>
                                </td>
                                <td class="documento-categoria"><?php echo htmlspecialchars($documento['categoria']); ?></td>
                                <td class="documento-tipo"><?php echo htmlspecialchars($documento['tipo']); ?></td>
                                <td class="documento-fecha">
                                    <?php 
                                    // Formatear la fecha
                                    if ($documento['fin_retencion']) {
                                        $fecha = new DateTime($documento['fin_retencion']);
                                        echo $fecha->format('d/m/Y');
                                    } else {
                                        echo 'No disponible';
                                    }
                                    ?>
                                </td>
                                <td class="documento-accion">
                                    <!-- Botón para escritorio - Modal -->
                                    <button class="btn_accion_archivo btn_ver_modal escritorio" 
                                            onclick="verDocumento('<?= urlencode($documento['titulo'] . '.' . $documento['tipo']) ?>', '<?= strtolower($documento['tipo']) ?>')">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <!-- Botón para móvil - Nueva ventana -->
                                    <button class="btn_accion__archivo btn_ver_nueva_ventana movil" 
                                            onclick="abrirNuevaVentana('<?= urlencode($documento['titulo'] . '.' . $documento['tipo']) ?>')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="filaVacia">
                            <td colspan="5" style="text-align: center; padding: 20px;">
                                No hay documentos archivados disponibles.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="sin-resultados" id="sinResultados" style="display: none;">
                No se encontraron documentos que coincidan con los filtros seleccionados.
            </div>
           

        </div>
         <div class="paginacion" style="text-align:center; margin-top:20px;">
    <?php if ($pagina > 1): ?>
        <a href="?pagina=<?php echo $pagina - 1; ?>" class="btn-paginacion">Anterior</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
        <a href="?pagina=<?php echo $i; ?>" class="btn-paginacion <?php echo ($i == $pagina) ? 'activa' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($pagina < $total_paginas): ?>
        <a href="?pagina=<?php echo $pagina + 1; ?>" class="btn-paginacion">Siguiente</a>
    <?php endif; ?>
</div>
    </div>
</section>

    </main>

    <!-- Modal para visualizar documentos -->
    <div id="modal_visor">
        <div id="modal_visor_content">
            <span id="cerrar_visor">&times;</span>
            <iframe id="visor_documento" src=""></iframe>
        </div>
    </div>

    <?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>

    <!-- Scripts -->
    <script src="../../../componentes/js/admin/panel.js"></script>
    <script src="../../../componentes/js/auditor/auditor_ver_docs.js"></script>
    <script src="../../../componentes/js/auditor/filtro_archivado.js"></script>
    <script src="../../../componentes/js/documentador/visor.js"></script>
    <!-- Agregar script adicional para manejo de tabla si es necesario -->
    <script src="../../../componentes/js/documentador/tabla_click.js"></script>
</body>
</html>