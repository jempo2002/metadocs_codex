<?php

require_once '../../helpers/conexion_bd.php';

// Asegurar que la respuesta sea JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST'){
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
} else {
    
    if (!isset($_POST['accion'])) {
        echo json_encode(['success' => false, 'error' => 'No se especificó una acción']);
        exit;
    }

    $correo = $_POST['correo'] ?? '';
    
    if (empty($correo)) {
        echo json_encode(['success' => false, 'error' => 'Correo no proporcionado']);
        exit;
    }

    function eliminar_usuario($conexion, $correo){
        $consulta = "UPDATE usuarios SET estado = 'inactivo' WHERE correo = ?";
        $sentencia = $conexion->prepare($consulta); 
        $sentencia->bind_param('s', $correo);

        if ($sentencia->execute()) {
            $sentencia->close();
            return ['success' => true, 'message' => 'Usuario eliminado correctamente'];
        } else {
            $error = $sentencia->error;
            $sentencia->close();
            return ['success' => false, 'error' => 'Error en la base de datos: ' . $error];
        }
    }
function editar_usuario($conexion, $correo_original) {

    if (!isset($_POST['nombre']) || !isset($_POST['correo_nuevo']) || 
        !isset($_POST['rol']) || !isset($_POST['area'])) {
        return ['success' => false, 'error' => 'Faltan datos requeridos'];
    }

    $nombre = $_POST['nombre'];
    $correo_nuevo = $_POST['correo_nuevo'];
    $rol = $_POST['rol'];
    $area = $_POST['area'];

    
    if (empty($nombre) || empty($correo_nuevo) || empty($rol) || empty($area)) {
        return ['success' => false, 'error' => 'Todos los campos son requeridos'];
    }

    // Verificar si el nuevo correo ya existe (solo si es diferente al correo original)
    if ($correo_nuevo != $correo_original) {
        $consulta_correo = "SELECT correo FROM usuarios WHERE correo = ?";
        $sentencia_correo = $conexion->prepare($consulta_correo);
        $sentencia_correo->bind_param("s", $correo_nuevo);
        $sentencia_correo->execute();
        $result_correo = $sentencia_correo->get_result();
        
        if ($result_correo->num_rows > 0) {
            $sentencia_correo->close();
            return ['success' => false, 'error' => 'El correo electrónico ya está en uso por otro usuario'];
        }
        $sentencia_correo->close();
    }

    // Consulta para obtener el id del área
    $consulta_area = "SELECT id_area FROM area_acceso WHERE nombre = ?";
    $sentencia_area = $conexion->prepare($consulta_area);
    $sentencia_area->bind_param("s", $area);
    $sentencia_area->execute();
    $result_area = $sentencia_area->get_result();
    
    if ($row_area = $result_area->fetch_assoc()) {
        $id_area = $row_area['id_area'];
        $sentencia_area->close();

        
        $consulta_editar = "UPDATE usuarios SET nombres = ?, correo = ?, rol = ?, id_area = ? WHERE correo = ?";
        $sentencia = $conexion->prepare($consulta_editar);
        $sentencia->bind_param("sssis", $nombre, $correo_nuevo, $rol, $id_area, $correo_original);

        if ($sentencia->execute()) {
            if ($sentencia->affected_rows > 0) {
                $sentencia->close();
                return ['success' => true, 'message' => 'Usuario editado correctamente'];
            } else {
                $sentencia->close();
                return ['success' => false, 'error' => 'No se encontró el usuario o no se realizaron cambios'];
            }
        } else {
            $error = $sentencia->error;
            $sentencia->close();
            return ['success' => false, 'error' => 'Error en la base de datos: ' . $error];
        }
    } else {
        $sentencia_area->close();
        return ['success' => false, 'error' => 'Área no encontrada'];
    }
}
        
    

    switch ($_POST['accion']) {
        case 'eliminar_usuario':
            $resultado = eliminar_usuario($conexion_metadocs, $correo);
            echo json_encode($resultado);
            break;


        case 'editar_usuario':
            $resultado = editar_usuario($conexion_metadocs, $correo) ;
            echo json_encode($resultado);
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
            break;
    }
}

?>