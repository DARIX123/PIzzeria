<?php
  include __DIR__ . '/API/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Domicilio - Mapa de Entrega</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      background: #fafafa;
      color: #333;
    }

    h2 {
      text-align: center;
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

  <h2>📍 Ingresa tu dirección de entrega en León, Gto</h2>

  <div id="search-container">
    <input id="pac-input" type="text" placeholder="Buscar dirección en León, Gto">
  </div>

  <div id="map"></div>

  <div class="info-direccion" id="info">
    🏠 <strong>Dirección:</strong> <span id="direccion">No seleccionada</span><br>
    📍 <strong>Latitud:</strong> <span id="lat">-</span> | <strong>Longitud:</strong> <span id="lng">-</span>
  </div>

  <button id="confirmar" class="btn-confirmar">✅ Confirmar dirección</button>
  <p id="alerta" class="alerta"></p>

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

    // 🔹 Confirmar dirección
    document.getElementById("confirmar").addEventListener("click", () => {
      if (!direccionSeleccionada || !coordenadas.lat) {
        document.getElementById("alerta").textContent = "⚠️ Primero selecciona una dirección válida en el mapa.";
        return;
      }

      // Guardar datos en localStorage (para usarlos en ticket.php)
      localStorage.setItem("direccion_entrega", direccionSeleccionada);
      localStorage.setItem("lat_entrega", coordenadas.lat);
      localStorage.setItem("lng_entrega", coordenadas.lng);

      // Redirigir al ticket
      window.location.href = "ticket.php";
    });
  </script>

</body>
</html>
