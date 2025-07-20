<?php

session_start();
$correo_inexistente = $_SESSION['no_existe'] ?? null;
unset($_SESSION['no_existe']); 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación</title>
    <link rel="icon" href="../../../componentes/img/logopng.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../componentes/css/recuperacion.css">
</head>
<body>
    <main id="cuerpo">
   
    <form action="../../backend/login/recuperacion_cont.php" method="post">
        <h1>Recuperar contraseña</h1>
       
        <p>Ingresa la dirección de correo electrónico asociada a tu cuenta. Te llegará un mensaje con las instrucciones necesarias para restablecer tu contraseña.</p>

        <label for="correo">Correo</label>
        <input type="email" name="gmail" id="gmail"   inputmode="email" maxlength="64" minlength="7" pattern="[a-z0-9\.\-\]+[@]+[a-z0-9\-\]+[\.]+[a-z0-9]{2,}$" title="Parece que no digitaste una direccion de email" placeholder="Ingrese su correo" required   class="<?= $correo_inexistente ? 'input-error' : '' ?>">

        <?php if ($correo_inexistente): ?>
      
            <p class="error_mensaje"><?= htmlspecialchars($correo_inexistente) ?></p>
       
        <?php endif; ?>
     

        <button type="submit">ENVIAR</button>
        <p class="footer-texto">
      ¿Recuerdas la contraseña? <a href="../../../login.php">inicia sesión</a>
        </p>

    </form>
    
    </main>
</body>
</html>