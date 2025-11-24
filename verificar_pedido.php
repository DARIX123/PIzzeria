<?php
include("API/conexion.php");

if (!isset($_GET['pedido_id']) || !isset($_GET['token'])) {
    die("Falta ID o token del pedido");
}

$pedido_id = $_GET['pedido_id'];
$token = $_GET['token'];

// 🔹 Verificar token y que el pedido exista
$resToken = $conn->query("SELECT * FROM compras WHERE pedido_id='$pedido_id' AND token_entrega='$token'");
if($resToken->num_rows === 0) {
    die("Pedido no válido o ya entregado");
}

$fila = $resToken->fetch_assoc();

if($fila['estado'] === 'entregado'){
    echo "Pedido ya entregado";
    exit;
}

// 🔹 Marcar como entregado si no lo estaba
$update = $conn->query("UPDATE compras SET estado='entregado' WHERE pedido_id='$pedido_id'");

$nodeUrl = "http://localhost:3000/verificar_pedido.php?pedido_id=$pedido_id";
@file_get_contents($nodeUrl);

// 🔹 Redirigir al cliente (móvil) a la página de ver pedido
header("Location: ver_pedido.php?pedido_id=$pedido_id");
exit;
?>
