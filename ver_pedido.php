<?php
include("API/conexion.php");

if (!isset($_GET['pedido_id'])) {
    die("Falta ID del pedido");
}

$pedido_id = ($_GET['pedido_id']);

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
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedido #<?php echo $pedido_id; ?></title>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #fff8f0;
        margin: 0;
        padding: 0;
    }

    h2 {
        text-align: center;
        color: #b22222;
        margin-top: 20px;
        font-size: 2rem;
        font-weight: bold;
    }

    table {
        width: 90%;
        margin: 30px auto;
        border-collapse: collapse;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0,0,0,0.15);
    }

    td, th {
        border: 1px solid #ccc;
        padding: 12px;
        text-align: center;
    }

    th {
        background-color: #b22222;
        color: white;
        font-size: 1.2rem;
    }

    /* 🔥 ANIMACIÓN DE ENTREGADO */
    #entregado {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.85);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        z-index: 9999;
        color: white;
        font-size: 3rem;
        font-weight: bold;
        text-align: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.5s ease;
    }

    #entregado.mostrar {
        opacity: 1;
        visibility: visible;
    }

    .pizza {
        width: 180px;
        height: 180px;
        background-image: url('img/pizza.gif');
        background-size: cover;
        margin-bottom: 20px;
        animation: girar 2s infinite linear;
    }

    @keyframes girar {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .texto {
        font-size: 3rem;
        color: #ffdd57;
        text-shadow: 3px 3px 10px black;
        animation: aparecer 1s ease-in-out;
    }

    @keyframes aparecer {
        from { opacity: 0; transform: scale(0.5); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
</head>

<body>

<h2>Pedido #<?php echo $pedido_id; ?></h2>

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
  <td>$<?php echo $f['total']; ?></td>
</tr>
<?php endwhile; ?>
</table>

<!-- 🔥 ANIMACIÓN DE ENTREGADO -->
<div id="entregado">
    <div class="pizza"></div>
    <div class="texto">¡PEDIDO ENTREGADO! 🍕🔥</div>
</div>


<script>
// Conexión WebSocket al servidor NGROK (WSS)
const socket = new WebSocket("wss://multilobular-guarded-michelle.ngrok-free.dev");

socket.onopen = () => {
    console.log("🔵 Conectado WebSocket");

    // Avisar que este pedido está abierto en el celular
    socket.send("usuario_abierto:<?php echo $pedido_id; ?>");
};

socket.onmessage = (event) => {
    console.log("📩 Mensaje:", event.data);

    if (event.data.includes("verificado")) {
        // Mostrar animación
        const animacion = document.getElementById("entregado");
        animacion.classList.add("mostrar");

        // Ocultar después de 3 segundos
        setTimeout(() => {
            animacion.classList.remove("mostrar");
        }, 3000);
    }
};
</script>

</body>
</html>
