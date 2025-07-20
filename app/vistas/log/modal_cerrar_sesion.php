<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../componentes/css/cerrar_sesion.css">
</head>
<body>



<div id="modal" class="modal">
  <form class="modal-contenido" method="post" action="../../backend/login/cerrar_sesion.php">
    <span class="close" id="cerrar">&times;</span>
    <h2>¿Deseas cerrar sesión?</h2>
    <div class="modal-buttons">
      <button type="button" class="cancel-btn" id="cancelar_btn">Cancelar</button>
      <button type="submit" class="logout-btn">Cerrar sesión</button>
    </div>
  </form>
</div>
<script src="../../../componentes/js/log/cerrar_sesion.js"></script>
</body>
</html>