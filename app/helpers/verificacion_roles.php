<?php

function AutorizacionRol($requiredRole) {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }


    if (!isset($_SESSION['id_log']) || !isset($_SESSION['rol'])) {
        header('Location: ../../../login.php');
        exit();
    }

    
    if ($_SESSION['rol'] !== $requiredRole) {
        header('Location: ../../../login.php');
        exit();
    }
}

