<?php

require '../../backend/login/validar_token.php';



if ($mostrar_form) {
  

?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../../../componentes/img/logopng.png" type="image/x-icon">
  <link rel="stylesheet" href="../../../componentes/css/form_cambio.css">
  <title>Recuperar contraseña</title>
</head>
<body>
  <main id="cuerpo">
    <form id="formulario" action="../../backend/login/cambio_contraseña.php" method="POST" novalidate>
      <h1>Recuperar contraseña</h1>
      <p>Ingrese una contraseña nueva</p>

      <label for="nueva_contrasena">Nueva contraseña</label>
      <input type="password" name="contrasena" id="contrasena" maxlength="16" minlength="8"
        pattern="[a-zA-Z0-9]{8,16}" required
        title="Solo letras y números. De 8 a 16 caracteres.">
      
      <label for="confirmar_contrasena">Confirmar contraseña</label>
      <input type="password" name="conf_contrasena" id="conf_contrasena" maxlength="16" minlength="8"
        pattern="[a-zA-Z0-9]{8,16}" required
        title="Debe coincidir con la contraseña anterior.">

        <span id="mensaje_err">Las contraseñas no coinciden</span>
       
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <input type="hidden" name="correo" value="<?php echo htmlspecialchars($correo); ?>">
      <button type="submit" id="btn_backend">Cambiar</button>
    </form>
  </main>

  <script src="../../../componentes/js/log/coincidir_contraseña.js"></script>
</body>
</html>

    <?php
    } else {
        echo "El enlace es inválido o ha expirado.";
    }

?>
