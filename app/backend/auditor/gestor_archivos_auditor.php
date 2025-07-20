<?php
require_once '../../helpers/conexion_bd.php';
require_once '../../helpers/info_usuario.php';

$area = $usuario['id_area'];
$id_usuario = $usuario['id_usuario'];

// Verificar conexión
if ($conexion_metadocs->connect_error) {
    die("Connection failed: " . $conexion_metadocs->connect_error);
}


 function registrarAuditoria($conexion, $id_area, $accion, $entidad, $entidad_id, $id_usuario, $rol) {
    $sql_auditoria = "INSERT INTO pista_auditoria (id_area, accion, fecha_accion, entidad, entidad_id, id_usuario, rol) 
                      VALUES (?, ?, NOW(), ?, ?, ?, ?)";
    
    if ($stmt_auditoria = $conexion->prepare($sql_auditoria)) {
        $stmt_auditoria->bind_param('issiis', $id_area, $accion, $entidad, $entidad_id, $id_usuario, $rol);
        
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


// Función subir expediente
function subirExpediente($conexion, $nombre, $descripcion, $padreId, $area, $id_usuario) {
    $estado = 'aprobado';
    $sql_expediente = $conexion->prepare("INSERT INTO expedientes (nombre, descripcion, expediente_padre, id_area, estado, autor) VALUES (?,?,?,?,?,?)");

    if ($sql_expediente) {
        $sql_expediente->bind_param("ssiisi", $nombre, $descripcion, $padreId, $area, $estado, $id_usuario); 
        return $sql_expediente->execute();
    } else {
        die("Error al preparar la consulta: " . $conexion->error);
    }
}
function obtenerDocumentos($conexion, $padre_id, $area) {
    $cantidad_tabla = 5;
    // Cambiar aquí: usar pagina_doc en lugar de pagina
    $pagina = isset($_GET['pagina_doc']) ? (int)$_GET['pagina_doc'] : 1;
    $inicio = ($pagina - 1) * $cantidad_tabla;
    
    // Consulta con paginación
    $sql = "SELECT id_documento, titulo, path, fecha_creacion, tipo 
            FROM documentos 
            WHERE id_expediente = ? 
            AND id_area = ?
            AND estado = 'aprobado' 
            AND estado_retencion = 'activo'
            ORDER BY fecha_creacion DESC
            LIMIT ?, ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iiii", $padre_id, $area, $inicio, $cantidad_tabla);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $documentos = $resultado->fetch_all(MYSQLI_ASSOC);
    
    // Obtener total de registros para calcular páginas
    $sql_total = "SELECT COUNT(*) as total 
                 FROM documentos 
                 WHERE id_expediente = ? 
                 AND id_area = ? 
                 AND estado = 'aprobado' 
                 AND estado_retencion = 'activo'";
    
    $stmt_total = $conexion->prepare($sql_total);
    $stmt_total->bind_param("ii", $padre_id, $area);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $total_filas = $result_total->fetch_assoc()['total'];
    $total_paginas = ceil($total_filas / $cantidad_tabla);
    
    return [
        'documentos' => $documentos,
        'pagina_actual' => $pagina,
        'total_paginas' => $total_paginas,
        'total_registros' => $total_filas
    ];
}
function obtenerContenidoUnificado($conexion, $padre_id, $area) {
    $cantidad_tabla = 10;
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $inicio = ($pagina - 1) * $cantidad_tabla;
    
    if ($padre_id === 0) {
        // Solo expedientes en la raíz
        $sql = "SELECT id_expediente as id, nombre, descripcion, fecha_creacion, 'expediente' as tipo_contenido
                FROM expedientes 
                WHERE (expediente_padre IS NULL OR expediente_padre = 0) 
                AND id_area = ? 
                AND estado = 'aprobado' 
                ORDER BY nombre 
                LIMIT ?, ?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('iii', $area, $inicio, $cantidad_tabla);
        
        // Contar total
        $sql_total = "SELECT COUNT(*) as total 
                     FROM expedientes 
                     WHERE (expediente_padre IS NULL OR expediente_padre = 0) 
                     AND id_area = ? 
                     AND estado = 'aprobado'";
        
        $stmt_total = $conexion->prepare($sql_total);
        $stmt_total->bind_param('i', $area);
        
    } else {
        // Expedientes + documentos unificados
        $sql = "(SELECT id_expediente as id, nombre , descripcion, fecha_creacion, 'expediente' as tipo_contenido, '' as tipo
                FROM expedientes 
                WHERE expediente_padre = ? 
                AND id_area = ? 
                AND estado = 'aprobado')
                UNION ALL
                (SELECT id_documento as id, titulo, '' as descripcion, fecha_creacion, 'documento' as tipo_contenido, tipo
                FROM documentos 
                WHERE id_expediente = ? 
                AND id_area = ?
                AND estado = 'aprobado' 
                AND estado_retencion = 'activo')
                ORDER BY tipo_contenido DESC, fecha_creacion DESC
                LIMIT ?, ?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('iiiiii', $padre_id, $area, $padre_id, $area, $inicio, $cantidad_tabla);
        
        // Contar total unificado
        $sql_total = "(SELECT COUNT(*) as count FROM expedientes 
                      WHERE expediente_padre = ? AND id_area = ? AND estado = 'aprobado')
                      UNION ALL
                      (SELECT COUNT(*) as count FROM documentos 
                      WHERE id_expediente = ? AND id_area = ? AND estado = 'aprobado' 
                      AND estado_retencion = 'activo')";
        
        $stmt_total = $conexion->prepare($sql_total);
        $stmt_total->bind_param('iiii', $padre_id, $area, $padre_id, $area);
    }
    
    $stmt->execute();
    $resultado = $stmt->get_result();
    $contenido = $resultado->fetch_all(MYSQLI_ASSOC);
    
    // Calcular total
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    
    if ($padre_id === 0) {
        $total_filas = $result_total->fetch_assoc()['total'];
    } else {
        // Sumar los conteos de expedientes y documentos
        $total_filas = 0;
        while ($row = $result_total->fetch_assoc()) {
            $total_filas += $row['count'];
        }
    }
    
    $total_paginas = ceil($total_filas / $cantidad_tabla);
    
    return [
        'contenido' => $contenido,
        'pagina_actual' => $pagina,
        'total_paginas' => $total_paginas,
        'total_registros' => $total_filas
    ];
}


// Función mejorada para obtener expedientes (manteniendo la estructura anterior)
function obtenerExpedientes($conexion, $padreId, $area) {
    $cantidad_tabla = 10;
    // Cambiar aquí: usar pagina_exp en lugar de pagina
    $pagina = isset($_GET['pagina_exp']) ? (int)$_GET['pagina_exp'] : 1;
    $inicio = ($pagina - 1) * $cantidad_tabla;
    
    if ($padreId === 0) {
        // Consulta para obtener expedientes con paginación
        $sql_paginacion = "SELECT id_expediente, nombre, descripcion, fecha_creacion 
                          FROM `expedientes` 
                          WHERE (expediente_padre IS NULL OR expediente_padre = 0) 
                          AND id_area = ? 
                          AND estado = 'aprobado' 
                          ORDER BY nombre 
                          LIMIT ?, ?";
        
        $stmt = $conexion->prepare($sql_paginacion);
        $stmt->bind_param('iii', $area, $inicio, $cantidad_tabla);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $expedientes = $resultado->fetch_all(MYSQLI_ASSOC);

        // Obtener total de registros para calcular páginas
        $sql_total = "SELECT COUNT(*) as total 
                     FROM expedientes 
                     WHERE (expediente_padre IS NULL OR expediente_padre = 0) 
                     AND id_area = ? 
                     AND estado = 'aprobado'";
        
        $stmt_total = $conexion->prepare($sql_total);
        $stmt_total->bind_param('i', $area);
        $stmt_total->execute();
        $result_total = $stmt_total->get_result();
        $total_filas = $result_total->fetch_assoc()['total'];
        $total_paginas = ceil($total_filas / $cantidad_tabla);

        return [
            'expedientes' => $expedientes,
            'pagina_actual' => $pagina,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_filas
        ];
        
    } else {
        // Para subcarpetas, también con paginación
        $sql_paginacion = "SELECT id_expediente, nombre, descripcion, fecha_creacion 
                          FROM `expedientes` 
                          WHERE expediente_padre = ? 
                          AND id_area = ? 
                          AND estado = 'aprobado'  
                          ORDER BY nombre
                          LIMIT ?, ?";
        
        $stmt = $conexion->prepare($sql_paginacion);
        $stmt->bind_param('iiii', $padreId, $area, $inicio, $cantidad_tabla);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $expedientes = $resultado->fetch_all(MYSQLI_ASSOC);
        
        // Obtener total de subcarpetas
        $sql_total = "SELECT COUNT(*) as total 
                     FROM expedientes 
                     WHERE expediente_padre = ? 
                     AND id_area = ? 
                     AND estado = 'aprobado'";
        
        $stmt_total = $conexion->prepare($sql_total);
        $stmt_total->bind_param('ii', $padreId, $area);
        $stmt_total->execute();
        $result_total = $stmt_total->get_result();
        $total_filas = $result_total->fetch_assoc()['total'];
        $total_paginas = ceil($total_filas / $cantidad_tabla);
        
        return [
            'expedientes' => $expedientes,
            'pagina_actual' => $pagina,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_filas
        ];
    }
}

// Función para obtener información de un expediente específico
function obtenerInfoExpediente($conexion, $id_expediente) {
    $sql = "SELECT id_expediente, nombre, descripcion, fecha_creacion 
            FROM expedientes 
            WHERE id_expediente = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_expediente);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado ? $resultado->fetch_assoc() : false;
}

// Función para subir documento
function subirDocumento($conexion, $archivo, $id_expediente, $area, $id_usuario, $categoria) {
    if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    // Buscar categoría de retención
    $sql_buscar_categoria = "SELECT id_retencion FROM retencion WHERE categoria = ?";
    $stmt_categoria = $conexion->prepare($sql_buscar_categoria);
    $stmt_categoria->bind_param("s", $categoria);
    $stmt_categoria->execute();
    $resultado_categoria = $stmt_categoria->get_result();

    if ($resultado_categoria->num_rows === 0) {
        $_SESSION['error_categoria'] = "Categoría inválida";
        header("Location: ../../vistas/auditor/archivos_auditor.php?error=upload_failed&id_expediente=" . $id_expediente);
        exit();
    }

    $id_retencion = $resultado_categoria->fetch_assoc()['id_retencion'];

    // Calcular fin de retención
    $sql_finRetencion = "SELECT DATE_ADD(CURDATE(), INTERVAL (duracion_año * 12 + duracion_mes) MONTH) AS fecha_retencion FROM retencion WHERE id_retencion = ?";
    $stmt_finRetencion = $conexion->prepare($sql_finRetencion);
    $stmt_finRetencion->bind_param("i", $id_retencion);
    $stmt_finRetencion->execute();
    $resultado_retencion = $stmt_finRetencion->get_result();
    $fila_retencion = $resultado_retencion->fetch_assoc();
    $fin_retencion = $fila_retencion['fecha_retencion'];

    // Estados por defecto
    $estado = "aprobado";
    $estado_retencion = "activo";

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Definir directorio de subida
        $directorio = "../../uploads/";
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }

        // Generar nombre único para evitar sobrescribir archivos
        $nombre_archivo = uniqid() . '_' . basename($archivo["name"]);
        $nombre_base = pathinfo($archivo["name"], PATHINFO_FILENAME);
        $rutaArchivo = $directorio . $nombre_archivo;

        // Validar la extensión del archivo
        $tipos_permitidos = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
        $extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
        if (!in_array($extension, $tipos_permitidos)) {
            $conexion->rollback();
            return false;
        }

        if (move_uploaded_file($archivo["tmp_name"], $rutaArchivo)) {
            // Insertar el documento
            $sql = $conexion->prepare("INSERT INTO documentos (titulo, path, id_expediente, id_area, tipo, autor, estado, estado_retencion, id_retencion, fin_retencion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $titulo = $nombre_base;
            $tipo = $extension;
            
            $sql->bind_param("ssiisissss", $titulo, $rutaArchivo, $id_expediente, $area, $tipo, $id_usuario, $estado, $estado_retencion, $id_retencion, $fin_retencion);
            $documento_insertado = $sql->execute();

            if ($documento_insertado) {
                $conexion->commit();
                return true;
            } else {
                $conexion->rollback();
                return false;
            }
        }
        
        $conexion->rollback();
        return false;

    } catch (Exception $e) {
        $conexion->rollback();
        return false;
    }
}

// Función para descargar documento
function descargarDocumento($conexion, $documento_id) {
    $sql = "SELECT d.titulo, d.path, d.tipo, e.nombre as nombre_expediente 
            FROM documentos d 
            LEFT JOIN expedientes e ON d.id_expediente = e.id_expediente 
            WHERE d.id_documento = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $documento_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $documento = $resultado->fetch_assoc();

    if (!$documento || !file_exists($documento['path'])) {
        return false;
    }

    // Limpiar el buffer de salida
    if (ob_get_level()) {
        ob_end_clean();
    }

    // Determinar el tipo MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $documento['path']);
    finfo_close($finfo);

    // Preparar el nombre del archivo para la descarga
    $nombreArchivo = $documento['titulo'];
    
    // Configurar las cabeceras para la descarga
    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
    header('Content-Length: ' . filesize($documento['path']));
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Leer y enviar el archivo en bloques
    $handle = fopen($documento['path'], 'rb');
    while (!feof($handle)) {
        echo fread($handle, 8192);
        flush();
    }
    fclose($handle);
    exit;
}

// Función para editar expediente
function editarExpediente($conexion, $id_expediente, $nuevo_titulo, $nueva_descripcion, $area, $id_usuario) {
    try {
        // Obtener el expediente_padre antes de editar
        $sql_parent = "SELECT expediente_padre FROM expedientes WHERE id_expediente = ?";
        $stmt_parent = $conexion->prepare($sql_parent);
        $stmt_parent->bind_param("i", $id_expediente);
        $stmt_parent->execute();
        $result = $stmt_parent->get_result();
        $expediente = $result->fetch_assoc();
        $expediente_padre = $expediente['expediente_padre'];

        // Actualizar expediente
        $sql = "UPDATE expedientes 
                SET nombre = ?, 
                    descripcion = ?
                WHERE id_expediente = ?";
        
        $stmt = $conexion->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error preparando la consulta: " . $conexion->error);
        }
        
        $stmt->bind_param("ssi", $nuevo_titulo, $nueva_descripcion, $id_expediente);
        
        if (!$stmt->execute()) {
            throw new Exception("Error ejecutando la consulta: " . $stmt->error);
        }
        
        if ($stmt->affected_rows >= 0) {

            registrarAuditoria($conexion, $area, 'editó', 'expediente', $id_expediente, $id_usuario, "auditor");

            return [
                'success' => true,
                'expediente_padre' => $expediente_padre
            ];
        } else {
            throw new Exception("El expediente no existe");
        }
    } catch (Exception $e) {
        error_log("Error en editarExpediente: " . $e->getMessage());
        return [
            'success' => false,
            'expediente_padre' => null
        ];
    }
}

// Manejo de los datos de las modales
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['accion']) {
        case 'subir_expediente':
            $nombre = $_POST['titulo_carpeta']; 
            $descripcion = $_POST['desc_carpeta']; 
            $padreId = $_POST['expediente_padre'] ?? 0;
            
            if (subirExpediente($conexion_metadocs, $nombre, $descripcion, $padreId, $area, $id_usuario)) {
                header("Location: ../../vistas/auditor/archivos_auditor.php?success=true&id_expediente=" . $padreId);
                exit();
            } else {
                header("Location: ../../vistas/auditor/archivos_auditor.php?error=true");
                exit();
            }
            break;

        case 'subir_documento':
            $id_expediente = $_POST['expediente_id'];
            $archivo = $_FILES['file-input'];
            $categoria = $_POST['categoria'];
            
            if (subirDocumento($conexion_metadocs, $archivo, $id_expediente, $area, $id_usuario, $categoria)) {
                $_SESSION['doc_exito'] = 'Documento subido con éxito';
                header("Location: ../../vistas/auditor/archivos_auditor.php?success=true&id_expediente=" . $id_expediente);
            } else {
                header("Location: ../../vistas/auditor/archivos_auditor.php?error=upload_failed&id_expediente=" . $id_expediente);
            }
            exit;

        case 'descargar_documento':
            if (isset($_POST['documento_id'])) {
                descargarDocumento($conexion_metadocs, $_POST['documento_id']);
            }
            break;

        case 'editar_expediente':
            if (isset($_POST['id_expediente']) && isset($_POST['nuevo_titulo']) && isset($_POST['nueva_descripcion'])) {
                $id_expediente = $_POST['id_expediente'];
                $nuevo_titulo = $_POST['nuevo_titulo'];
                $nueva_descripcion = $_POST['nueva_descripcion'];
                
                $resultado = editarExpediente($conexion_metadocs, $id_expediente, $nuevo_titulo, $nueva_descripcion, $area , $id_usuario);
                
                if ($resultado['success']) {
                    if ($resultado['expediente_padre']) {
                        header("Location: ../../vistas/auditor/archivos_auditor.php?id_expediente=" . $resultado['expediente_padre']);
                    } else {
                        header("Location: ../../vistas/auditor/archivos_auditor.php");
                    }
                } else {
                    header("Location: ../../vistas/auditor/archivos_auditor.php?error=edit_failed&id_expediente=" . $id_expediente);
                }
                exit;
            }
            break;
    }
}
?>