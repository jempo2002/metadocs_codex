<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['id_log'])) {
    header('Location: ../../../login.php');
    exit();
}


require_once 'conexio_graficas.php';


$id_usuario = $_SESSION['id_log'];
$sentencia = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$sentencia->bind_param("i", $id_usuario);
$sentencia->execute();
$resultado = $sentencia->get_result();
$usuario = $resultado->fetch_assoc();








?>