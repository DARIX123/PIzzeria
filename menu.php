<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://unpkg.com/i18next@22.4.9/i18next.min.js"></script>
    <script src="https://unpkg.com/i18next-browser-languagedetector@6.1.4/i18nextBrowserLanguageDetector.min.js"></script>
    <script src="https://unpkg.com/jquery@3.7.1/dist/jquery.min.js"></script>
    <meta charset="UTF-8">
    <title>Menú | 8VA ReBaNaDa</title>
    <link rel="stylesheet" href="css/estilo_menu.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<header>
  
    <?php session_start(); ?>

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
                <span id="contador-carrito">0</span>
            </button>
        </div>
    </div>
</header>

<main>
    <div class="botones-inferiores">
            <a href="index.php" class="btn-login" >Inicio</a>
            <a href="menu.php" class="btn-login">Menu</a>
            <a href="ordena.php" class="btn-login">Ordena</a>
            <a href="contacto.php" class="btn-login">Contacto</a>
        </div>

    <div class="linea2"></div>

    
    <!-- 🔹 BARRA DE BUSQUEDA CON MICRO -->
    <div class="buscador-contenedor">
        <input type="text" id="busqueda" placeholder="Buscar producto...">
        <button id="btn-voz">🎤</button>
    </div>

    <!-- 🔹 BOTONES DE CATEGORÍA -->
    <div class="categorias">
        <button class="btn-cat" data-cat="todo">Ver todo</button>
        <button class="btn-cat" data-cat="pizzas">Pizzas</button>
        <button class="btn-cat" data-cat="pollo">Pollo</button>
        <button class="btn-cat" data-cat="bebidas">Bebidas</button>
        <button class="btn-cat" data-cat="postres">Postres</button>
    </div>

    

    <!-- 🔹 CONTENEDOR DE PRODUCTOS -->
    <div id="productos" class="contenedor-productos"></div>
    
</main>

<div id="panel-carrito" class="panel-carrito">
    <h2>TU CARRO REBANADO!!!</h2>
    <div id="lista-carrito"></div>
    <div class="total-carrito">
        <p>Total: <span id="total-precio">$0.00</span></p>
        <button id="btn-pagar">PAGAR</button>
    </div>
</div>

<div id="overlay-carrito" class="overlay-carrito"></div>


<footer class="pie-pagina">
    <div class="footer-izq">
        <a href="https://maps.app.goo.gl/FVRkTHBrWogSUAgc6" target="_blank">📍 Ver ubicación</a>
    </div>
    <div class="footer-der">
        <p>ALAMEDAS DE VILLAFRANCA</p>
        <p>LOMAS DE LOS CASTILLOS</p>
    </div>
</footer>

<script>
const contenedor = document.getElementById('productos');
const inputBusqueda = document.getElementById('busqueda');
const botonesCat = document.querySelectorAll('.btn-cat');
const btnVoz = document.getElementById('btn-voz');

// ✅ Cargar productos desde la API
async function cargarProductos(categoria = 'todo', busqueda = '') {
    try {
        const url = `API/get_productos.php?categoria=${encodeURIComponent(categoria)}&busqueda=${encodeURIComponent(busqueda)}`;
        const res = await fetch(url);
        const data = await res.json();

        contenedor.innerHTML = '';
        if (data.length === 0) {
            contenedor.innerHTML = '<p class="no-result">No se encontraron productos.</p>';
            return;
        }

        data.forEach(prod => {
            const card = document.createElement('div');
            card.classList.add('tarjeta-producto');
            card.innerHTML = `
                <img src="${prod.imagen}" alt="${prod.nombre}">
                <h3>${prod.nombre}</h3>
                <p class="desc">${prod.descripcion}</p>
                <p class="precio">$${prod.precio}</p>
            `;
            contenedor.appendChild(card);
        });
    } catch (e) {
        contenedor.innerHTML = '<p class="error">Error al cargar los productos.</p>';
        console.error(e);
    }
}

// 🔹 Eventos de categorías
botonesCat.forEach(btn => {
    btn.addEventListener('click', () => {
        const categoria = btn.dataset.cat;
        cargarProductos(categoria);
    });
});

// 🔹 Evento de búsqueda
inputBusqueda.addEventListener('input', () => {
    const texto = inputBusqueda.value.trim();
    cargarProductos('todo', texto);
});

// 🔹 Reconocimiento de voz
if ('webkitSpeechRecognition' in window) {
    const recognition = new webkitSpeechRecognition();
    recognition.lang = 'es-ES';
    recognition.continuous = false;
    recognition.interimResults = false;

    btnVoz.addEventListener('click', () => {
        recognition.start();
        btnVoz.textContent = '🎙️ Escuchando...';
    });

    recognition.onresult = (event) => {
        const texto = event.results[0][0].transcript;
        inputBusqueda.value = texto;
        cargarProductos('todo', texto);
        btnVoz.textContent = '🎤';
    };

    recognition.onerror = () => {
        btnVoz.textContent = '🎤';
    };
} else {
    btnVoz.disabled = true;
    btnVoz.title = 'Tu navegador no soporta reconocimiento de voz';
}

// 🔹 Cargar todo al iniciar
cargarProductos();


// -------------------------------
// 🛒 LÓGICA DEL CARRITO
// -------------------------------
let carrito = [];

document.querySelector('.btn-carrito').addEventListener('click', () => {
  document.getElementById('panel-carrito').classList.toggle('active');
});

// ✅ Modificar renderizado de productos para incluir botones
async function cargarProductos(categoria = 'todo', busqueda = '') {
  try {
    const url = `API/get_productos.php?categoria=${encodeURIComponent(categoria)}&busqueda=${encodeURIComponent(busqueda)}`;
    const res = await fetch(url);
    const data = await res.json();

    contenedor.innerHTML = '';
    if (data.length === 0) {
      contenedor.innerHTML = '<p class="no-result">No se encontraron productos.</p>';
      return;
    }

    data.forEach(prod => {
      const card = document.createElement('div');
      card.classList.add('tarjeta-producto');
      card.innerHTML = `
        <img src="${prod.imagen}" alt="${prod.nombre}">
        <h3>${prod.nombre}</h3>
        <p class="desc">${prod.descripcion}</p>
        <p class="precio">$${prod.precio}</p>
        <div class="acciones-producto">
          <button class="btn-menos">–</button>
          <span class="cantidad">1</span>
          <button class="btn-mas">+</button>
        </div>
        <button class="btn-agregar">Agregar al carrito</button>
      `;
      const btnMas = card.querySelector('.btn-mas');
      const btnMenos = card.querySelector('.btn-menos');
      const cantidad = card.querySelector('.cantidad');
      const btnAgregar = card.querySelector('.btn-agregar');

      btnMas.addEventListener('click', () => {
        cantidad.textContent = parseInt(cantidad.textContent) + 1;
      });

      btnMenos.addEventListener('click', () => {
        if (parseInt(cantidad.textContent) > 1)
          cantidad.textContent = parseInt(cantidad.textContent) - 1;
      });

      btnAgregar.addEventListener('click', () => {
        agregarAlCarrito(prod, parseInt(cantidad.textContent));
      });

      contenedor.appendChild(card);
    });
  } catch (e) {
    contenedor.innerHTML = '<p class="error">Error al cargar los productos.</p>';
    console.error(e);
  }
}

// -------------------------------
// FUNCIONES DE CARRITO
// -------------------------------
function agregarAlCarrito(prod, cantidad) {
  const existente = carrito.find(item => item.id === prod.id);
  if (existente) {
    existente.cantidad += cantidad;
  } else {
    carrito.push({ ...prod, cantidad });
  }
  actualizarCarrito();
}

function actualizarCarrito() {
  const lista = document.getElementById('lista-carrito');
  const contador = document.getElementById('contador-carrito');
  const totalPrecio = document.getElementById('total-precio');

  lista.innerHTML = '';
  let total = 0;

  carrito.forEach(item => {
    const div = document.createElement('div');
    div.classList.add('item-carrito');
    div.innerHTML = `
      <img src="${item.imagen}" alt="${item.nombre}">
      <div>
        <p>${item.nombre}</p>
        <p>${item.cantidad} × $${item.precio}</p>
      </div>
    `;
    lista.appendChild(div);
    total += item.precio * item.cantidad;
  });

  contador.textContent = carrito.reduce((acc, item) => acc + item.cantidad, 0);
  totalPrecio.textContent = `$${total.toFixed(2)}`;
}

// -------------------------------
// BOTÓN PAGAR
// -------------------------------
document.getElementById('btn-pagar').addEventListener('click', async () => {
  if (carrito.length === 0) {
    alert('🛒 Agrega productos antes de continuar con tu orden.');
    return;
  }

  try {
    const res = await fetch("guardar_carrito.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ carrito }),
    });

    const data = await res.json();
    if (data.success) {
      window.location.href = "ordena.php";
    } else {
      alert("Error al guardar el carrito: " + (data.error || ""));
    }
  } catch (error) {
    console.error("Error:", error);
    alert("No se pudo guardar el carrito.");
  }
});



// -------------------------------
// 🧠 CIERRE SUAVE DEL CARRITO
// -------------------------------
const overlay = document.getElementById('overlay-carrito');
const panelCarrito = document.getElementById('panel-carrito');
const btnCarrito = document.querySelector('.btn-carrito');

// Abrir carrito
btnCarrito.addEventListener('click', () => {
  panelCarrito.classList.add('active');
  overlay.classList.add('active');
});

// Cerrar al hacer clic fuera
overlay.addEventListener('click', () => {
  panelCarrito.classList.remove('active');
  overlay.classList.remove('active');
});

// (Opcional) cerrar con tecla ESC
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    panelCarrito.classList.remove('active');
    overlay.classList.remove('active');
  }
});


</script>
</body>
</html>
