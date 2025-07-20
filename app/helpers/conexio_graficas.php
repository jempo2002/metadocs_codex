     
            <?php
// config.php - Configuración de base de datos
$host = 'localhost';
$db_name = 'metadocs';
$username = 'root';
$password = '';

// Función para obtener conexión
function getConnection() {
    global $host, $db_name, $username, $password;
    
    $conn = mysqli_connect($host, $username, $password, $db_name);
    
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }
    
    // Configurar charset para evitar problemas con caracteres especiales
    mysqli_set_charset($conn, "utf8");
    
    return $conn;
}

// dashboard.php - Página principal con gráficos
$conn = getConnection();
?>