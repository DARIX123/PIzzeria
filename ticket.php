<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: formulario.php");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $producto = $_POST["producto"];
    $cantidad = $_POST["cantidad"];
    $total = $_POST["total"];
    $tipo_entrega = $_POST["tipo_entrega"];

    $sql = "INSERT INTO compras (usuario_id, producto, cantidad, total, tipo_entrega)
            VALUES ('$usuario_id', '$producto', '$cantidad', '$total', '$tipo_entrega')";
    $conexion->query($sql);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Compra</title>
</head>
<body>
    <h1>Ticket de Compra</h1>
    <p>Gracias por tu compra, <?php echo $_SESSION["usuario"]; ?>.</p>

    <table border="1" cellpadding="8">
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Tipo de entrega</th>
        </tr>
        <?php
        $resultado = $conexion->query("SELECT * FROM compras WHERE usuario_id='$usuario_id' ORDER BY fecha_compra DESC LIMIT 1");
        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td>{$fila['producto']}</td>
                    <td>{$fila['cantidad']}</td>
                    <td>\${$fila['total']}</td>
                    <td>{$fila['tipo_entrega']}</td>
                  </tr>";
        }
        ?>
    </table>
    <br>
    <a href="mis_compras.php">Ver todas mis compras</a>
</body>
</html>
