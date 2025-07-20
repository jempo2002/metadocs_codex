<?php
session_start();

require_once '../../helpers/conexion_bd.php';


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "tu peticion ha sido rechazada";

}else{
    echo "si llego";
    
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $contraseña = isset($_POST['contrasena']) ? md5(trim($_POST['contrasena'])) : '';
    $cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
    $rol = isset($_POST['rol']) ? trim($_POST['rol']) : '';
    $area = isset($_POST['area']) ? trim($_POST['area']) : '';


    if (empty($nombre) || empty($apellido) || empty($email) || empty($telefono) || empty($contraseña) || empty($cedula) || empty($rol) || empty($area)) {
        die("Por favor complete todos los campos.");
    }

    $conexion_metadocs->begin_transaction();

    try {
        // Verificar si el correo ya está registrado
        $sql_verificar_email = "SELECT correo FROM usuarios WHERE correo = ?";
        $sentencia = $conexion_metadocs->prepare($sql_verificar_email);
        $sentencia->bind_param("s", $email);
        $sentencia->execute();
        $resultado = $sentencia->get_result();

        if ($resultado->num_rows > 0) {
            
            $_SESSION['correo_existente'] = "El correo electrónico ya está registrado.";
            header('Location: ../../vistas/admin/creacion_usuario.php');
            
            exit();
        }

        
        $sql_buscar_area = "SELECT id_area FROM area_acceso WHERE nombre = ?";
        $sentencia_area = $conexion_metadocs->prepare($sql_buscar_area);
        $sentencia_area->bind_param("s", $area);
        $sentencia_area->execute();
        $resultado_area = $sentencia_area->get_result();


        echo $area;
        

        if ($resultado_area->num_rows === 0) {
            
            $_SESSION['error'] = "El área especificada no existe.";
            header('Location: ../../vistas/admin/creacion_usuario.php');
            
            exit();
        }

        $id_area = $resultado_area->fetch_assoc()['id_area'];

    
        $sql_usuario = "INSERT INTO usuarios (nombres, apellidos, correo, contraseña, cedula, telefono, rol, id_area) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
        $sentencia_usuario = $conexion_metadocs->prepare($sql_usuario);
        $sentencia_usuario->bind_param("sssssssi", $nombre, $apellido, $email, $contraseña, $cedula, $telefono, $rol, $id_area);
        $sentencia_usuario->execute();

        
        $conexion_metadocs->commit();
        $_SESSION['exito'] = 'Usuario creado con éxito';
        header('Location: ../../vistas/admin/creacion_usuario.php');
        
        exit();
    } catch (Exception $e) {
        
        $conexion_metadocs->rollback();
        $_SESSION['error'] = "Error al registrar los datos: " . $e->getMessage();
        header('Location: ../../vistas/admin/creacion_usuario.php');
        
        exit();
    }

    $conexion_metadocs->close();




}
?>