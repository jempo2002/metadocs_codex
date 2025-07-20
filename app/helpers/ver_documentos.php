<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use ConvertApi\ConvertApi;

//  Clave de API de ConvertAPI
$API_KEY = 'Kfilut7lXcr41xSRbOT7W37Hhq82pqqi'; // Reemplaza por tu clave real

// Configurar la API key
ConvertApi::setApiCredentials($API_KEY);

// Validar si se recibió el nombre del archivo
if (!isset($_GET['file'])) {
    die(" Archivo no especificado.");
}

$archivo = basename($_GET['file']); // evita rutas externas

//  CORRECCIÓN: Si el archivo no tiene extensión, intentar encontrarla
$extension_archivo = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
if (empty($extension_archivo)) {
    // Buscar el archivo con cualquier extensión
    $uploads_dir = __DIR__ . "/../uploads/";
    $archivos_encontrados = glob($uploads_dir . $archivo . ".*");
    
    if (!empty($archivos_encontrados)) {
        $archivo = basename($archivos_encontrados[0]); // Tomar el primero encontrado
    }
}


$path_archivo = __DIR__ . "/../uploads/" . $archivo;

// Verifica si el archivo existe
if (!file_exists($path_archivo)) {
    // 🔍 Intentar rutas alternativas comunes
    $rutas_alternativas = [
        __DIR__ . "/../uploads/" . $archivo,         // app/helpers/ -> app/uploads/
        __DIR__ . "/uploads/" . $archivo,            // Por si uploads está en helpers/
        __DIR__ . "/../../uploads/" . $archivo,      // Por si uploads está en raíz
        "./uploads/" . $archivo,                     // Relativa simple
        "../uploads/" . $archivo                     // Un nivel arriba
    ];
    
    $archivo_encontrado = false;
    foreach ($rutas_alternativas as $ruta_alt) {
        if (file_exists($ruta_alt)) {
            $path_archivo = $ruta_alt;
            $archivo_encontrado = true;
            break;
        }
    }
    
    if (!$archivo_encontrado) {
        die(" El archivo '$archivo' no existe en ninguna de las rutas esperadas.<br>
            Ruta principal: $path_archivo<br>
            Verifica que el archivo esté subido correctamente.");
    }
}

// Obtener extensión
$extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

// Si es PDF, mostrar directamente
if ($extension === 'pdf') {
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=\"" . $archivo . "\"");
    readfile($path_archivo);
    exit;
}

// Si es imagen, mostrar directamente
$imagenes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
if (in_array($extension, $imagenes)) {
    $mime_type = match($extension) {
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'webp' => 'image/webp',
        default => 'image/jpeg'
    };
    
    header("Content-Type: $mime_type");
    header("Content-Disposition: inline; filename=\"" . $archivo . "\"");
    readfile($path_archivo);
    exit;
}

// Si es Word, Excel, PowerPoint, etc., convertir a PDF
$permitidos = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'];

if (in_array($extension, $permitidos)) {
    // Crear directorio temporal si no existe
    $temp_dir = __DIR__ . "/temp_cache";
    if (!is_dir($temp_dir)) {
        mkdir($temp_dir, 0755, true);
    }

    $pdf_nombre = pathinfo($archivo, PATHINFO_FILENAME) . ".pdf";
    $pdf_temp_path = $temp_dir . "/" . $pdf_nombre;

    // Verificar si existe cache y si es más reciente que el archivo original
    $usar_cache = false;
    if (file_exists($pdf_temp_path)) {
        $tiempo_original = filemtime($path_archivo);
        $tiempo_cache = filemtime($pdf_temp_path);
        
        // Si el cache es más reciente que el original, usarlo
        if ($tiempo_cache >= $tiempo_original) {
            $usar_cache = true;
        }
    }

    if ($usar_cache) {
        // Usar archivo en cache
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename=\"" . $pdf_nombre . "\"");
        readfile($pdf_temp_path);
        exit;
    } else {
        try {
            // Convertir el archivo a PDF
            $result = ConvertApi::convert('pdf', [
                'File' => $path_archivo,
            ], $extension);

            // Obtener contenido del PDF
            $pdf_content = $result->getFile()->getContents();

            // Guardar en cache temporal (opcional)
            file_put_contents($pdf_temp_path, $pdf_content);

            // Limpiar cache antiguo (archivos de más de 24 horas)
            limpiarCacheAntiguo($temp_dir);

            // Enviar PDF al navegador
            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=\"" . $pdf_nombre . "\"");
            header("Content-Length: " . strlen($pdf_content));
            
            echo $pdf_content;
            exit;

        } catch (Exception $e) {
            die(" Error al convertir el documento: " . $e->getMessage());
        }
    }
}

// Función para limpiar cache antiguo
function limpiarCacheAntiguo($directorio, $horas = 24) {
    $archivos = glob($directorio . "/*");
    $tiempo_limite = time() - ($horas * 3600);
    
    foreach ($archivos as $archivo) {
        if (is_file($archivo) && filemtime($archivo) < $tiempo_limite) {
            unlink($archivo);
        }
    }
}

// Si el tipo no está soportado
die(" Tipo de archivo '$extension' no soportado para visualización.");
?>