<?php
  // Cargar la API Key de manera segura
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
    }
    h2 { text-align: center; }
    #search-container {
      display: flex;
      justify-content: center;
      margin-bottom: 10px;
    }
    gmpx-place-autocomplete {
      width: 90%;
      max-width: 500px;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 8px;
      font-size: 1rem;
    }
    #map {
      height: 450px;
      width: 100%;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

  <h2>📍 Ingresa tu dirección de entrega en León, Gto</h2>
  <div id="search-container">
    <gmpx-place-autocomplete placeholder="Buscar dirección en León, Gto"></gmpx-place-autocomplete>
  </div>
  <div id="map"></div>

  <!-- Carga la API Key desde PHP -->
  <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&libraries=maps,places&v=weekly&callback=initMap">
  </script>

  <script>
    let map, marker;

    function initMap() {
      const leon = { lat: 21.123, lng: -101.684 };

      map = new google.maps.Map(document.getElementById("map"), {
        center: leon,
        zoom: 13,
        mapId: "MAPA_LEON"
      });

      marker = new google.maps.marker.AdvancedMarkerElement({
        map,
        position: leon,
        title: "Ubicación inicial"
      });

      const autocomplete = document.querySelector("gmpx-place-autocomplete");
      autocomplete.country = "mx";

      autocomplete.addEventListener("gmp-placeselect", (event) => {
        const place = event.place;
        if (!place || !place.location) return;

        map.panTo(place.location);
        map.setZoom(16);
        marker.position = place.location;
      });
    }
  </script>

</body>
</html>
