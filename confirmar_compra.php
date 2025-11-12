<?php
session_start();
include("API/conexion.php");

if (!isset($_POST['pedido_id'])) {
    die("Pedido no válido.");
}

$pedido_id = $_POST['pedido_id'];
$direccion = $_POST['direccion'] ?? '';
$lat = $_POST['latitud'] ?? '';
$lng = $_POST['longitud'] ?? '';

// Actualizar todos los productos de ese pedido con la información de entrega
$sql = "UPDATE compras 
        SET tipo_entrega='Domicilio', direccion_entrega='$direccion', latitud='$lat', longitud='$lng'
        WHERE pedido_id='$pedido_id'";
$conn->query($sql);

// Redirigir al ticket
header("Location: ticket.php?pedido_id=$pedido_id");
exit;
