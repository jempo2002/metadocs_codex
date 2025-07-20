<?php
session_start(); 
require_once '../../helpers/conexion_bd.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    echo "Tu petición ha sido rechazada.";
    exit;
}else{

    $correo  = $_POST['correo'];
    $token = $_POST['token'];
    $contraseña_cambio = md5($_POST['conf_contrasena']);

    $sentencia = $conexion_metadocs->prepare("SELECT id_usuario FROM contraseña_resets WHERE token = ? AND expira_en > NOW()");
    $sentencia->bind_param("s", $token);
    $sentencia->execute();
    $resultado = $sentencia->get_result();

    if($resultado->num_rows == 0){
        echo "Token inválido o expirado.";
    } else {
        $sentencia_contraseña = $conexion_metadocs->prepare("UPDATE usuarios SET contraseña = ? WHERE correo = ?");
        $sentencia_contraseña->bind_param("ss", $contraseña_cambio, $correo);
        $sentencia_contraseña->execute();

        $sentencia_contraseña = $conexion_metadocs->prepare("DELETE FROM contraseña_resets WHERE token = ?");
        $sentencia_contraseña->bind_param("s", $token);
        $sentencia_contraseña->execute();

        // Aquí mostramos el HTML de éxito:
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Contraseña Cambiada</title>
            <link rel="stylesheet" href="/Metadocs_v2/componentes/css/cambio_contra.css" />
        </head>
        <body>
            <div class="contenedor">
                <div class="card">
                    <div class="image-placeholder">
                        <img src="/Metadocs_v2/componentes/img/Recupera.jpeg" alt="Cambio correctamente" />
                    </div>
                    <p class="mensage">Contraseña cambiada<br>¡Felicidades!</p>
                    <button type="button" class="ok-button" onclick="window.location.href='/Metadocs_v2/login.php'">Regresar</button>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit();
    }   
}
