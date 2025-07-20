<?php

require_once '../../helpers/info_usuario.php';
require_once '../../helpers/conexion_bd.php';

$area_usuarios = $usuario['id_area'];


$sentencia_expediente = "SELECT id_expediente, nombre,  apellidos ,descripcion, nombres AS nombre_autor, expedientes.fecha_creacion FROM `expedientes` JOIN usuarios ON expedientes.autor = usuarios.id_usuario WHERE expedientes.estado = 'revision'  AND expedientes.id_area = '$area_usuarios';";

$resultado_expediente = $conexion_metadocs->query($sentencia_expediente);


$sentencia_documento = "SELECT 
    id_documento, 
    titulo, 
    categoria, 
    nombres,
    apellidos, 
    documentos.fecha_creacion, 
    tipo, 
    expedientes.nombre AS expediente FROM documentos JOIN usuarios ON documentos.autor = usuarios.id_usuario  JOIN retencion ON documentos.id_retencion = retencion.categoria JOIN expedientes ON expedientes.id_expediente = documentos.id_expediente  WHERE documentos.estado = 'revision' AND documentos.id_area = '$area_usuarios';";

$resultado_documento = $conexion_metadocs->query($sentencia_documento);



?>