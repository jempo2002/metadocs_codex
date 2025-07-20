<?php 
require_once '../../backend/administrador/consulta_usuarios.php';
require_once '../../helpers/verificacion_roles.php';
AutorizacionRol('administrador');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Metadocs</title>
    
    <link rel="icon" href="../../../componentes/img/logopng.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    
    <link rel="stylesheet" href="../../../componentes/css/admin/panel.css">
    <script src="../../../componentes/js/admin/panel.js"></script>
    <link rel="stylesheet" href="../../../componentes/css/admin/controles.css">
    <link rel="stylesheet" href="../../../componentes/css/admin/ediciones_u.css">
    <script src="../../../componentes/js/admin/modal_editar.js" ></script>
    <link rel="stylesheet" href="../../../componentes/css/admin/eliminar_u.css">
    <script src="../../../componentes/js/admin/modal_eliminar.js" ></script>
    <script src="../../../componentes/js/admin/filtro_busqueda_usuarios.js" ></script>
    <link rel="stylesheet" href="../../../componentes/css/admin/lista_u.css">

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
                <a href="../admin/panel_control.php">
                    <i class="bi bi-bar-chart-line"></i>
                    Panel Control
                </a>
            </li>

              <li>
                <a href="../admin/pista_auditoria.php">
                    <i class="bi bi-journal-check"></i>
                    Actividades usuarios
                </a>
            </li>

            <li class="gestion_usuario">
                <a href="#" id="gestion-usuarios" class="activo"><i class="bi bi-people"></i> Gestión Usuarios</a>
                <ul class="sub_menu gestion-submenu" id="sub_menu">
                    <li><a href="../../vistas/admin/creacion_usuario.php"><i class="bi bi-person-plus"></i> Crear usuario</a></li>
                    <li><a href="../admin/ver_usuarios.php" class="submenu-activo"><i class="bi bi-eye"></i> Ver usuario</a></li>
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

            <h1>Lista de usuarios</h1>

            <div class="cont_nombre">

                <input type="text" name="buscar_usuario" id="buscar_usuario" placeholder="Buscar usuario...">
                <select id="roles">
                    <option value="">Todos los roles</option>
                    <option value="administrador">Administrador</option>
                    <option value="auditor">Auditor</option>
                    <option value="documentador">Documentador</option>
                </select>
            </div>

            <div class="tabla-contenedor">
                <table class="tabla-usuarios">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Área</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultado->num_rows <= 0) {
                            echo '<tr><td colspan="5">No hay usuarios en el sistema</td></tr>';
                        } else {
                            while ($fila = $resultado->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($fila['nombres']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['correo']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['rol']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['area']) . "</td>";
                                echo "<td class='acciones'>
                                        <i class='bi bi-pencil'></i>
                                        <i class='bi bi-trash'></i>
                                    </td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
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
        </section>
    </main>


    <!-- Modal editar -->


    <div id="modal-editar" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar usuario</h2>
            <form id="form_editar">
                <label>Nombre(s):</label>
                <input type="text" id="nombre_editar" required>

                <label>Correo electrónico:</label>
                <input type="email" id="correo_editar" required>

                <label>Rol:</label>
                <select id="rol_editar">
                    <option value="administrador">Administrador</option>
                    <option value="documentador">Documentador</option>
                    <option value="auditor">Auditor</option>
                </select>

                <label>Área:</label>
                <select id="area_editar">
                    <option value="administracion">Administración</option>
                    <option value="logistica">Logística</option>
                    <option value="contabilidad">Contabilidad</option>
                    <option value="otro">Otro</option>
                </select>

                <button type="submit" class="btn btn_editar">Guardar cambios</button>
            </form>
        </div>
    </div>

    <!-- Modal eliminar -->

    <div id="modal-eliminar" class="modal">
        <form class="modal-contenedor" >
            <span class="close">&times;</span>
            <h2>Confirmar eliminación</h2>
            <p>¿Estás seguro de que deseas eliminar este usuario?</p>
            <div class="modal-acciones">
                <button class="btn-cancelar">Cancelar</button>
                <button class="btn-eliminar">Eliminar</button>
                
            </div>
            
        </form>
    </div>

    <?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>
</body>

</html>
