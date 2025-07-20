<?php

require_once '../../helpers/conexion_bd.php';
require_once '../../helpers/info_usuario.php';

$id_area = $usuario['id_area'];


if($_SERVER['REQUEST_METHOD'] != 'POST'){

    echo 'no tienes acceso a esta vista';

}else{

    function registrarAuditoria($conexion, $id_area, $accion, $entidad, $entidad_id, $id_usuario,$rol) {
    $sql_auditoria = "INSERT INTO pista_auditoria (id_area, accion, fecha_accion, entidad, entidad_id, id_usuario,rol) 
                      VALUES (?, ?, NOW(), ?, ?, ?,?)";
    
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


    $categoria = $_POST['tipo'];
    $responsable = $_POST['responsable_display'];
    $expediente = $_POST['expediente_display'];
    $descripcion = $_POST['descripcion'];

    $id_usuario = $usuario['id_usuario'];


        $mensaje_json = json_encode([
        'categoria' => $categoria,
        'expediente_destinado' => $expediente,
        'descripcion' => $descripcion,

    ], JSON_UNESCAPED_UNICODE);

    
    $id_usuario_escaped = mysqli_real_escape_string($conexion_metadocs, $id_usuario);
    $tipo_actividad_escaped = mysqli_real_escape_string($conexion_metadocs, 'solicitud_documento');
    $mensaje_escaped = mysqli_real_escape_string($conexion_metadocs, $mensaje_json);

    
    $sql_actividad = "INSERT INTO actividades (id_usuario, tipo_actividad, mensaje, fecha_creacion, usuario_destinatario) 
        VALUES ('$id_usuario_escaped', '$tipo_actividad_escaped', '$mensaje_escaped', NOW(), '$responsable')";


if (mysqli_query($conexion_metadocs, $sql_actividad)) {
    
        $id_solicitud = mysqli_insert_id($conexion_metadocs);

         registrarAuditoria($conexion_metadocs, $id_area, 'solicito', 'documento', $id_solicitud, $id_usuario, "auditor");
        
        header('Location: ../../vistas/auditor/solicitar_documento.php?msg=solicitud_enviada');
        
        
    } else {
        // Error en la consulta
        header('Location: ../../vistas/auditor/solicitar_documento.php?msg=error_en_la_consulta');
    }
    
    // Cerrar conexión
    mysqli_close($conexion_metadocs);
}








?>