<?php 

require_once '../../helpers/verificacion_roles.php';
require_once '../../backend/auditor/lista_documentadores.php';

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
    <link rel="stylesheet" href="../../../componentes/css/auditor/solicitar.css">
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
                        <li><a href="recibir_documentos.php" ><i class="bi bi-envelope-paper"></i>pendientes</a></li>
                        <li><a href="archivos_auditor.php"><i class="bi bi-eye"></i>Archivos</a></li>
                        <li><a href="#" class="submenu-activo"><i class="bi bi-file-earmark-plus"></i> Solicitar archivos</a></li>
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

    <h1>Solicitar documentos</h1>

        <div class="formulario-solicitud">
            <form action="../../backend/auditor/enviar_solicitud.php" method="post">
                <div class="campo">
                    <label for="tipo">Categoria</label>
                    <select id="tipo" name="tipo">
                        <option value="">Seleccione...</option>
                        <option value="Estratégicos">Estratégicos</option>
                        <option value="Operativos">Operativos</option>
                        <option value="Soporte">Soporte</option>
                        <option value="Legales">Legales</option>
                        <option value="Financieros">Financieros</option>
                        <option value="Correspondencia">Correspondencia</option>
                    </select>
                </div>

                <div class="campo">
                    <label for="responsable">Responsable:</label>
                    <div class="usuario-selector">
                        <input type="text" 
                                id="responsable" 
                                name="responsable_display" 
                                class="usuario-input" 
                                placeholder="Buscar documentador..." 
                                autocomplete="off">
                        <input type="hidden" name="responsable" id="responsable_id">
                        <div class="usuario-dropdown" id="usuario-dropdown"></div>
                    </div>
                </div>

                <div class="campo">
                    <label for="expediente">Expediente destinado:</label>
                    <div class="usuario-selector">
                        <input type="text" 
                                id="expediente" 
                                name="expediente_display" 
                                class="usuario-input" 
                                placeholder="Buscar expediente..." 
                                autocomplete="off">
                        <input type="hidden" name="expediente" id="expediente_id">
                        <div class="usuario-dropdown" id="expediente-dropdown"></div>
                    </div>
                </div>

                <div class="campo">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Describe qué documento necesitas y para qué."></textarea>
                </div>
                
                <button type="submit" class="btn-solicitar">Solicitar documento</button>
            </form>
        </div>
</section>

</main>

<script>
    // Datos desde PHP
    const datosCompletos = <?php echo json_encode($datos_documentadores['datos_completos']); ?>;
    
    // Hacer los datos globales para el JavaScript
    window.documentadores = datosCompletos.documentadores;
    window.expedientes = datosCompletos.expedientes;
</script>

<script src="../../../componentes/js/auditor/usuarios_input.js"></script>
    <?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>
</body>
</html>