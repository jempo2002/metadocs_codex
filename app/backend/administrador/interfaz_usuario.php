<?php

require_once '../../helpers/conexion_bd.php';
require_once '../../helpers/info_usuario.php';

$rol_usuario = $usuario['rol'];

$correo_usuario = $usuario["correo"];

$sentencia = "SELECT usuarios.nombres, usuarios.apellidos, usuarios.correo, usuarios.rol, usuarios.cedula, usuarios.telefono, area_acceso.nombre AS area FROM usuarios JOIN area_acceso ON usuarios.id_area = area_acceso.id_area WHERE correo = '$correo_usuario'";

$resultado = $conexion_metadocs->query($sentencia);

if ($resultado && $resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    //echo $fila["nombres"] . " - ". $fila["apellidos"] . "-" . $fila["cedula"] . " - " . $fila["telefono"] ." - ". $fila["correo"] ." - " . $fila["rol"] ." - " . $fila["cedula"] ." - " . $fila["area"];
} else {
    echo "No se encontró el usuario.";
}

$mensaje = ''; 
if ($rol_usuario == 'administrador') {
    $mensaje = 'Tu rol dentro del sistema es fundamental y abarca varias responsabilidades clave. Principalmente, serás el encargado de la creación y gestión de usuarios, asegurando que solo el personal autorizado tenga acceso. Además, tendrás la capacidad de visualizar y analizar las estadísticas de los documentos del sistema, lo que te permitirá comprender patrones de uso y rendimiento. Finalmente, tu función incluirá la recepción y procesamiento de reportes, crucial para el seguimiento y la toma de decisiones informadas. En resumen, tu posición es vital para mantener la integridad, el rendimiento y la transparencia operativa del sistema';

} else if($rol_usuario == 'documentador'){
    $mensaje = "Como parte de tus responsabilidades, te encargarás de subir archivos y expedientes específicos que el auditor te solicite, asegurando su correcta incorporación al sistema. También tendrás la capacidad de consultar y verificar estos documentos una vez cargados. Además, será tu función preparar y presentar reportes detallados al administrador sobre la información manejada";
}else if ($rol_usuario == 'auditor'){

    $mensaje = 'Como parte de tus responsabilidades en el sistema, te encargarás de solicitar y autorizar documentos y expedientes. Podrás consultar la pista de auditoría para el seguimiento de cambios y acciones, así como acceder al archivo histórico para referencia y consulta. Asimismo, tu función incluirá la elaboración de reportes dirigidos al administrador';
}

$conexion_metadocs->close();

?>

