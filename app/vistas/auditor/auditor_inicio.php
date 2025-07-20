<?php 

require_once '../../helpers/verificacion_roles.php';
require_once '../../helpers/conexion_bd.php';

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
    <script src="../../../componentes/js/documentador/ver_documentos.js"></script>
    <script src="../../../componentes/js/admin/panel.js"></script>
    <link rel="stylesheet" href="../../../componentes/css/auditor/inicio_auditor.css">
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
                        <a href="#" class="activo">
                            <i class="bi bi-house-door"></i>
                            Inicio
                        </a>
                    </li>
                    <li class="gestion_usuario">
                        <a href="#" id="gestion-usuarios">
                            <i class="bi bi-file-earmark-text"></i>
                            Gestión Archivos
                        </a>
                        <ul class="sub_menu gestion-submenu" id="sub_menu">
                            <li><a href="recibir_documentos.php"><i class="bi bi-envelope-paper"></i>pendientes</a></li>
                            <li><a href="archivos_auditor.php"><i class="bi bi-eye"></i>Archivos</a></li>
                            <li><a href="solicitar_documento.php"><i class="bi bi-file-earmark-plus"></i> Solicitar archivos</a></li>
                            <li><a href="archivo_historico.php"><i class="bi bi-clock-history"></i> Archivo historico</a></li>
                        </ul>
                    </li>
                    
                    <li>
                        <a href="../../vistas/auditor/pista_auditoria.php">
                            <i class="bi bi-list-check"></i>
                            Pista auditoria
                        </a>
                    </li>
                    
                    <li class="gestion-usuarios">
                        <a href="#" id="cerrado-usuarios">
                            <i class="bi bi-person"></i>
                            Auditor
                        </a>
                        <ul class="sub_menu usuario-submenu" id="sub_menu">
                            <li><a href="../log/informacion_usuario.php"><i class="bi bi-info-circle"></i> Info auditor</a></li>
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

                <li class="cerrar-sesion-separado">
                    <a href="#" id="cerrar_sesion"><i class="bi bi-box-arrow-left"></i>Cerrar sesión</a>
                </li>
            </ul>
        </nav>
        
        <section id="admin-contenido" class="admin">
            <div class="panel-auditoria-container">
                <h1 class="titulo-auditor">Panel de Auditoría</h1>
                <h2 class="titulo-mediano">Monitoreo y control de documentos del sistema</h2>

                <!-- Métricas principales centradas -->
                <div class="metricas-auditoria">
                    <div class="metrica-card aprobados">
                        <div class="metrica-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="metrica-info">
                            <h3 class="metrica-numero"><?php echo "99" ?></h3>
                            <p class="metrica-label">Documentos Aprobados</p>
                        </div>
                    </div>

                    <div class="metrica-card revision">
                        <div class="metrica-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="metrica-info">
                            <h3 class="metrica-numero"><?php echo "99" ?></h3>
                            <p class="metrica-label">En Revisión</p>
                        </div>
                    </div>

                    <div class="metrica-card rechazados">
                        <div class="metrica-icon">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div class="metrica-info">
                            <h3 class="metrica-numero"><?php echo "99" ?></h3>
                            <p class="metrica-label">Documentos Rechazados</p>
                        </div>
                    </div>

                    <div class="metrica-card pendientes">
                        <div class="metrica-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div class="metrica-info">
                            <h3 class="metrica-numero"><?php echo "99" ?></h3>
                            <p class="metrica-label">Pendientes de Revisión</p>
                        </div>
                    </div>
                </div>

                <!-- Accesos rápidos -->
                <div class="accesos-rapidos">
                    <h3 class="accesos-titulo">Accesos Rápidos</h3>
                    <div class="contenedor-accesos">
                        <a href="recibir_documentos.php" class="acceso-link">
                            <div class="acceso-card">
                                <div class="acceso-icon">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <span class="acceso-label">Documentos Pendientes</span>
                            </div>
                        </a>

                        <a href="archivos_auditor.php" class="acceso-link">
                            <div class="acceso-card">
                                <div class="acceso-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <span class="acceso-label">Archivo Histórico</span>
                            </div>
                        </a>

                        <a href="../../vistas/auditor/pista_auditoria.php" class="acceso-link">
                            <div class="acceso-card">
                                <div class="acceso-icon">
                                    <i class="bi bi-list-check"></i>
                                </div>
                                <span class="acceso-label">Pista de Auditoría</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>
</body>
</html>