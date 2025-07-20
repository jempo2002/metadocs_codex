<?php
$archivo = basename($_GET['file']); // evita rutas peligrosas
$extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
$esPDF = ($extension === 'pdf');

$url = $esPDF 
    ? "ver_documento.php?file=" . urlencode($archivo)
    : "https://docs.google.com/gview?url=https://tudominio.com/uploads/" . urlencode($archivo) . "&embedded=true";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visualizador</title>
    <style>
        body { margin: 0; padding: 0; }
        iframe { width: 100vw; height: 100vh; border: none; }
    </style>
</head>
<body>
    <iframe src="<?= htmlspecialchars($url) ?>"></iframe>
</body>
</html>
