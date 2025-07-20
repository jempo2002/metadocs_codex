<?php
session_start();
require_once '../../helpers/conexion_bd.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['recuperacion_iniciada'])) {
    echo "Error: acceso no autorizado o sesión inválida.";
    exit;
}else{

    $correo = $_SESSION['email'];


    // Limpiar sesión
    unset($_SESSION['email']);
    unset($_SESSION['recuperacion_iniciada']);

}




?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../../../componentes/img/logopng.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../componentes/css/correo_enviado.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correo enviado</title>
</head>
<body>
    <main id="cuerpo">
        <section id="correo_enviado">
            <form>
                <i class="bi bi-envelope-check"></i>
                <h2>Correo enviado</h2>
                <p>Hemos enviado un correo a <?php echo $correo; ?>, por favor, revisa tu bandeja de entrada para encontrar las instrucciones necesarias para restablecer tu contraseña.</p>

                <button onclick="window.open('https://mail.google.com/', '_blank')">
                    Abrir Gmail
                </button>

                <p>¿No recibiste un correo?<a href="../../app/controller/reenviar_correo.php"> Reenviar</a></p>

                <p>¿Correo incorrecto?<a href="../../vistas/log/recuperacion.php"> Cambiar correo</a></p>
            </form>
        </section>
    </main>
</body>
</html>