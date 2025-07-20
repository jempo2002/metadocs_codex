<?php 

session_start();

require_once '../../helpers/verificacion_roles.php';
AutorizacionRol('administrador');


$exito = $_SESSION['exito'] ?? null;
unset($_SESSION['exito']);

$corre_exitente = $_SESSION['correo_existente'] ?? null;
unset($_SESSION['correo_existente']);



?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin | Metadocs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="icon" href="../../../componentes/img/logopng.png" type="image/x-icon" />
    <link rel="stylesheet" href="../../../componentes/css/admin/panel.css" />
    <link rel="stylesheet" href="../../../componentes/css/admin/creacion_usuario.css" />
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
        <!-- Opciones principales del menú -->
        <div class="menu-opciones-principales">
            <li>
                <a href="../admin/panel_control.php">
                    <i class="bi bi-bar-chart-line"></i>
                    Panel Control
                </a>
            </li>

            <div class="menu-opciones-principales">
            <li>
                <a href="../admin/pista_auditoria.php">
                    <i class="bi bi-journal-check"></i>
                    Actividades usuarios
                </a>
            </li>

            <li class="gestion_usuario">
                <a href="#" id="gestion-usuarios" class="activo"><i class="bi bi-people"></i> Gestión Usuarios</a>
                <ul class="sub_menu gestion-submenu" id="sub_menu">
                    <li><a href="#" class="submenu-activo"><i class="bi bi-person-plus"></i> Crear usuario</a></li>
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
    

        <?php if ($exito): ?>
        <div class="mensaje_exito" id="mensaje-exito">
            <p><?= htmlspecialchars($exito) ?></p>
        </div>
    

        <?php endif; ?>

        <?php if ($corre_exitente): ?>
            <div class="correo_existente" id="correo_existente">
            <p><?= htmlspecialchars($corre_exitente) ?></p>
        </div>
        <?php endif; ?>


        <section id="admin-contenido" class="admin">
    <div class="contenedor-formulario"> <!-- Contenedor nuevo para controlar el ancho -->
        <div class="form-container">
            <h2>Crear Usuario</h2>
            <p class="subtitle">Ingrese los datos del nuevo usuario</p>
            
            <form action="../../backend/administrador/subir_usuario.php" method="post">

                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre(s)</label>
                        <input 
                            type="text" id="nombre" name="nombre" maxlength="32" minlength="2"
                            pattern="^[A-Za-z]+( [A-Za-z]+)?$"
                            title="Tu nombre no puede llevar números o caracteres especiales, solo letras y espacios."
                            required placeholder="Ingresa tu nombre">
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido(s)</label>
                        <input 
                            type="text" id="apellido" name="apellido" maxlength="32" minlength="2"
                            pattern="^[A-Za-z]+( [A-Za-z]+)?$"
                            title="Tu apellido no puede llevar números o caracteres especiales, solo letras y espacios."
                            required placeholder="Ingresa tu apellido">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input 
                            type="email" id="email" name="email" maxlength="64" minlength="7"
                            placeholder="Ingresa tu correo"
                            required
                            class="<?= $corre_exitente ? 'input-error' : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input 
                            type="number" id="telefono" name="telefono"
                            placeholder="Ingresa tu número telefónico"
                            pattern="^[0-9]{6,15}$"
                            title="Número telefónico no válido. Solo 10 caracteres."
                            minlength="10" maxlength="10" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contrasena">Contraseña</label>
                        <input 
                            type="password" id="contrasena" name="contrasena"
                            placeholder="Ingresa tu contraseña" minlength="6"
                            title="Mínimo 6 caracteres" required>
                    </div>
                    <div class="form-group">
                        <label for="conf_contrasena">Confirmar contraseña</label>
                        <input 
                            type="password" id="conf_contrasena" name="conf_contrasena"
                            placeholder="Confirma tu contraseña" minlength="6"
                            title="Mínimo 6 caracteres" required>
                        <span id="mensaje_err">Las contraseñas no coinciden</span> 
                    </div>
                    <div class="form-group cedula-grupo">
                        <label for="cedula_pc">Cédula</label>
                        <input 
                            type="number" id="cedula_pc" name="cedula"
                            placeholder="Ingresa tu cédula"
                            pattern="^[0-9]{6,15}$"
                            title="Solo números, entre 6 y 15 dígitos" required>
                    </div>
                </div>

                <!-- Esto solamente es para resolución de celular -->
                <div class="form-group cedula-grupo2">
                    <label for="cedula_movil">Cédula</label>
                    <input 
                        type="number" id="cedula_movil" name="cedula"
                        placeholder="Ingresa tu cédula"
                        pattern="^[0-9]{6,15}$"
                        title="Solo números, entre 6 y 15 dígitos" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <select id="rol" name="rol" required>
                            <option value="" disabled selected>Seleccione rol</option>
                            <option value="administrador">Administrador</option>
                            <option value="visualizador">Visualizador</option>
                            <option value="auditor">Auditor</option>
                            <option value="documentador">Documentador</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="area">Área</label>
                        <select id="area" name="area" required>
                            <option value="" disabled selected>Seleccione área</option>
                            <option value="logistica">Logística</option>
                            <option value="contabilidad">Contabilidad</option>
                            <option value="administracion">Administración</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-crear" id="btn_backend">Crear</button>
                </div>
            </form>
        </div>
    </div>
</section>

            <script src="../../../componentes/js/log/coincidir_contraseña.js"></script>
    </main>
    <?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>
</body>
</html>
