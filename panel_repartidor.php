<?php
session_start();
include("API/conexion.php");
include("roles.php");

// 🔹 Solo repartidores pueden acceder
permitirSolo(['repartidor']);

// 🔹 Obtener pedidos completados asignados a este repartidor
$repartidor_id = $_SESSION['usuario_id'];
$pedidosCompletados = $conn->query("
    SELECT c.*, p.imagen 
    FROM compras c 
    LEFT JOIN productos p ON c.producto = p.nombre 
    WHERE c.estado='entregado' AND c.repartidor_id = '$repartidor_id'
    ORDER BY c.id DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Repartidor - 8VA ReBaNaDa</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/estilo_repartidor.css?v=<?php echo time(); ?>">
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0; }
header { background:#b22222; color:white; padding:15px; text-align:center; }
h1 { margin:0; }
main { padding:20px; }
button, .btn { cursor:pointer; }
.panel-btn { display:block; width:90%; max-width:400px; margin:20px auto; padding:20px; font-size:1.2rem; background:#b22222; color:white; border:none; border-radius:10px; transition:0.3s; }
.panel-btn:hover { background:#ff4444; }
table { width:90%; max-width:900px; margin:20px auto; border-collapse: collapse; background:white; border-radius:10px; overflow:hidden; box-shadow:0 0 10px rgba(0,0,0,0.1); }
th, td { padding:12px; border:1px solid #ddd; text-align:center; }
th { background:#b22222; color:white; }
td img { width:60px; height:60px; border-radius:8px; object-fit:cover; }
.ver-ticket { background:#228B22; color:white; padding:5px 10px; border-radius:5px; text-decoration:none; }
.ver-ticket:hover { background:#32CD32; }
#scanner { width:100%; max-width:400px; margin:20px auto; }
#regresar { display:block; text-align:center; margin:20px auto; color:#b22222; text-decoration:none; font-weight:bold; }
</style>
</head>
<body>

<header>
    <h1>Panel Repartidor</h1>
    <p>Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?></p>

</header>

<main>
    <button class="panel-btn" id="btnEscanear">📷 Escanear Pedido</button>

    <!-- Escaner -->
    <div id="scanner" style="display:none;"></div>

    <!-- Pedidos completados -->
    <h2 style="text-align:center;">Pedidos Completados</h2>
    <table>
        <tr>
            <th>Imagen</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Tipo de entrega</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
        <?php while($f = $pedidosCompletados->fetch_assoc()): ?>
        <tr>
            <td><img src="<?php echo !empty($f['imagen']) ? $f['imagen'] : 'img/default.png'; ?>"></td>
            <td><?php echo htmlspecialchars($f['producto']); ?></td>
            <td><?php echo $f['cantidad']; ?></td>
            <td>$<?php echo $f['total']; ?></td>
            <td><?php echo $f['tipo_entrega']; ?></td>
            <td><?php echo htmlspecialchars($f['direccion_entrega']); ?></td>
            <td>
                <a href="ver_pedido.php?pedido_id=<?php echo $f['pedido_id']; ?>" class="ver-ticket" target="_blank">Ver Ticket</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="panel_repartidor.php" id="regresar">🔄 Volver al panel</a>
</main>

<!-- 🔹 Librería para escaneo de QR -->
<script src="js/html5-qrcode.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const btnEscanear = document.getElementById('btnEscanear');
const scannerDiv = document.getElementById('scanner');
let html5QrcodeScanner;

btnEscanear.addEventListener('click', () => {
    scannerDiv.style.display = 'block';
    html5QrcodeScanner = new Html5Qrcode("scanner");

    Html5Qrcode.getCameras().then(cameras => {
        if(cameras && cameras.length){
            // Buscar la cámara trasera
            let backCamera = cameras.find(cam => cam.label.toLowerCase().includes("back") || cam.label.toLowerCase().includes("environment"));
            let cameraId = backCamera ? backCamera.id : cameras[0].id; // Si no hay trasera, usar la primera

            html5QrcodeScanner.start(
                cameraId,
                { fps: 10, qrbox: 250 },
                qrCodeMessage => {
                    console.log("QR escaneado:", qrCodeMessage);
                    // Detener escáner antes de redirigir
                    html5QrcodeScanner.stop().then(() => {
                        window.location.href = qrCodeMessage;
                    });
                },
                errorMessage => {
                    console.warn("Error QR:", errorMessage);
                }
            );
        }
    }).catch(err => console.error("No se pudo acceder a las cámaras:", err));
})
});
</script>

</body>
</html>
