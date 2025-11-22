<?php
include("API/conexion.php");

$pedido_id = $_POST['pedido_id'];

$conn->query("UPDATE compras SET estado='entregado' WHERE pedido_id='$pedido_id'");
?>

<script>
alert("¡Pedido entregado con éxito!");
window.location.href = "mis_compras.php";
</script>
