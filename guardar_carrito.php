<?php
session_start();
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["carrito"])) {
    $_SESSION["carrito"] = $data["carrito"];
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "No se recibieron datos"]);
}
?>
