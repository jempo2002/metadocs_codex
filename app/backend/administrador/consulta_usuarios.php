<?php

require_once '../../helpers/conexion_bd.php';

$sentencia = "SELECT usuarios.nombres, usuarios.correo, usuarios.rol, area_acceso.nombre AS area FROM usuarios JOIN area_acceso ON usuarios.id_area = area_acceso.id_area WHERE estado = 'activo';";

$resultado = $conexion_metadocs->query($sentencia);


$cantidad_tabla = 10;

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $cantidad_tabla;

$sql_paginacion = "SELECT usuarios.nombres, usuarios.correo, usuarios.rol, area_acceso.nombre AS area FROM usuarios JOIN area_acceso ON usuarios.id_area = area_acceso.id_area WHERE estado = 'activo' LIMIT $inicio, $cantidad_tabla ";

$resultado_paginacion = mysqli_query($conexion_metadocs, $sql_paginacion);

$usuarios = mysqli_fetch_all($resultado_paginacion, MYSQLI_ASSOC);

$sql_total = "SELECT COUNT(*) as total FROM documentos WHERE estado_retencion ='archivado';";
$result_total = mysqli_query($conexion_metadocs, $sql_total);
$total_filas = mysqli_fetch_assoc($result_total)['total'];

$total_paginas = ceil($total_filas / $cantidad_tabla);


$conexion_metadocs->close();

?>