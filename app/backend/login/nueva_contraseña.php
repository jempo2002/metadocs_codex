<?php

require_once '../../helpers/conexion_bd.php';
require_once '../../helpers/info_usuario.php';
if($_SERVER["REQUEST_METHOD"]=="POST"){

$correo_usuario = $usuario["correo"];
$contra_vieja = md5($_POST["contraseña_actual"]);
$contra_nueva = md5($_POST["contrasena"]);

$sentencia = "SELECT usuarios.contraseña FROM usuarios WHERE correo = '$correo_usuario'";

$resultado = $conexion_metadocs->query($sentencia);

$respuesta_contra = $resultado->fetch_assoc();

if ($resultado && $resultado->num_rows > 0) {

    if($contra_vieja == $respuesta_contra["contraseña"]){

        $contra_nueva = "UPDATE usuarios SET contraseña='$contra_nueva' WHERE correo = '$correo_usuario'" ;
        $res_contraseña = $conexion_metadocs->query($contra_nueva);

    if($res_contraseña){
        echo("la contraseña ha sido actualizada con exito");
        header("location: ../../vistas/admin/panel_control.php ");

    }else{
        echo("no se pudo actualizar la contraseña");
    }

} else{
    echo("tu contraseña actual es incorrecta");
}
}

$conexion_metadocs->close();
}
?>