<?php



$host = "localhost";     
$usuario = "root";       
$contrasena = "";        
$basedatos = "metadocs";  


$conexion_metadocs = mysqli_connect($host, $usuario, $contrasena, $basedatos);


if (!$conexion_metadocs) {
    die("Error al conectar: " . mysqli_connect_error());
} else {
    
}


?>