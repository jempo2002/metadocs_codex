<?php

require_once '../../helpers/conexion_bd.php';

$sentencia = "SELECT estado FROM documentos WHERE estado = 'aprobado' OR estado = 'revision'";
$resultado = $conexion_metadocs->query($sentencia);

$recuentos_aprobados = 0;
$recuentos_revision = 0;


if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        if ($fila['estado'] == 'aprobado') {
            $recuentos_aprobados++;
        } elseif ($fila['estado'] == 'revision') {
            $recuentos_revision++;
        }
    }

}

$sentencia_usuarios = "SELECT * FROM usuarios WHERE estado = 'activo'";
$resultado_usuarios = $conexion_metadocs->query($sentencia_usuarios);

$recuentos_usuarios = 0;

if ($resultado_usuarios) {
    while ($fila = $resultado_usuarios->fetch_assoc()) {
        $recuentos_usuarios++;
    }
}

$sentencia_archivados = "SELECT estado_retencion FROM documentos WHERE estado_retencion = 'archivado'";
$resultado_archivados = $conexion_metadocs->query($sentencia_archivados);
$recuentos_archivados = 0;
if ($resultado_archivados) {
    while ($fila = $resultado_archivados->fetch_assoc()) {
        $recuentos_archivados++;
    }
}


$conexion_metadocs->close();
?>
