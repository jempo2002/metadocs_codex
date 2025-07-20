<?php
require_once '../../helpers/conexion_bd.php';
require_once '../../helpers/info_usuario.php';

$id_usuario = $usuario['id_usuario'];
$id_area = $usuario['id_area'];

$id_expediente = $_POST['datos_expediente'] ?? null;
$id_documento = $_POST['datos_documento'] ?? null;
$motivo_rechazo = $_POST['motivo_rechazo'] ?? null;

// FUNCIÓN PARA OBTENER NOMBRE COMPLETO DEL USUARIO
function obtenerNombreCompletoUsuario($conexion, $identificador_usuario) {
    // Si ya es un nombre completo (contiene espacio), devolverlo tal como está
    if (strpos($identificador_usuario, ' ') !== false) {
        return $identificador_usuario;
    }
    
    // Si es un ID numérico, buscar en la base de datos
    if (is_numeric($identificador_usuario)) {
        $sql = "SELECT nombres, apellidos FROM usuarios WHERE id_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $identificador_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($row = $resultado->fetch_assoc()) {
            $stmt->close();
            return trim($row['nombres'] . ' ' . $row['apellidos']);
        }
        $stmt->close();
    }
    
    // Si no es numérico, asumir que es un nombre y buscar por nombres
    $sql = "SELECT nombres, apellidos FROM usuarios WHERE CONCAT(nombres, ' ', apellidos) = ? OR nombres = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $identificador_usuario, $identificador_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($row = $resultado->fetch_assoc()) {
        $stmt->close();
        return trim($row['nombres'] . ' ' . $row['apellidos']);
    }
    $stmt->close();
    
    // Si no se encuentra, devolver el identificador original
    return $identificador_usuario;
}

// FUNCIÓN PARA REGISTRAR AUDITORÍA
function registrarAuditoria($conexion, $id_area, $accion, $entidad, $entidad_id, $id_usuario, $rol) {
    $sql_auditoria = "INSERT INTO pista_auditoria (id_area, accion, fecha_accion, entidad, entidad_id, id_usuario, rol) 
                      VALUES (?, ?, NOW(), ?, ?, ?, ?) ";
    
    if ($stmt_auditoria = $conexion->prepare($sql_auditoria)) {
        $stmt_auditoria->bind_param('issiis', $id_area, $accion, $entidad, $entidad_id, $id_usuario,$rol);
        
        if ($stmt_auditoria->execute()) {
            $stmt_auditoria->close();
            return true;
        } else {
            error_log("Error al insertar auditoría: " . $stmt_auditoria->error);
            $stmt_auditoria->close();
            return false;
        }
    } else {
        error_log("Error al preparar consulta de auditoría: " . $conexion->error);
        return false;
    }
}

function aprobarExpediente($conexion, $id_expediente, $usuario_destinatario, $nombre_expediente, $id_usuario, $id_area) {
    $sql_aprobar = "UPDATE `expedientes` SET `estado` = 'aprobado' WHERE `id_expediente` = ?;";

    if ($sentencia = $conexion->prepare($sql_aprobar)) {
        $sentencia->bind_param('i', $id_expediente);

        if ($sentencia->execute()) {
            $sentencia->close();

            //registrar auditoria 
            
            registrarAuditoria($conexion, $id_area, 'aprobó', 'expediente', $id_expediente, $id_usuario, "auditor");

            // Obtener el nombre completo del usuario destinatario
            $usuario_destinatario_completo = obtenerNombreCompletoUsuario($conexion, $usuario_destinatario);

            // Estructura del mensaje para expediente aprobado
            $mensaje_data = [
                'texto' => "Tu expediente '" . $nombre_expediente . "' fue aprobado con éxito",
                'titulo_expediente' => $nombre_expediente
            ];
            
            $mensaje_json = json_encode($mensaje_data, JSON_UNESCAPED_UNICODE);
            $tipo_actividad = 'expediente_aprobado';

            $sql_actividad = "INSERT INTO actividades (id_usuario, tipo_actividad, mensaje, fecha_creacion, usuario_destinatario) 
                                VALUES (?, ?, ?, NOW(), ?)";

            if ($stmt_actividad = $conexion->prepare($sql_actividad)) {
                $stmt_actividad->bind_param('isss', $id_usuario, $tipo_actividad, $mensaje_json, $usuario_destinatario_completo);
                
                if (!$stmt_actividad->execute()) {
                    error_log("Error al insertar actividad: " . $stmt_actividad->error);
                    $stmt_actividad->close();
                    return false;
                }
                $stmt_actividad->close();
                return true;
            } else {
                error_log("Error al preparar consulta de actividad: " . $conexion->error);
                return false;
            }

        } else {
            error_log("Error al ejecutar la consulta: " . $sentencia->error);
            return false;
        }
        $sentencia->close();
    } else {
        error_log("Error al preparar la consulta: " . $conexion->error);
        return false;
    }
}

function aprobarDocumento($conexion, $id_documento, $usuario_destinatario, $titulo, $categoria, $expediente, $id_usuario, $id_area){
    $sql_aprobar = "UPDATE `documentos` SET `estado` = 'aprobado' WHERE `documentos`.`id_documento` = ?;";
    
    if($sentencia = $conexion->prepare($sql_aprobar)){
        $sentencia->bind_param('i', $id_documento);
        
        if ($sentencia->execute()) {
            $sentencia->close();


              registrarAuditoria($conexion, $id_area, 'aprobó', 'documento', $id_documento, $id_usuario,"auditor");

            // Obtener el nombre completo del usuario destinatario
            $usuario_destinatario_completo = obtenerNombreCompletoUsuario($conexion, $usuario_destinatario);

            // Estructura del mensaje para documento aprobado
            $mensaje_data = [
                'texto' => "Tu documento '" . $titulo . "' fue aprobado con éxito",
                'titulo_documento' => $titulo,
                'categoria' => $categoria,
                'expediente_destino' => $expediente
            ];
            
            $mensaje_json = json_encode($mensaje_data, JSON_UNESCAPED_UNICODE);
            $tipo_actividad = 'documento_aprobado';

            $sql_actividad = "INSERT INTO actividades (id_usuario, tipo_actividad, mensaje, fecha_creacion, usuario_destinatario) 
                                VALUES (?, ?, ?, NOW(), ?)";

            if ($stmt_actividad = $conexion->prepare($sql_actividad)) {
                $stmt_actividad->bind_param('isss', $id_usuario, $tipo_actividad, $mensaje_json, $usuario_destinatario_completo);
                
                if (!$stmt_actividad->execute()) {
                    error_log("Error al insertar actividad: " . $stmt_actividad->error);
                    $stmt_actividad->close();
                    return false;
                }
                $stmt_actividad->close();
                return true;
            } else {
                error_log("Error al preparar consulta de actividad: " . $conexion->error);
                return false;
            }

        } else {
            error_log("Error al ejecutar la consulta: " . $sentencia->error);
            $sentencia->close();
            return false;
        }

    } else {
        error_log("Error al preparar la consulta: " . $conexion->error);
        return false;
    }
}

function rechazarExpediente($conexion, $id_expediente, $usuario_destinatario, $nombre_expediente, $motivo_rechazo, $id_usuario, $id_area){
    $sql_rechazar = "UPDATE `expedientes` SET `estado` = 'rechazado' WHERE `id_expediente` = ?;";

    if($sentencia = $conexion->prepare($sql_rechazar)){
        $sentencia->bind_param('i', $id_expediente);

        if ($sentencia->execute()) {
            $sentencia->close();


             registrarAuditoria($conexion, $id_area, 'rechazó', 'expediente', $id_expediente, $id_usuario,"auditor");

            // Obtener el nombre completo del usuario destinatario
            $usuario_destinatario_completo = obtenerNombreCompletoUsuario($conexion, $usuario_destinatario);

            // Estructura del mensaje para expediente rechazado
            $mensaje_data = [
                'texto' => "Tu expediente '" . $nombre_expediente . "' fue rechazado",
                'motivo' => $motivo_rechazo,
                'titulo_expediente' => $nombre_expediente
            ];
            
            $mensaje_json = json_encode($mensaje_data, JSON_UNESCAPED_UNICODE);
            $tipo_actividad = 'expediente_rechazado';

            $sql_actividad = "INSERT INTO actividades (id_usuario, tipo_actividad, mensaje, fecha_creacion, usuario_destinatario) 
                                VALUES (?, ?, ?, NOW(), ?)";

            if ($stmt_actividad = $conexion->prepare($sql_actividad)) {
                $stmt_actividad->bind_param('isss', $id_usuario, $tipo_actividad, $mensaje_json, $usuario_destinatario_completo);
                
                if (!$stmt_actividad->execute()) {
                    error_log("Error al insertar actividad: " . $stmt_actividad->error);
                    $stmt_actividad->close();
                    return false;
                }
                $stmt_actividad->close();
                return true;
            } else {
                error_log("Error al preparar consulta de actividad: " . $conexion->error);
                return false;
            }

        } else {
            error_log("Error al ejecutar la consulta: " . $sentencia->error);
            return false;
        }
        $sentencia->close();
    } else {
        error_log("Error al preparar la consulta: " . $conexion->error);
        return false;
    }
}

function rechazarDocumento($conexion, $id_documento, $usuario_destinatario, $titulo, $categoria, $expediente, $motivo_rechazo, $id_usuario, $id_area) {
    // Iniciar transacción
    $conexion->begin_transaction();
    
    try {
        // Primero obtener la ruta del archivo antes de actualizar
        $sql_obtener_path = "SELECT path FROM documentos WHERE id_documento = ?";
        $stmt_path = $conexion->prepare($sql_obtener_path);
        
        if (!$stmt_path) {
            throw new Exception("Error al preparar consulta para obtener path: " . $conexion->error);
        }
        
        $stmt_path->bind_param('i', $id_documento);
        $stmt_path->execute();
        $resultado = $stmt_path->get_result();
        $documento = $resultado->fetch_assoc();
        
        if (!$documento) {
            throw new Exception("Documento no encontrado");
        }
        
        $ruta_archivo = $documento['path'];
        $stmt_path->close();
        
        // Actualizar el estado del documento a rechazado
        $sql_rechazar = 'UPDATE documentos SET estado = "rechazado" WHERE id_documento = ?';
        $sentencia = $conexion->prepare($sql_rechazar);
        
        if (!$sentencia) {
            throw new Exception("Error al preparar la consulta de actualización: " . $conexion->error);
        }
        
        $sentencia->bind_param('i', $id_documento);
        
        if (!$sentencia->execute()) {
            throw new Exception("Error al ejecutar la consulta de actualización: " . $sentencia->error);
        }
        
        $sentencia->close();

        // Obtener el nombre completo del usuario destinatario
        $usuario_destinatario_completo = obtenerNombreCompletoUsuario($conexion, $usuario_destinatario);

        // Estructura del mensaje para documento rechazado
        $mensaje_data = [
            'texto' => "Tu documento '" . $titulo . "' fue rechazado",
            'motivo' => $motivo_rechazo,
            'titulo_documento' => $titulo,
            'categoria' => $categoria,
            'expediente_destino' => $expediente
        ];
        
        $mensaje_json = json_encode($mensaje_data, JSON_UNESCAPED_UNICODE);
        $tipo_actividad = 'documento_rechazado';

        $sql_actividad = "INSERT INTO actividades (id_usuario, tipo_actividad, mensaje, fecha_creacion, usuario_destinatario) 
                            VALUES (?, ?, ?, NOW(), ?)";

        $stmt_actividad = $conexion->prepare($sql_actividad);
        if (!$stmt_actividad) {
            throw new Exception("Error al preparar consulta de actividad: " . $conexion->error);
        }

        $stmt_actividad->bind_param('isss', $id_usuario, $tipo_actividad, $mensaje_json, $usuario_destinatario_completo);

        if (!$stmt_actividad->execute()) {
            throw new Exception("Error al insertar actividad: " . $stmt_actividad->error);
        }

        $stmt_actividad->close();
        
        // Eliminar el archivo físico si existe
        if (!empty($ruta_archivo) && file_exists($ruta_archivo)) {
            if (!unlink($ruta_archivo)) {
                // Log del error pero no fallar la transacción
                error_log("Advertencia: No se pudo eliminar el archivo físico: " . $ruta_archivo);
            }
        }

         if (!registrarAuditoria($conexion, $id_area, 'rechazó', 'documento', $id_documento, $id_usuario, "auditor")) {
            throw new Exception("Error al registrar auditoría");
        }
        
        // Confirmar la transacción
        $conexion->commit();
        return true;
        
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conexion->rollback();
        error_log("Error en rechazarDocumento: " . $e->getMessage());
        return false;
    }
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo "tu peticion ha sido rechazada ";
}else{

    switch($_POST['accion']){

        case 'aprobar_expediente':
            $usuario_destinatario = $_POST['usuario_destinatario'];
            $nombre_expediente = $_POST['nombre_expediente'];
            
            if(aprobarExpediente($conexion_metadocs, $id_expediente, $usuario_destinatario, $nombre_expediente, $id_usuario, $id_area)){
                header("Location: ../../vistas/auditor/recibir_documentos.php?sucess=true");
            }else{
                header("Location: ../../vistas/auditor/recibir_documentos.php?error=true");
            }
            break;

        case 'aprobar_documento':
            $usuario_destinatario = $_POST['usuario_destinatario'];
            $titulo = $_POST['titulo'];
            $categoria = $_POST['categoria'];
            $expediente = $_POST['expediente'];
            
            if(aprobarDocumento($conexion_metadocs, $id_documento, $usuario_destinatario, $titulo, $categoria, $expediente, $id_usuario, $id_area)){
                header("Location: ../../vistas/auditor/recibir_documentos.php?sucess=true");
            }else{
                header("Location: ../../vistas/auditor/recibir_documentos.php?error=true");
            }
            break;

        case 'rechazar_expediente':
            $usuario_destinatario = $_POST['usuario_destinatario'];
            $nombre_expediente = $_POST['nombre_expediente'];
            
            if(rechazarExpediente($conexion_metadocs, $id_expediente, $usuario_destinatario, $nombre_expediente, $motivo_rechazo, $id_usuario, $id_area)){
                header("Location: ../../vistas/auditor/recibir_documentos.php?sucess=true");
            }else{
                header("Location: ../../vistas/auditor/recibir_documentos.php?error=true");
            }
            break;

        case 'rechazar_documento':
            $usuario_destinatario = $_POST['usuario_destinatario'];
            $titulo = $_POST['titulo'];
            $categoria = $_POST['categoria'];
            $expediente = $_POST['expediente'];
            
            if (rechazarDocumento($conexion_metadocs, $id_documento, $usuario_destinatario, $titulo, $categoria, $expediente, $motivo_rechazo, $id_usuario,  $id_area)) {
                header("Location: ../../vistas/auditor/recibir_documentos.php?sucess=true");
            }else{
                header("Location: ../../vistas/auditor/recibir_documentos.php?error=true");
            }
            break;
    }
}
?>