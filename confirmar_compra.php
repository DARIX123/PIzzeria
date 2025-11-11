<?php
session_start();
include("API/conexion.php");

if (!isset($_SESSION["usuario_id"])) {
    header("Location: formulario.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario_id = $_SESSION["usuario_id"];
    $tipo_entrega = $_POST["tipo_entrega"] ?? "domicilio";
    $direccion = $_POST["direccion"] ?? "Ubicación personalizada";
    $latitud = $_POST["latitud"] ?? null;
    $longitud = $_POST["longitud"] ?? null;
    $carrito = $_SESSION["carrito"] ?? [];

    if (!empty($carrito)) {
        foreach ($carrito as $producto) {
            $nombre = $producto["nombre"];
            $cantidad = $producto["cantidad"];
            $total = $producto["precio"] * $cantidad;

            $sql = "INSERT INTO compras (usuario_id, producto, cantidad, total, tipo_entrega, direccion_entrega, latitud, longitud)
                    VALUES ('$usuario_id', '$nombre', '$cantidad', '$total', '$tipo_entrega', '$direccion', '$latitud', '$longitud')";
            $conn->query($sql);
        }

        // Vaciar carrito al finalizar compra
        unset($_SESSION["carrito"]);

        // Redirigir al ticket
        header("Location: ticket.php");
        exit;
    } else {
        echo "Error: el carrito está vacío.";
    }
}
?>
