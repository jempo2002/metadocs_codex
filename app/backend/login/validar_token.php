<?php
session_start();
require_once '../../helpers/conexion_bd.php';

$token = $_GET['token'] ?? '';

if(!$token){
    
    echo "Falta el token en la URL.";

}else{
    
    
    $sentencia = $conexion_metadocs->prepare("SELECT id_usuario FROM contraseña_resets WHERE token = ? AND expira_en > NOW()");
    $sentencia->bind_param("s", $token);
    $sentencia->execute();
    $resultado = $sentencia->get_result();

    if ($resultado->num_rows > 0) {
        $resetRequest = $resultado->fetch_assoc();
        $usuarioId = $resetRequest['id_usuario'];
        
        $sentencia_dos = $conexion_metadocs->prepare("SELECT correo FROM usuarios WHERE id_usuario = ?");

        $sentencia_dos->bind_param("i", $usuarioId);
        $sentencia_dos ->execute();
        $resultado_dos = $sentencia_dos->get_result();

        if($resultado_dos -> num_rows > 0){
            $resetrequest = $resultado_dos->fetch_assoc();
            $correo = $resetrequest["correo"];
        }

        $mostrar_form  = true;

    }else{
        $mostrar_form  = false;
    }
}



?>