<?php
session_start();
include("API/conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: formulario.php");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];

// Solo pedidos entregados
$res = $conn->query("
    SELECT DISTINCT pedido_id
    FROM compras
    WHERE usuario_id='$usuario_id' AND estado='entregado'
    ORDER BY pedido_id DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mis Compras</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { font-family: Arial, sans-serif; background:#fff8f0; margin:0; padding:0; }
header { background:#b22222; color:white; text-align:center; padding:15px; }
header h1 { margin:0; font-size:1.5rem; }
.compras { padding:20px; display:flex; flex-direction:column; align-items:center; }
.compra { background:white; padding:15px 20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); width:90%; max-width:400px; margin:10px 0; display:flex; justify-content:space-between; align-items:center; }
.compra a { text-decoration:none; background:#b22222; color:white; padding:8px 15px; border-radius:6px; font-weight:bold; transition:0.3s; }
.compra a:hover { background:#ff4444; }
</style>
</head>
<body>

<header>
    <h1>Mis Compras Completadas</h1>
</header>

<div class="compras">
<?php while($f = $res->fetch_assoc()): ?>
    <div class="compra">
        <span>Pedido #<?php echo $f['pedido_id']; ?></span>
        <a href="ticket.php?pedido_id=<?php echo $f['pedido_id']; ?>">Ver Ticket</a>
    </div>
<?php endwhile; ?>
</div>

</body>
</html>
