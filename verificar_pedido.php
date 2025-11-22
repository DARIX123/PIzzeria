<?php
// --- Cargar conexión ---
require __DIR__ . '/API/conexion.php';

// --- Validar pedido_id ---
if (!isset($_GET['pedido_id'])) {
    echo "❌ Falta el parámetro pedido_id";
    exit;
}

$pedidoId = $_GET['pedido_id'];

// --- Preparar query ---
$query = "UPDATE compras SET estado = 'entregado' WHERE pedido_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pedidoId);

if ($stmt->execute()) {
    // Si afectó filas, el pedido existía
    if ($stmt->affected_rows > 0) {
        echo "✔ Pedido $pedidoId marcado como ENTREGADO.";
    } else {
        echo "⚠ No existe un pedido con ID: $pedidoId";
    }
} else {
    echo "❌ Error al actualizar el pedido.";
}

$stmt->close();
$conn->close();
?>
