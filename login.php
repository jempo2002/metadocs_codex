<?php
session_start();
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']); 

$denegado = $_SESSION['denegado'] ?? null;
unset($_SESSION['denegado']);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>inicio</title>
    
    <link rel="stylesheet" href="componentes/css/login.css">
    <link rel="icon" href="componentes/img/logopng.png" type="image/x-icon">
</head>
<body>
    <main id="cuerpo">
        <figure id="img_login">
            <img src="componentes/img/OIG2 (1).jpeg" alt="imagen login">
        </figure>

        <form action="app/backend/login/autenticacion.php" method="post" id="form">

            <figure>
                <img src="componentes/img/Imagen de WhatsApp 2025-05-01 a las 11.52.47_deffc20c.jpg" alt="logo">
            </figure>

            <p id="subtitulo">Inicia sesión</p>



            
            <label for="gmail">Correo</label>
            <input type="email" name="gmail" id="gmail" inputmode="email" maxlength="64" minlength="7" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
            title="Parece que no digitaste una dirección de email" placeholder="Ingrese su correo" required
            class="<?= $denegado ? 'input-error' : '' ?>">

            <?php if ($denegado): ?>
                        <p class="error_mensaje" id="error_mensaje"><?= htmlspecialchars($denegado) ?></p>
            <?php endif; ?>

            <label for="contrasena" id="label_contraseña">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" maxlength="16" minlength="8" pattern="[a-zA-Z0-9]{8,16}" title="solo números y letras, pueden ser mayúsculas o minúsculas; mínimo 8 hasta máximo 16 caracteres" placeholder="Ingrese su contraseña" required class="<?= $error ? 'input-error' : '' ?>">

            <?php if ($error): ?>
                        <p class="error_mensaje" id="error_mensaje"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <div id="recuperacion">
                <div id="checkbox">
                    <input type="checkbox" name="recordar" id="recordar">Recordar
                </div>
                <a href="app/vistas/log/recuperacion.php">¿Olvidaste tu contraseña?</a>
            </div>

            <div id="btn_login"> 
                <button id="btn_iniciar" type="submit">Iniciar</button>
            </div>
        </form>
    </main>
</body>
</html>