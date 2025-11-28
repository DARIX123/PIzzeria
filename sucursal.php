<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: formulario.php");
    exit;
}

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
  header("Location: menu.php");
  exit;
}

include __DIR__ . '/API/conexion.php';
include __DIR__ . '/API/config.php';

$usuario_id = $_SESSION['usuario_id'];

// Verificar que haya productos en el carrito


// 🔹 Generar un pedido_id único
$pedido_id = time() . '_' . $usuario_id;

// 🔹 Generar token único para este pedido
$token_entrega = bin2hex(random_bytes(16)); // 32 caracteres seguros

// Guardar los productos en la tabla compras
foreach ($_SESSION['carrito'] as $producto) {
    $nombre = $producto['nombre'];
    $precio = $producto['precio'];
    $cantidad = $producto['cantidad'];
    $total_producto = $precio * $cantidad;

    $query = "INSERT INTO compras 
              (pedido_id, usuario_id, producto, cantidad, total, tipo_entrega, fecha_compra, token_entrega)
              VALUES 
              ('$pedido_id', '$usuario_id', '$nombre', '$cantidad', '$total_producto', 'domicilio', NOW(), '$token_entrega')";

    if (!$conn->query($query)) {
        die("Error al guardar la compra: " . $conn->error);
    }
}

// Vaciar carrito después de guardar
unset($_SESSION['carrito']);


$sucursales = [
  [
    "nombre" => "8VA Rebanada - Centro",
    "direccion" => "Calle Principal #123, Centro, León",
    "lat" => 21.1295,
    "lng" => -101.6862,
    "foto" => "img/sucursal1.jpg"
  ],
  [
    "nombre" => "8VA Rebanada - Plaza Mayor",
    "direccion" => "Blvd. Las Torres 450, León",
    "lat" => 21.1542,
    "lng" => -101.6638,
    "foto" => "img/sucursal2.jpg"
  ]
  
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Domicilio - 8VA ReBaNaDa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/estilo_index.css">
  <script src="https://unpkg.com/i18next@22.4.9/i18next.min.js"></script>
  <script src="https://unpkg.com/i18next-browser-languagedetector@6.1.4/i18nextBrowserLanguageDetector.min.js"></script>
  <script src="https://unpkg.com/jquery@3.7.1/dist/jquery.min.js"></script>


  <style>
     #map {
        width: 100%;
        height: 500px;
    }

    /* --- Estilo de la tarjeta que aparece al dar clic en el pin --- */
    .info-window {
        width: 250px;
        font-family: Arial;
    }

    .info-window img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 8px;
    }

    .info-window h3 {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }

    .info-window p {
        margin: 4px 0 0 0;
        font-size: 14px;
        color: #555;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: #fafafa;
      color: #333;
    }

    main {
      padding: 20px;
    }

    h2 {
      text-align: center;
      margin-top: 10px;
    }

    #search-container {
      display: flex;
      justify-content: center;
      margin-bottom: 10px;
    }

    #pac-input {
      width: 90%;
      max-width: 500px;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 10px;
      font-size: 1rem;
      outline: none;
    }

    #map {
      height: 450px;
      width: 100%;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      margin-bottom: 15px;
    }

    .info-direccion {
      text-align: center;
      font-size: 1rem;
      background: #fff;
      padding: 10px;
      border-radius: 8px;
      max-width: 600px;
      margin: 0 auto;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .info-direccion strong {
      color: #b22222;
    }

    .btn-confirmar {
      display: block;
      margin: 20px auto;
      padding: 10px 20px;
      font-size: 1rem;
      border: none;
      background: #b22222;
      color: white;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn-confirmar:hover {
      background: #ff4444;
    }

    .alerta {
      text-align: center;
      color: #b22222;
      font-weight: bold;
      margin-top: 10px;
    }
    #map {
      height: 450px;
      width: 100%;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    .info-sucursal {
      text-align: center;
      background: #fff;
      padding: 15px;
      border-radius: 10px;
      max-width: 500px;
      margin: auto;
    }
    .info-sucursal img {
      width: 100%;
      border-radius: 8px;
      margin-bottom: 10px;
    }
    .btn-recoger {
      background: #28a745;
      color: white;
      padding: 10px 20px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      display: block;
      margin: 10px auto;
      font-size: 1rem;
    }

    #infoSucursal {
    display: none;
    background: white;
    padding: 20px;
    border-radius: 15px;
    width: 100%;
    max-width: 500px;
    margin: 25px auto;
    box-shadow: 0 5px 20px rgba(0,0,0,0.16);
    transition: opacity .3s ease;
}

#infoSucursal img {
    width: 100%;
    border-radius: 12px;
    margin-bottom: 10px;
    object-fit: cover;
    height: 200px;
}

#infoSucursal h3 {
    margin: 0;
    font-size: 1.4rem;
    font-weight: bold;
}

#infoSucursal p {
    margin: 4px 0 15px 0;
    color: #555;
}

.btn-recoger {
    background: #ff5e3a;
    color: white;
    padding: 12px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    width: 100%;
    transition: .2s;
}

.btn-recoger:hover {
    background: #ff3e18;
}

  </style>
</head>

<body>
<header>
    <div class="logo">
      <span>🍕</span>
    </div>
    <h1 class="titulo">8VA ReBaNaDa</h1>

    <div class="acciones-header">
      <div class="botones-superiores">
        <?php if (isset($_SESSION["usuario"])): ?>
            <span class="nombre-usuario">👋 Hola, <?php echo htmlspecialchars($_SESSION["usuario"]); ?></span>
            <a href="logout.php" class="btn-login" data-i18n="cerrar-sesion">Cerrar sesión</a>
        <?php else: ?>
            <a href="formulario.php" class="btn-login" data-i18n="btn-login">Iniciar Sesión</a>
        <?php endif; ?>
        <button class="btn-carrito">
          <img src="img/carro.png" alt="carrito">
        </button>
      </div>
    </div>
  </header>


<main>

  <h2 data-i18n="titulo-sucursal">Selecciona una sucursal</h2>
  <div id="map"></div>

  <div class="info-sucursal" id="infoSucursal" style="display:none;">
      <img id="fotoSucursal" src="" alt="">
      <h3 id="nombreSucursal"></h3>
      <p id="direccionSucursal"></p>

      <form action="confirmar_compra.php" method="POST">
        <input type="hidden" name="pedido_id" value="<?php echo $pedido_id; ?>">
        <input type="hidden" name="tipo_entrega" value="tienda">
        <input type="hidden" name="sucursal" id="inputSucursal">
        <button class="btn-recoger" data-i18n="btn-recoger">✔ Recoger aquí</button>
      </form>
  </div>

</main>

 <script src="js/traduccion.js"></script>

<script>
// -------------------------
//  LISTA DE SUCURSALES
// -------------------------
const sucursales = [
    {
        nombre: "8VA Rebanada - Centro",
        direccion: "Calle Principal #123, Centro, León",
        lat: 21.1295,
        lng: -101.6862,
        imagen: "img/sucursal1.png",
        icono: "img/pizza.png"
    },
    {
        nombre: "8VA Rebanada - Plaza Mayor",
        direccion: "Blvd. Las Torres 450, León",
        lat: 21.1542,
        lng: -101.6638,
        imagen: "img/sucursal2.png",
        icono: "img/pizza.png"
    }
];

// -------------------------
//  INICIAR MAPA (Versión Marcadores Avanzados)
// -------------------------
async function initMap() {
    // Importamos las librerías necesarias
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    const mapa = new Map(document.getElementById("map"), {
        center: { lat: 21.1295, lng: -101.6862 }, // LEÓN
        zoom: 13,
        mapId: "DEMO_MAP_ID" // ⚠️ Necesario para marcadores avanzados (puedes usar este ID de demo)
    });

    // Crear marcadores
    sucursales.forEach((sucursal) => {

        // 1. Crear el elemento HTML del icono (DOM real)
        const iconoImg = document.createElement("img");
        iconoImg.src = sucursal.icono;
        iconoImg.classList.add("pin-rebote"); // 🔥 AQUÍ SE APLICA TU CLASE CSS
        iconoImg.style.width = "50px"; 
        iconoImg.style.height = "50px";
        
        // 2. Crear el Marcador Avanzado usando ese elemento como contenido
        const marker = new AdvancedMarkerElement({
            map: mapa,
            position: { lat: sucursal.lat, lng: sucursal.lng },
            title: sucursal.nombre,
            content: iconoImg, // Le pasamos el elemento HTML animado
        });

        // 3. Crear tarjeta (InfoWindow)
        const infoContent = `
            <div class="info-window">
                <img src="${sucursal.imagen}" alt="Sucursal" style="width:100%;border-radius:10px;margin-bottom:10px;">
                <h3>${sucursal.nombre}</h3>
                <p>${sucursal.direccion}</p>
            </div>
        `;

        const infoWindow = new google.maps.InfoWindow({
            content: infoContent
        });

        // En marcadores avanzados, el evento click se agrega al marcador, no al elemento content
        marker.addListener("click", () => {
            infoWindow.open({
                anchor: marker,
                map: mapa,
            });
            
            // Lógica para llenar el formulario oculto al seleccionar
            document.getElementById("infoSucursal").style.display = "block";
            document.getElementById("fotoSucursal").src = sucursal.imagen;
            document.getElementById("nombreSucursal").textContent = sucursal.nombre;
            document.getElementById("direccionSucursal").textContent = sucursal.direccion;
            document.getElementById("inputSucursal").value = sucursal.nombre;
        });
    });
}
</script>

<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&libraries=marker&callback=initMap">
</script>

</body>
</html>
