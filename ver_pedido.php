<?php
include("API/conexion.php");

if (!isset($_GET['pedido_id'])) {
    die("Falta ID del pedido");
}

$pedido_id = intval($_GET['pedido_id']);

$res = $conn->query("
    SELECT c.*, p.imagen
    FROM compras c
    LEFT JOIN productos p ON c.producto = p.nombre
    WHERE c.pedido_id = '$pedido_id'
");

if ($res->num_rows === 0) {
    die("No se encontró el pedido");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Pedido #<?php echo $pedido_id; ?></title>
<meta charset="UTF-8">
<style>
table { width: 90%; margin: auto; border-collapse: collapse; }
td, th { border: 1px solid #ccc; padding: 10px; }
th { background-color: #b22222; color: white; }
</style>
</head>
<body>
<h2 style="text-align:center;">Pedido #<?php echo $pedido_id; ?></h2>

<table>
<tr>
<th>Imagen</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Total</th>
</tr>

<?php while ($f = $res->fetch_assoc()): ?>
<tr>
  <td><img src="<?php echo $f['imagen']; ?>" width="80"></td>
  <td><?php echo $f['producto']; ?></td>
  <td><?php echo $f['cantidad']; ?></td>
  <td><?php echo $f['total']; ?></td>
</tr>
<?php endwhile; ?>

</table>
</body>
</html>
