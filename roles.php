<?php
session_start();

// Si no hay usuario loggeado
if (!isset($_SESSION["usuario"]) || !isset($_SESSION["rol"])) {
    header("Location: formulario.php");
    exit;
}

// Funciones para limitar acceso por rol:
function permitirSolo($roles) {
    if (!in_array($_SESSION["rol"], $roles)) {
        // Si no tiene permiso, lo mandamos al inicio
        header("Location: index.php");
        exit;
    }
}
?>
