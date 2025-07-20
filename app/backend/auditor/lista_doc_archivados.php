<?php
require_once '../../helpers/conexion_bd.php';

$sql = "SELECT 
            documentos.titulo,
            retencion.categoria,
            documentos.tipo,
            documentos.fin_retencion
        FROM 
            documentos
        JOIN 
            retencion ON documentos.id_retencion = retencion.id_retencion
        WHERE 
            documentos.estado_retencion = 'archivado'";

$resultado = $conexion_metadocs->query($sql);

$documentos_archivados = [];
if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $documentos_archivados[] = $fila;
    }
}


$cantidad_tabla = 10;

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $cantidad_tabla;

$sql_paginacion = "SELECT 
            documentos.titulo,
            retencion.categoria,
            documentos.tipo,
            documentos.fin_retencion
        FROM 
            documentos
        JOIN 
            retencion ON documentos.id_retencion = retencion.id_retencion
        WHERE 
            documentos.estado_retencion = 'archivado' LIMIT $inicio, $cantidad_tabla";
$resultado = mysqli_query($conexion_metadocs, $sql_paginacion);


$documentos_archivados = mysqli_fetch_all($resultado, MYSQLI_ASSOC);


$sql_total = "SELECT COUNT(*) as total FROM documentos WHERE estado_retencion ='archivado';";
$result_total = mysqli_query($conexion_metadocs, $sql_total);
$total_filas = mysqli_fetch_assoc($result_total)['total'];

$total_paginas = ceil($total_filas / $cantidad_tabla);


$conexion_metadocs->close();
?>