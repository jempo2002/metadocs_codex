<?php
// Archivo: componentes/js/auditor/obtener_actividades.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir la conexión a la base de datos
require_once '../../helpers/conexion_bd.php'; // Ajusta la ruta según tu estructura

// Función para sanitizar entrada
function sanitizar($datos) {
    return htmlspecialchars(strip_tags(trim($datos)));
}

try {
    // Obtener parámetros de la URL
    $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
    $limite = isset($_GET['limite']) ? max(1, min(100, intval($_GET['limite']))) : 10;
    $busqueda = isset($_GET['busqueda']) ? sanitizar($_GET['busqueda']) : '';
    $filtro_accion = isset($_GET['filtro_accion']) ? sanitizar($_GET['filtro_accion']) : '';
    $filtro_archivo = isset($_GET['filtro_archivo']) ? sanitizar($_GET['filtro_archivo']) : '';
    
    $offset = ($pagina - 1) * $limite;
    
    // Construir la consulta base
    $sql_base = "SELECT usuarios.nombres AS nombre, 
                        pista_auditoria.accion, 
                      
                        CASE 
                            WHEN pista_auditoria.entidad = 'documento' THEN 'Documento' 
                            WHEN pista_auditoria.entidad = 'expediente' THEN 'Expediente' 
                            ELSE 'No' 
                        END AS archivo, 
                        CASE 
                            WHEN pista_auditoria.entidad = 'documento' THEN documentos.titulo 
                            WHEN pista_auditoria.entidad = 'expediente' THEN expedientes.nombre 
                            ELSE 'Sin archivo' 
                        END AS titulo,
                           pista_auditoria.fecha_accion AS fecha 
                 FROM pista_auditoria 
                 JOIN usuarios ON pista_auditoria.id_usuario = usuarios.id_usuario 
                 LEFT JOIN documentos ON pista_auditoria.entidad = 'documento' AND pista_auditoria.entidad_id = documentos.id_documento 
                 LEFT JOIN expedientes ON pista_auditoria.entidad = 'expediente' AND pista_auditoria.entidad_id = expedientes.id_expediente 
                 WHERE pista_auditoria.rol = 'documentador'";
    
    // Construir condiciones WHERE adicionales
    $condiciones = [];
    $params = [];
    $types = '';
    
    if (!empty($busqueda)) {
        $condiciones[] = "(usuarios.nombres LIKE ? OR pista_auditoria.accion LIKE ? OR documentos.titulo LIKE ? OR expedientes.nombre LIKE ?)";
        $busqueda_param = "%$busqueda%";
        $params = array_merge($params, [$busqueda_param, $busqueda_param, $busqueda_param, $busqueda_param]);
        $types .= 'ssss';
    }
    
    if (!empty($filtro_accion)) {
        $condiciones[] = "pista_auditoria.accion = ?";
        $params[] = $filtro_accion;
        $types .= 's';
    }
    
    if (!empty($filtro_archivo)) {
        if ($filtro_archivo === 'Documento') {
            $condiciones[] = "pista_auditoria.entidad = 'documento'";
        } elseif ($filtro_archivo === 'Expediente') {
            $condiciones[] = "pista_auditoria.entidad = 'expediente'";
        } elseif ($filtro_archivo === 'No') {
            $condiciones[] = "pista_auditoria.entidad IS NULL OR pista_auditoria.entidad = ''";
        }
    }
    
    // Agregar condiciones a la consulta
    if (!empty($condiciones)) {
        $sql_base .= " AND " . implode(" AND ", $condiciones);
    }
    
    // Consulta para contar total de registros
    $sql_count = "SELECT COUNT(*) as total FROM ($sql_base) as subconsulta";
    
    // Preparar y ejecutar consulta de conteo
    $stmt_count = $conexion_metadocs->prepare($sql_count);
    if (!empty($params)) {
        $stmt_count->bind_param($types, ...$params);
    }
    $stmt_count->execute();
    $resultado_count = $stmt_count->get_result();
    $total_registros = $resultado_count->fetch_assoc()['total'];
    
    // Consulta principal con paginación
    $sql_datos = $sql_base . " ORDER BY pista_auditoria.fecha_accion DESC LIMIT ? OFFSET ?";
    $params[] = $limite;
    $params[] = $offset;
    $types .= 'ii';
    
    // Preparar y ejecutar consulta de datos
    $stmt_datos = $conexion_metadocs->prepare($sql_datos);
    if (!empty($params)) {
        $stmt_datos->bind_param($types, ...$params);
    }
    $stmt_datos->execute();
    $resultado_datos = $stmt_datos->get_result();
    
    // Construir array de resultados
    $actividades = [];
    while ($fila = $resultado_datos->fetch_assoc()) {
        // Formatear fecha
        $fecha_formateada = date('d/m/Y H:i', strtotime($fila['fecha']));
        
        $actividades[] = [
            'nombre' => $fila['nombre'],
            'accion' => $fila['accion'],
            'archivo' => $fila['archivo'],
            'titulo' => $fila['titulo'] ?? 'Sin título',
             'fecha' => $fecha_formateada,
        ];
    }
    
    // Calcular información de paginación
    $total_paginas = ceil($total_registros / $limite);
    $registro_inicio = $offset + 1;
    $registro_fin = min($offset + $limite, $total_registros);
    
    // Preparar respuesta
    $respuesta = [
        'success' => true,
        'data' => $actividades,
        'pagination' => [
            'pagina_actual' => $pagina,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros,
            'registros_por_pagina' => $limite,
            'registro_inicio' => $registro_inicio,
            'registro_fin' => $registro_fin,
            'hay_anterior' => $pagina > 1,
            'hay_siguiente' => $pagina < $total_paginas
        ]
    ];
    
    // Cerrar statements
    $stmt_count->close();
    $stmt_datos->close();
    
    echo json_encode($respuesta);
    
} catch (Exception $e) {
    // Manejo de errores
    $error = [
        'success' => false,
        'message' => 'Error al obtener las actividades: ' . $e->getMessage(),
        'data' => [],
        'pagination' => []
    ];
    
    echo json_encode($error);
    
} finally {
    // Cerrar conexión si existe
    if (isset($conexion_metadocs)) {
        $conexion_metadocs->close();
    }
}
?>