<?php
session_start();
if (!isset($_SESSION["usuario"])) {
  header("Location: formulario.php");
  exit;
}

include __DIR__ . '/API/config.php';
if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $producto) {
        $nombre = $producto['nombre'];
        $precio = $producto['precio'];
        $imagen = $producto['imagen'];
        $cantidad = $producto['cantidad'];
        $total_producto = $precio * $cantidad;

        // Guardar en la base de datos cada producto
        $query = "INSERT INTO compras (usuario, producto, imagen, cantidad, total, fecha)
                  VALUES ('$usuario', '$nombre', '$imagen', '$cantidad', '$total_producto', NOW())";
        mysqli_query($conexion, $query);

        // Mostrar en el ticket
        echo "
        <div class='ticket-item'>
            <img src='img/$imagen' alt='$nombre'>
            <h3>$nombre</h3>
            <p>Cantidad: $cantidad</p>
            <p>Precio unitario: $$precio</p>
            <p>Total: $$total_producto</p>
        </div>
        ";
    }

    // Vaciar carrito al final
    unset($_SESSION['carrito']);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Domicilio - 8VA ReBaNaDa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/estilo_index.css">

  <style>
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
  </style>
</head>

<body>
  <!-- 🔹 Encabezado -->
  <header>
    <div class="logo">
      <span>🍕</span>
    </div>
    <h1 class="titulo">8VA ReBaNaDa</h1>

    <div class="acciones-header">
      <div class="botones-superiores">
        <?php if (isset($_SESSION["usuario"])): ?>
            <span class="nombre-usuario">👋 Hola, <?php echo htmlspecialchars($_SESSION["usuario"]); ?></span>
            <a href="logout.php" class="btn-login">Cerrar sesión</a>
        <?php else: ?>
            <a href="formulario.php" class="btn-login">Iniciar Sesión</a>
        <?php endif; ?>
        <button class="btn-carrito">
          <img src="img/carro.png" alt="carrito">
        </button>
      </div>
    </div>
  </header>

  <main>
    <h2>📍 Ingresa tu dirección de entrega en León, Gto</h2>

    <div id="search-container">
      <input id="pac-input" type="text" placeholder="Buscar dirección en León, Gto">
    </div>

    <div id="map"></div>

    <div class="info-direccion" id="info">
      🏠 <strong>Dirección:</strong> <span id="direccion">No seleccionada</span><br>
      📍 <strong>Latitud:</strong> <span id="lat">-</span> | 
      <strong>Longitud:</strong> <span id="lng">-</span>
    </div>

    <!-- 🔹 FORMULARIO OCULTO que se llenará con los datos del mapa -->
    <form id="formDomicilio" action="confirmar_compra.php" method="POST">
      <input type="hidden" name="tipo_entrega" value="domicilio">
      <input type="hidden" name="direccion" id="inputDireccion">
      <input type="hidden" name="latitud" id="inputLatitud">
      <input type="hidden" name="longitud" id="inputLongitud">
      <button type="submit" class="btn-confirmar">✅ Confirmar dirección</button>
    </form>

    <p id="alerta" class="alerta"></p>
  </main>

  <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&libraries=places&callback=initMap">
  </script>

  <script>
    let map, marker, autocomplete, direccionSeleccionada = "", coordenadas = {};

    function initMap() {
      const leon = { lat: 21.123, lng: -101.684 };

      map = new google.maps.Map(document.getElementById("map"), {
        center: leon,
        zoom: 13,
      });

      marker = new google.maps.Marker({
        position: leon,
        map: map,
        draggable: true,
      });

      const input = document.getElementById("pac-input");
      autocomplete = new google.maps.places.Autocomplete(input, {
        componentRestrictions: { country: "mx" },
        fields: ["formatted_address", "geometry"],
      });

      autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();
        if (!place.geometry) return;

        map.panTo(place.geometry.location);
        map.setZoom(16);
        marker.setPosition(place.geometry.location);

        direccionSeleccionada = place.formatted_address;
        coordenadas = {
          lat: place.geometry.location.lat(),
          lng: place.geometry.location.lng()
        };

        actualizarInfo();
      });

      marker.addListener("dragend", () => {
        const pos = marker.getPosition();
        coordenadas = { lat: pos.lat(), lng: pos.lng() };
        direccionSeleccionada = "Ubicación personalizada";
        actualizarInfo();
      });
    }

    function actualizarInfo() {
      document.getElementById("direccion").textContent = direccionSeleccionada || "No seleccionada";
      document.getElementById("lat").textContent = coordenadas.lat ? coordenadas.lat.toFixed(6) : "-";
      document.getElementById("lng").textContent = coordenadas.lng ? coordenadas.lng.toFixed(6) : "-";
    }

    // 🔹 Al enviar el formulario validamos que haya dirección
    document.getElementById("formDomicilio").addEventListener("submit", function (e) {
      if (!direccionSeleccionada || !coordenadas.lat) {
        e.preventDefault();
        document.getElementById("alerta").textContent = "⚠️ Primero selecciona una dirección válida en el mapa.";
        return;
      }

      // Llenamos los inputs ocultos antes de enviar
      document.getElementById("inputDireccion").value = direccionSeleccionada;
      document.getElementById("inputLatitud").value = coordenadas.lat;
      document.getElementById("inputLongitud").value = coordenadas.lng;
    });
  </script>
</body>
</html>
