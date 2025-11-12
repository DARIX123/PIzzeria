<?php
session_start();
include("API/conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: formulario.php");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];
$resultado = $conn->query("SELECT * FROM compras WHERE usuario_id='$usuario_id' ORDER BY fecha_compra DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Compras - 8VA Rebanada</title>
    <link rel="stylesheet" href="css/estilo_index.css">
    <style>
        .compras-container {
            max-width: 900px;
            margin: 150px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .compras-container h1 {
            color: #b22222;
            text-align: center;
            font-family: "Poppins", sans-serif;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #b22222;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #ffe5e5;
        }
        .btn-volver {
            display: inline-block;
            background: #b22222;
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.3s;
            margin-top: 20px;
        }
        .btn-volver:hover {
            background: #d33a2c;
        }
    </style>
</head>
<body>
<header>
    <div class="logo"><span>🍕</span></div>
    <h1 class="titulo">8VA Rebanada</h1>
    <div class="acciones-header">
        
        <a href="logout.php" class="btn-login">Salir</a>
    </div>
</header>

<div class="compras-container">
    <h1>Mis Compras</h1>
    <table>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Entrega</th>
            <th>Fecha</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($fila['producto']) ?></td>
                <td><?= $fila['cantidad'] ?></td>
                <td>$<?= $fila['total'] ?></td>
                <td><?= $fila['tipo_entrega'] ?></td>
                <td><?= $fila['fecha_compra'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <center><a href="menu.php" class="btn-volver">← Volver al Menú</a></center>
</div>

<footer class="pie-pagina">
  <div class="footer-izq">
    © 2025 8VA Rebanada. Todos los derechos reservados.
  </div>
  <div class="footer-der">
    🍕 Hecho con amor y mucho queso
  </div>
</footer>


</body>
</html>
 