
const API_URL = "http://localhost/pizzeria/API/get_productos.php"; // ajusta el path si cambia
const contenedor = document.getElementById("contenedor-productos");
const buscador = document.getElementById("buscador");
const btnVoz = document.getElementById("btn-voz");
const botones = document.querySelectorAll(".btn-filtro");

let categoriaActual = "todo";
let busquedaActual = "";

// 🔹 Cargar productos desde el servidor
async function cargarProductos() {
    const url = `${API_URL}?categoria=${categoriaActual}&busqueda=${encodeURIComponent(busquedaActual)}`;
    const resp = await fetch(url);
    const data = await resp.json();

    contenedor.innerHTML = "";

    if (data.length === 0) {
        contenedor.innerHTML = "<p class='sin-resultados'>No hay productos disponibles.</p>";
        return;
    }

    data.forEach(prod => {
        const card = document.createElement("div");
        card.classList.add("producto");

        card.innerHTML = `
            <img src="${prod.imagen}" alt="${prod.nombre}">
            <h3>${prod.nombre}</h3>
            <p>${prod.descripcion}</p>
            <p class="precio">$${prod.precio}</p>

            <div class="contador">
                <button class="menos">−</button>
                <span class="cantidad">1</span>
                <button class="mas">+</button>
            </div>

            <button class="btn-agregar">Agregar al carrito</button>
        `;

        contenedor.appendChild(card);

        // Funcionalidad del contador
        const menos = card.querySelector(".menos");
        const mas = card.querySelector(".mas");
        const cantidad = card.querySelector(".cantidad");

        menos.addEventListener("click", () => {
            let val = parseInt(cantidad.textContent);
            if (val > 1) cantidad.textContent = val - 1;
        });

        mas.addEventListener("click", () => {
            let val = parseInt(cantidad.textContent);
            if (val < 10) cantidad.textContent = val + 1;
        });
    });
}

// 🔹 Filtrar por categoría
botones.forEach(btn => {
    btn.addEventListener("click", () => {
        categoriaActual = btn.dataset.categoria;
        cargarProductos();
    });
});

// 🔹 Filtro por texto
buscador.addEventListener("input", () => {
    busquedaActual = buscador.value;
    cargarProductos();
});

// 🔹 Reconocimiento de voz
btnVoz.addEventListener("click", () => {
    if (!('webkitSpeechRecognition' in window)) {
        alert("Tu navegador no soporta reconocimiento de voz.");
        return;
    }

    const recognition = new webkitSpeechRecognition();
    recognition.lang = "es-ES";
    recognition.start();

    recognition.onresult = (event) => {
        const texto = event.results[0][0].transcript;
        buscador.value = texto;
        busquedaActual = texto;
        cargarProductos();
    };
});

// 🔹 Cargar todos los productos al iniciar
cargarProductos();

