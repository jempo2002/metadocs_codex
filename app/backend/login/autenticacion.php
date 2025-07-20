<?php


session_start(); 

require_once '../../helpers/conexion_bd.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST'){

    echo "tu peticion ha sido rechazada";

    exit;

}else{

    if(!isset($_POST['gmail']) && !isset($_POST['contrasena'])){
        
        echo "no se puede ejecutar la operación púes faltan datos";
    
    }else{
        
        $correo_ingresado = strtolower(filter_input(INPUT_POST, 'gmail', FILTER_SANITIZE_EMAIL));
        $contraseña_ingresado = md5($_POST["contrasena"]);


        $sentencia = $conexion_metadocs->prepare("SELECT * FROM `usuarios` WHERE correo=? AND estado = 'activo'");
        $sentencia->bind_param("s", $correo_ingresado);
        $sentencia->execute();
        $resultado = $sentencia->get_result();

        $autenticacion = $resultado -> fetch_object();

        if (!$autenticacion) {
            
            $_SESSION['denegado'] = 'Acceso denegado, no existes en el sistema';
            
            header('Location: ../../../login.php'); 
            
            exit();
        }else{
            
            if($contraseña_ingresado != $autenticacion->contraseña) {

                $_SESSION['error'] = 'contraseña incorrecta';
                
                header('Location: ../../../login.php');
                
                exit();

            } else {

                $rol = $autenticacion->rol;
                $area = $autenticacion ->id_area;
                $id_log = $autenticacion -> id_usuario;
                
                $_SESSION['id_log'] =$id_log;
                $_SESSION['rol'] =$rol;
                $_SESSION['area'] =$area;
            
            


                switch ($rol) {
                    
                    case 'administrador':
                        
                        header("Location: ../../vistas/admin/panel_control.php");
                        break;
                    case 'visualizador':
                        header("Location: visualizador.php");
                        break;
                    case 'documentador':
                        header("Location: ../../vistas/documentador/documentador_inicio.php");
                        break;
                    case 'auditor':
                        header("Location: ../../vistas/auditor/auditor_inicio.php");
                        break;
                    default:
                        header("Location:../../../login.php");
                }
                exit;

            }
        }

    
    }



}




$conexion_metadocs->close();


?>

