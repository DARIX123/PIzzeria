<?php
include("conexion.php");

if (!isset($_GET['pedido_id'])) {
    echo json_encode(['status' => 'error']);
    exit;
}

$pedido_id = $_GET['pedido_id'];

// Consultamos solo el estado para ser rápidos
$sql = "SELECT estado FROM compras WHERE pedido_id = '$pedido_id' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['estado' => $row['estado']]);
} else {
    echo json_encode(['estado' => 'no_encontrado']);
}
?>