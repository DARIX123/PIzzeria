<?php
session_start();
include("API/conexion.php");

// 1. SEGURIDAD: Solo un repartidor logueado puede verificar entregas
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'repartidor') {
    die("❌ Error: Debes iniciar sesión como repartidor para escanear este código.");
}

if (!isset($_GET['pedido_id']) || !isset($_GET['token'])) {
    die("❌ Falta ID o token del pedido.");
}

$pedido_id = $_GET['pedido_id'];
$token = $_GET['token'];
$repartidor_id = $_SESSION['usuario_id']; // TU ID DE REPARTIDOR

// 2. Verificar que el pedido existe y el token es correcto
$resToken = $conn->query("SELECT * FROM compras WHERE pedido_id='$pedido_id' AND token_entrega='$token'");

if($resToken->num_rows === 0) {
    die("❌ Pedido no válido o token de seguridad incorrecto.");
}

$fila = $resToken->fetch_assoc();

// Si ya estaba entregado, solo redirigimos para ver el ticket
if($fila['estado'] === 'entregado'){
    header("Location: ver_pedido.php?pedido_id=$pedido_id");
    exit;
}

// 3. ACTUALIZACIÓN CLAVE: Guardamos el estado Y tu ID de repartidor
// Sin 'repartidor_id', este pedido no aparecería en tu historial.
$update = $conn->query("
    UPDATE compras 
    SET estado = 'entregado', 
        repartidor_id = '$repartidor_id'
         
    WHERE pedido_id = '$pedido_id'
");

if ($update) {
    // 4. Notificación WebSocket y Redirección
    // Usamos JavaScript aquí para asegurar que el socket se envíe antes de cambiar de página
    ?>
    <!DOCTYPE html>
    <html>
    <body style="background: black; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif;">
        <h2 style="text-align:center;">✅ Pedido Verificado<br>Procesando...</h2>
        <script>
            // Conectar al WebSocket para avisar al cliente que su pizza llegó
            const socket = new WebSocket("wss://cali-arborescent-cynthia.ngrok-free.dev/ws");
            
            socket.onopen = () => {
                // Enviamos la señal "pedido_actualizado"
                socket.send("pedido_actualizado:<?php echo $pedido_id; ?>");
                
                // Redirigir al ticket después de enviar el aviso
                setTimeout(() => {
                    window.location.href = "ver_pedido.php?pedido_id=<?php echo $pedido_id; ?>";
                }, 500);
            };

            // Fallback: Si el socket tarda mucho, redirigir de todos modos
            setTimeout(() => {
                window.location.href = "ver_pedido.php?pedido_id=<?php echo $pedido_id; ?>";
            }, 2000);
        </script>
    </body>
    </html>
    <?php
    exit;
} else {
    echo "❌ Error al actualizar la base de datos: " . $conn->error;
}
?>