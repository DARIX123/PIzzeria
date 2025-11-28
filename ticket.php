<?php
session_start();
include("API/conexion.php");

//  Obtener usuario
if (!isset($_SESSION["usuario"])) {
    header("Location: formulario.php");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];

//  Obtener pedido_id de la sesión o de GET
$pedido_id = $_SESSION['pedido_id'] ?? $_GET['pedido_id'] ?? null;

if (!$pedido_id) {
    die("No hay pedido actual.");
}

//  Obtener todos los productos de este pedido
$resultado = $conn->query( "
    SELECT c.*, p.imagen 
    FROM compras c 
    LEFT JOIN productos p ON c.producto = p.nombre 
    WHERE c.usuario_id='$usuario_id' AND c.pedido_id='$pedido_id'
");

//  Verificar si hay productos
if ($resultado->num_rows === 0) {
    die("No hay productos para este pedido.");
}



require_once __DIR__ . "/phpqrcode/qrlib.php";

//  URL pública de Ngrok 
$ngrokHost = "https://cali-arborescent-cynthia.ngrok-free.dev";

//  ESTA es la URL correcta que Node sí reconoce
//  Necesitamos obtener el token desde la BD
$tokenQuery = $conn->query("SELECT token_entrega FROM compras WHERE pedido_id='$pedido_id' LIMIT 1");
$tokenData = $tokenQuery->fetch_assoc();
$token = $tokenData['token_entrega'] ?? '';

$qrUrl = $ngrokHost . "/verificar_pedido.php?pedido_id=$pedido_id&token=$token";





// Carpeta donde se guardan los QRs
$dir = __DIR__ . "/qrs/";
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

$filename = $dir . "pedido_" . $pedido_id . ".png";

// Crear QR
QRcode::png($qrUrl, $filename, QR_ECLEVEL_L, 5);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Compra - 8VA ReBaNaDa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo_index.css?v=<?php echo time(); ?>">
    <script src="https://unpkg.com/i18next@22.4.9/i18next.min.js"></script>
    <script src="https://unpkg.com/i18next-browser-languagedetector@6.1.4/i18nextBrowserLanguageDetector.min.js"></script>
    <script src="https://unpkg.com/jquery@3.7.1/dist/jquery.min.js"></script>


    <style>
        main { text-align: center; padding: 40px 20px; background: #fafafa; }
        h1 { color: #b22222; }
        table { margin: 20px auto; border-collapse: collapse; width: 90%; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; }
        th { background: #b22222; color: white; }
        td img { width: 80px; height: 80px; border-radius: 10px; object-fit: cover; }
        .boton-regreso { margin-top: 25px; display: inline-block; background: #b22222; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; }
        .boton-regreso:hover { background: #ff4444; }
    </style>
</head>

<body>

<header>
    <div class="logo"><span>🍕</span></div>
    <h1 class="titulo">8VA ReBaNaDa</h1>
</header>

<main>

    <h1 data-i18n="ticket-titulo">🧾 Ticket de Compra</h1>

    <div style="text-align:center; margin:20px;">
        <h3 data-i18n="ticket-qr-instruccion">Escanea tu código QR</h3>

        <!-- Mostrar QR generado -->
        <img src="qrs/pedido_<?php echo $pedido_id; ?>.png"
             style="width:200px; height:200px;">
    </div>

    <table>
        <tr>
            <th data-i18n="col-imagen">Imagen</th>
            <th data-i18n="col-producto">Producto</th>
            <th data-i18n="col-cantidad">Cantidad</th>
            <th data-i18n="col-total">Total</th>
            <th data-i18n="col-tipo">Tipo de entrega</th>
            <th data-i18n="col-direccion">Dirección</th>
        </tr>

        <?php while ($fila = $resultado->fetch_assoc()): 
            $imagen = !empty($fila['imagen']) ? $fila['imagen'] : 'img/default.png';
        ?>
        <tr>
            <td><img src="<?php echo $imagen; ?>" alt="Producto"></td>
            <td><?php echo htmlspecialchars($fila['producto']); ?></td>
            <td><?php echo $fila['cantidad']; ?></td>
            <td>$<?php echo $fila['total']; ?></td>
            <td><?php echo $fila['tipo_entrega']; ?></td>
            <td><?php echo htmlspecialchars($fila['direccion_entrega']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    

    <div style="margin-top: 30px;">
        <a href="index.php" class="boton-regreso" data-i18n="btn-volver">⬅ Volver al inicio</a>
    </div>

</main>

   
</main>

<footer class="pie-pagina">
    <div class="footer-izq">
        <a href="https://maps.app.goo.gl/FVRkTHBrWogSUAgc6" target="_blank" data-i18n="ver-ubicacion">📍 Ver ubicación</a>
    </div>
    <div class="footer-der">
        <p>ALAMEDAS DE VILLAFRANCA</p>
        <p>LOMAS DE LOS CASTILLOS</p>
    </div>
</footer>

<script>
    const pedidoID = "<?php echo $pedido_id; ?>";
    const socketUrl = "wss://cali-arborescent-cynthia.ngrok-free.dev/ws";

    // ---------------------------------------------
    // 1️⃣ MÉTODO RÁPIDO: WEBSOCKET
    // ---------------------------------------------
    console.log("🔵 Iniciando WebSocket...");
    const socket = new WebSocket(socketUrl);

    socket.onopen = () => {
        console.log("✅ Conectado al Socket. Esperando señal...");
        socket.send("pedido_abierto:" + pedidoID);
    };

    socket.onmessage = (event) => {
        const msg = event.data.trim();
        console.log("📩 Señal recibida:", msg);

        if (msg === "pedido_actualizado:" + pedidoID) {
            console.log("🚀 Señal confirmada. Redirigiendo...");
            window.location.href = "ver_pedido.php?pedido_id=" + pedidoID;
        }
    };

    // ---------------------------------------------
    // 2️⃣ MÉTODO SEGURO: POLLING (CONSULTA CADA 3 SEG)
    // (Por si el WebSocket falla o el celular pierde señal)
    // ---------------------------------------------
    setInterval(() => {
        fetch(`API/consultar_estado.php?pedido_id=${pedidoID}`)
            .then(response => response.json())
            .then(data => {
                // Si la base de datos dice que ya está entregado, nos vamos
                if (data.estado === 'entregado') {
                    console.log("🔄 Base de datos actualizada. Redirigiendo...");
                    window.location.href = "ver_pedido.php?pedido_id=" + pedidoID;
                }
            })
            .catch(err => console.error("Error verificando estado:", err));
    }, 3000); // Se ejecuta cada 3 segundos
</script>


<script src="js/traduccion.js"></script>

</body>
</html>