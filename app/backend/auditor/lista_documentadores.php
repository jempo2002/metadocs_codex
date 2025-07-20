<?php  
require_once '../../helpers/conexion_bd.php'; 
require_once '../../helpers/info_usuario.php';  

$area_usuarios = $usuario['id_area'];  

$sql_lista_documentadores = "SELECT id_usuario, nombres, apellidos FROM `usuarios` WHERE usuarios.rol = 'documentador' AND usuarios.id_area = ? AND usuarios.estado = 'activo'"; 

$sentencia_doc = $conexion_metadocs->prepare($sql_lista_documentadores);
$sentencia_doc->bind_param("i", $area_usuarios);
$sentencia_doc->execute();
$resultado = $sentencia_doc->get_result();

$datos_documentadores = [];

if ($resultado->num_rows > 0) {          
    while ($fila_doc = $resultado->fetch_assoc()) {                  
        $datos_documentadores[] = [             
            'id' => $fila_doc['id_usuario'],             
            'nombre' => $fila_doc['nombres'] . ' ' . $fila_doc['apellidos']         
        ];     
    }  
}   

// Lista de expedientes  
$sql_lista_expedientes = "SELECT id_expediente, nombre FROM `expedientes` WHERE expedientes.id_area = ? AND expedientes.estado = 'aprobado'";  
$sentencia_exp = $conexion_metadocs->prepare($sql_lista_expedientes); 
$sentencia_exp->bind_param('i', $area_usuarios); 
$sentencia_exp->execute(); 
$resultado_exp = $sentencia_exp->get_result();  

$datos_expediente = [];  

if ($resultado_exp->num_rows > 0) {     
    while($fila_exp = $resultado_exp->fetch_assoc()) {     
        $datos_expediente[] = [         
            'id' => $fila_exp['id_expediente'],         
            'nombre' => $fila_exp['nombre']      
        ]; 
    }
}    

$sentencia_doc->close();  
$sentencia_exp->close();

// Preparar datos completos para el frontend
$datos_completos = [
    'documentadores' => $datos_documentadores,
    'expedientes' => $datos_expediente
];

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {     
    header('Content-Type: application/json');     
    echo json_encode([         
        'success' => true,         
        'documentadores' => $datos_documentadores,
        'expedientes' => $datos_expediente     
    ]);     
    exit; 
}

// Para uso en el HTML principal
$datos_documentadores = [
    'datos_completos' => $datos_completos
];
?>