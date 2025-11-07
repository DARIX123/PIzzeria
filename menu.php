<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú | 8VA ReBaNaDa</title>
    <link rel="stylesheet" href="css/estilo_menu.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<header>
    <div class="logo"><span>🍕</span></div>
    <h1 class="titulo">8VA ReBaNaDa</h1>
    <div class="acciones-header">
        <a href="formulario.php" class="btn-login">Iniciar Sesión</a>
        <button class="btn-carrito"><img src="img/carro.png" alt="carrito"></button>
    </div>
</header>

<main>
    <div class="botones-inferiores">
            <a href="index.php" class="btn-login">Inicio</a>
            <a href="menu.php" class="btn-login">Menu</a>
            <a href="ordena.php" class="btn-login">Ordena</a>
            <a href="contacto.php" class="btn-login">Contacto</a>
        </div>

    <div class="linea"></div>

    
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
</script>
</body>
</html>
