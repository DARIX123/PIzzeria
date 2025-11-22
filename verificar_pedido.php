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

// 🔹 Marcar como entregado si no lo estaba
$update = $conn->query("UPDATE compras SET estado='entregado' WHERE pedido_id='$pedido_id'");

// 🔹 Obtener datos actualizados del pedido
$res = $conn->query("
    SELECT c.*, p.imagen
    FROM compras c
    LEFT JOIN productos p ON c.producto = p.nombre
    WHERE c.pedido_id='$pedido_id'
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedido #<?php echo $pedido_id; ?></title>
<style>
table { border-collapse: collapse; width: 90%; margin: 20px auto;}
th, td { border:1px solid #ddd; padding:8px; text-align:center;}
th { background:#b22222; color:white;}
</style>
</head>
<body>

<h1>Pedido #<?php echo $pedido_id; ?></h1>
<p id="estado">Estado: <strong>Entregado ✅</strong></p>

<table>
<tr><th>Producto</th><th>Cantidad</th><th>Total</th></tr>
<?php while($f = $res->fetch_assoc()): ?>
<tr>
<td><?php echo $f['producto']; ?></td>
<td><?php echo $f['cantidad']; ?></td>
<td>$<?php echo $f['total']; ?></td>
</tr>
<?php endwhile; ?>
</table>

<script>
// 🔹 Conectar al WebSocket del servidor Node.js
const socket = new WebSocket("wss://multilobular-guarded-michelle.ngrok-free.dev/ws");

socket.onopen = () => {
    console.log("🔵 Conectado a WebSocket");
    // Avisar al servidor que este cliente está viendo este pedido
    socket.send("pedido_abierto:<?php echo $pedido_id; ?>");
};

socket.onmessage = (event) => {
    console.log("📩 Mensaje WebSocket:", event.data);
    // Si el servidor notifica que este pedido fue actualizado
    if(event.data === "pedido_actualizado:<?php echo $pedido_id; ?>") {
        // 🔄 Recargar la página para reflejar el cambio
        location.reload();
    }
};
</script>

</body>
</html>
