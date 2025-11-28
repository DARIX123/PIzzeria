<?php
session_start(); // 🔹 IMPORTANTE: Iniciar sesión para leer $_SESSION['rol']
include("API/conexion.php");

if (!isset($_GET['pedido_id'])) {
    die("Falta ID del pedido");
}

$pedido_id = $_GET['pedido_id'];

// 🔹 Detectar el rol (si no hay sesión, asumimos que es un cliente externo/invitado)
$rol_actual = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'cliente';

$res = $conn->query("
    SELECT c.*, p.imagen
    FROM compras c
    LEFT JOIN productos p ON c.producto = p.nombre
    WHERE c.pedido_id = '$pedido_id'
");

if ($res->num_rows === 0) {
    die("No se encontró el pedido");
}

// Obtener estado general del pedido
$estadoQuery = $conn->query("SELECT estado FROM compras WHERE pedido_id='$pedido_id' LIMIT 1");
$estadoData = $estadoQuery->fetch_assoc();
$estado = $estadoData['estado'] ?? 'pendiente';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="css/pedido.css?v=<?php echo time(); ?>">

<title>Pedido #<?php echo $pedido_id; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

</head>
<div id="loading">
    <img src="img/pizza.png" class="loader-pizza">
</div>

<body>
<canvas id="pizzas-canvas"></canvas>


<header>
    <h1>Pedido #<?php echo $pedido_id; ?></h1>
</header>

<div class="estado">
    Estado: <span id="estado"><?php echo ($estado === 'entregado') ? 'Entregado ✅' : ucfirst($estado); ?></span>
</div>

<div class="productos">
<?php while ($f = $res->fetch_assoc()): ?>
    <div class="card-producto">
        <img src="<?php echo $f['imagen'] ?: 'img/default.png'; ?>" alt="<?php echo $f['producto']; ?>">
        <h3><?php echo $f['producto']; ?></h3>
        <p>Cantidad: <?php echo $f['cantidad']; ?></p>
        <p>Total: $<?php echo $f['total']; ?></p>
    </div>
<?php endwhile; ?>
</div>

<div class="botones">
    <?php if ($rol_actual === 'repartidor'): ?>
        <a href="panel_repartidor.php" style="background-color: #333;">⬅ Volver al Panel</a>
    <?php else: ?>
        <a href="index.php"> Inicio</a>
        <a href="mis_compras.php"> Mis Compras</a>
    <?php endif; ?>
</div>

<div id="entregado">
    <div class="pizza"></div>
    <div class="texto">¡PEDIDO ENTREGADO! 🍕🔥</div>
</div>

<script>
    window.addEventListener("load", function() {
        document.getElementById("loading").style.display = "none";
    });
</script>


<script>
// WebSocket al servidor Node.js/Ngrok
const socket = new WebSocket("wss://cali-arborescent-cynthia.ngrok-free.dev/ws");

socket.onopen = () => {
    console.log("🔵 Conectado WebSocket");
    socket.send("pedido_abierto:<?php echo $pedido_id; ?>");
};

socket.onmessage = (event) => {
    console.log("📩 Mensaje:", event.data);
    if(event.data === "pedido_actualizado:<?php echo $pedido_id; ?>") {
        const animacion = document.getElementById("entregado");
        animacion.classList.add("mostrar");
        document.getElementById("estado").innerText = "Entregado ✅";
        setTimeout(() => { animacion.classList.remove("mostrar"); }, 3000);
    }
};
</script>
<script src="js/traduccion.js"></script>
<script src="js/pizzas_particles.js"></script>

</body>
</html>