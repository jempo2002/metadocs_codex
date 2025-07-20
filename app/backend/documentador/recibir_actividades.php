<?php
// ===== PASO 1: CONFIGURACIÓN DE BASE DE DATOS =====
require_once '../../helpers/conexion_bd.php';
require_once '../../helpers/verificacion_roles.php';
require_once '../../helpers/info_usuario.php';

AutorizacionRol('documentador');

// ===== CONFIGURACIÓN DE PAGINACIÓN =====
$pagina_actual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$registros_por_pagina = 10; // Puedes ajustar este número según tus necesidades
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// ===== PASO 2: FUNCIÓN PARA OBTENER NOTIFICACIONES CON PAGINACIÓN =====
function obtenerNotificaciones($usuario_destinatario, $limit = 10, $offset = 0) {
    global $conexion_metadocs;
    
    $sql = "SELECT 
        id_actividad,
        id_usuario,
        fecha_visualizacion,
        fecha_creacion,
        tipo_actividad,
        mensaje,
        usuario_destinatario
    FROM actividades 
    WHERE usuario_destinatario = ?
    ORDER BY fecha_creacion DESC
    LIMIT ? OFFSET ?";
    
    $stmt = $conexion_metadocs->prepare($sql);
    $stmt->bind_param("sii", $usuario_destinatario, $limit, $offset);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $notificaciones = [];
    while ($row = $resultado->fetch_assoc()) {
        $notificaciones[] = $row;
    }
    
    return $notificaciones;
}

// ===== FUNCIÓN PARA CONTAR TOTAL DE NOTIFICACIONES =====
function contarTotalNotificaciones($usuario_destinatario) {
    global $conexion_metadocs;
    
    $sql = "SELECT COUNT(*) as total FROM actividades WHERE usuario_destinatario = ?";
    $stmt = $conexion_metadocs->prepare($sql);
    $stmt->bind_param("s", $usuario_destinatario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $row = $resultado->fetch_assoc();
    
    return $row['total'];
}

// ===== PASO 3: FUNCIÓN PARA PROCESAR DATOS DEL MENSAJE =====
function procesarMensaje($mensaje_json) {
    $datos = json_decode($mensaje_json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            'texto' => $mensaje_json,
            'titulo_documento' => '',
            'titulo_expediente' => '',
            'categoria' => '',
            'expediente_destino' => '',
            'motivo' => ''
        ];
    }
    
    return [
        'texto' => $datos['descripcion'] ?? $datos['texto'] ?? '',
        'titulo_documento' => $datos['titulo_documento'] ?? '',
        'titulo_expediente' => $datos['titulo_expediente'] ?? '',
        'categoria' => $datos['categoria'] ?? '',
        'expediente_destino' => $datos['expediente_destinado'] ?? $datos['expediente_destino'] ?? '',
        'motivo' => $datos['motivo'] ?? ''
    ];
}

// ===== PASO 4: FUNCIÓN PARA MAPEAR TIPOS A ICONOS Y ESTILOS =====
function obtenerConfiguracionTipo($tipo_actividad) {
    $configuraciones = [
        'solicitud_documento' => [
            'icono' => 'bi-file-earmark-arrow-up',
            'texto_tipo' => 'Solicitud de documento',
            'clase_css' => 'documento',
            'modal' => 'modal-solicitud-documento'
        ],
        'documento_aprobado' => [
            'icono' => 'bi-file-earmark-check',
            'texto_tipo' => 'Documento aprobado',
            'clase_css' => 'documento-aprobado',
            'modal' => 'modal-documento-aprobado'
        ],
        'documento_rechazado' => [
            'icono' => 'bi-file-earmark-x',
            'texto_tipo' => 'Documento rechazado',
            'clase_css' => 'documento-rechazado',
            'modal' => 'modal-documento-rechazado'
        ],
        'expediente_aprobado' => [
            'icono' => 'bi-folder-check',
            'texto_tipo' => 'Expediente aprobado',
            'clase_css' => 'expediente-aprobado',
            'modal' => 'modal-expediente-aprobado'
        ],
        'expediente_rechazado' => [
            'icono' => 'bi-folder-x',
            'texto_tipo' => 'Expediente rechazado',
            'clase_css' => 'expediente-rechazado',
            'modal' => 'modal-expediente-rechazado'
        ]
    ];
    
    return $configuraciones[$tipo_actividad] ?? [
        'icono' => 'bi-info-circle',
        'texto_tipo' => 'Notificación',
        'clase_css' => 'default',
        'modal' => 'modal-default'
    ];
}

// ===== PASO 5: FUNCIÓN PARA CALCULAR TIEMPO TRANSCURRIDO =====
date_default_timezone_set('America/Bogota');

// ===== FUNCIÓN CORREGIDA PARA CALCULAR TIEMPO TRANSCURRIDO =====
function tiempoTranscurrido($fecha_creacion) {
    try {
        // Configurar zona horaria para Colombia
        $timezone = new DateTimeZone('America/Bogota');
        
        // Crear fechas con la zona horaria correcta
        $fecha_actual = new DateTime('now', $timezone);
        $fecha_mensaje = new DateTime($fecha_creacion, $timezone);
        
        // Calcular diferencia
        $diferencia = $fecha_actual->diff($fecha_mensaje);
        
        // Calcular tiempo transcurrido en orden de prioridad
        if ($diferencia->days > 30) {
            $meses = floor($diferencia->days / 30);
            return "hace " . $meses . " mes" . ($meses > 1 ? "es" : "");
        } elseif ($diferencia->days > 0) {
            return "hace " . $diferencia->days . " día" . ($diferencia->days > 1 ? "s" : "");
        } elseif ($diferencia->h > 0) {
            return "hace " . $diferencia->h . " hora" . ($diferencia->h > 1 ? "s" : "");
        } elseif ($diferencia->i > 0) {
            return "hace " . $diferencia->i . " minuto" . ($diferencia->i > 1 ? "s" : "");
        } else {
            return "hace unos segundos";
        }
        
    } catch (Exception $e) {
        // En caso de error, devolver un valor por defecto
        error_log("Error calculando tiempo transcurrido: " . $e->getMessage());
        return "hace un momento";
    }
}

// ===== FUNCIÓN ALTERNATIVA MÁS SIMPLE (SI LA ANTERIOR DA PROBLEMAS) =====
function tiempoTranscurridoSimple($fecha_creacion) {
    // Asegurar zona horaria
    date_default_timezone_set('America/Bogota');
    
    // Convertir a timestamp
    $timestamp_mensaje = strtotime($fecha_creacion);
    $timestamp_actual = time();
    
    // Calcular diferencia en segundos
    $diferencia_segundos = $timestamp_actual - $timestamp_mensaje;
    
    // Convertir a unidades más grandes
    $minutos = floor($diferencia_segundos / 60);
    $horas = floor($diferencia_segundos / 3600);
    $dias = floor($diferencia_segundos / 86400);
    $meses = floor($diferencia_segundos / 2592000); // 30 días
    
    if ($meses > 0) {
        return "hace " . $meses . " mes" . ($meses > 1 ? "es" : "");
    } elseif ($dias > 0) {
        return "hace " . $dias . " día" . ($dias > 1 ? "s" : "");
    } elseif ($horas > 0) {
        return "hace " . $horas . " hora" . ($horas > 1 ? "s" : "");
    } elseif ($minutos > 0) {
        return "hace " . $minutos . " minuto" . ($minutos > 1 ? "s" : "");
    } else {
        return "hace unos segundos";
    }
}

// ===== PASO 6: OBTENER DATOS PARA LA VISTA CON PAGINACIÓN =====
$usuario_actual = $usuario['nombres']. " ".$usuario['apellidos'] ?? 'metadocs prueba';

// Calcular datos de paginación
$total_registros = contarTotalNotificaciones($usuario_actual);
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener notificaciones con paginación
$notificaciones = obtenerNotificaciones($usuario_actual, $registros_por_pagina, $offset);

// ===== PASO 7: PROCESAR NOTIFICACIONES PARA LA VISTA =====
$notificaciones_procesadas = [];
foreach ($notificaciones as $notificacion) {
    $datos_mensaje = procesarMensaje($notificacion['mensaje']);
    $config_tipo = obtenerConfiguracionTipo($notificacion['tipo_actividad']);
    
    $notificaciones_procesadas[] = [
        'id' => $notificacion['id_actividad'],
        'usuario_nombre' => obtenerNombreUsuario($notificacion['id_usuario']),
        'tipo_actividad' => $notificacion['tipo_actividad'],
        'icono' => $config_tipo['icono'],
        'texto_tipo' => $config_tipo['texto_tipo'],
        'clase_css' => $config_tipo['clase_css'],
        'modal' => $config_tipo['modal'],
        'es_visto' => !is_null($notificacion['fecha_visualizacion']),
        'tiempo_transcurrido' => tiempoTranscurrido($notificacion['fecha_creacion']),
        'fecha_creacion' => $notificacion['fecha_creacion'],
        'datos' => $datos_mensaje
    ];
}

// ===== PASO 8: FUNCIÓN AUXILIAR PARA OBTENER NOMBRE DE USUARIO =====
function obtenerNombreUsuario($id_usuario) {
    global $conexion_metadocs;
    
    $sql = "SELECT nombres, apellidos FROM usuarios WHERE id_usuario = ?";
    $stmt = $conexion_metadocs->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($row = $resultado->fetch_assoc()) {
        return trim($row['nombres'] . ' ' . $row['apellidos']);
    }
    
    return 'Usuario Desconocido';
}

// ===== FUNCIÓN PARA GENERAR ENLACES DE PAGINACIÓN =====
function generarPaginacion($pagina_actual, $total_paginas, $url_base = '') {
    if ($total_paginas <= 1) {
        return '';
    }
    
    $html = '<nav aria-label="Paginación de notificaciones">';
    $html .= '<ul class="pagination justify-content-center">';
    
    // Botón anterior
    if ($pagina_actual > 1) {
        $html .= '<li class="page-item">';
        $html .= '<a class="page-link" href="' . $url_base . '?pagina=' . ($pagina_actual - 1) . '">';
        $html .= '<i class="bi bi-chevron-left"></i> Anterior</a>';
        $html .= '</li>';
    } else {
        $html .= '<li class="page-item disabled">';
        $html .= '<span class="page-link"><i class="bi bi-chevron-left"></i> Anterior</span>';
        $html .= '</li>';
    }
    
    // Páginas numeradas
    $inicio = max(1, $pagina_actual - 2);
    $fin = min($total_paginas, $pagina_actual + 2);
    
    // Primera página si no está en el rango
    if ($inicio > 1) {
        $html .= '<li class="page-item">';
        $html .= '<a class="page-link" href="' . $url_base . '?pagina=1">1</a>';
        $html .= '</li>';
        if ($inicio > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    // Páginas en el rango
    for ($i = $inicio; $i <= $fin; $i++) {
        if ($i == $pagina_actual) {
            $html .= '<li class="page-item active">';
            $html .= '<span class="page-link">' . $i . '</span>';
            $html .= '</li>';
        } else {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $url_base . '?pagina=' . $i . '">' . $i . '</a>';
            $html .= '</li>';
        }
    }
    
    // Última página si no está en el rango
    if ($fin < $total_paginas) {
        if ($fin < $total_paginas - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $html .= '<li class="page-item">';
        $html .= '<a class="page-link" href="' . $url_base . '?pagina=' . $total_paginas . '">' . $total_paginas . '</a>';
        $html .= '</li>';
    }
    
    // Botón siguiente
    if ($pagina_actual < $total_paginas) {
        $html .= '<li class="page-item">';
        $html .= '<a class="page-link" href="' . $url_base . '?pagina=' . ($pagina_actual + 1) . '">';
        $html .= 'Siguiente <i class="bi bi-chevron-right"></i></a>';
        $html .= '</li>';
    } else {
        $html .= '<li class="page-item disabled">';
        $html .= '<span class="page-link">Siguiente <i class="bi bi-chevron-right"></i></span>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    $html .= '</nav>';
    
    return $html;
}

// ===== INFORMACIÓN DE PAGINACIÓN =====
$inicio_registro = ($pagina_actual - 1) * $registros_por_pagina + 1;
$fin_registro = min($pagina_actual * $registros_por_pagina, $total_registros);

// ===== PASO 9: FUNCIÓN PARA MARCAR COMO VISTO =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['marcar_visto'])) {
    $id_actividad = $_POST['id_actividad'];
    
    $sql = "UPDATE actividades SET fecha_visualizacion = NOW() WHERE id_actividad = ?";
    $stmt = $conexion_metadocs->prepare($sql);
    $stmt->bind_param("i", $id_actividad);
    $stmt->execute();
    
    echo json_encode(['success' => true]);
    exit;
}
?>