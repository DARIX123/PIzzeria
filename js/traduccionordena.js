const resources = {
  es: {
    translation: {
      "inicio": "Inicio",
      "menu": "Menú",
      "ordena": "Ordena",
      "contacto": "Contacto",
      "titulo-tienda": "ELIGE TU TIENDA",
      "contenedor-preferencia": "¿CÓMO PREFIERES RECIBIR TU PIZZA?",
      "opcion": "A DOMICILIO",
      "opcion1": "EN TIENDA", 
    }
  },
  en: {
    translation: {
      "inicio": "Home",
      "menu": "Menu",
      "ordena": "Order",
      "contacto": "Contact",
      "titulo-tienda": "Choose your store",
      "contenedor-preferencia": "How do you prefer to receive your pizza?",
      "opcion": "Delivery",
      "opcion1": "In store",
    }
  },
  fr: {
    translation: {
      "inicio": "Accueil",
      "menu": "Menu",
      "ordena": "Commander",
      "contacto": "Contact",
      "titulo-tienda": "Choisis ton magasin",
      "contenedor-preferencia": "Comment préfères-tu recevoir ta pizza ?",
      "opcion": "À domicile",
      "opcion1": "En magasin",
    }
  },
  it: {
    translation: {
      "inicio": "Home",
      "menu": "Menu",
      "ordena": "Ordina",
      "contacto": "Contatto",
      "titulo-tienda": "Scegli il tuo negozio",
      "contenedor-preferencia": "Come preferisci ricevere la tua pizza?",
      "opcion": "A domicilio",
      "opcion1": "In negozio",
    }
  },
  zh: {
    translation: {
      "inicio": "首页",
      "menu": "菜单",
      "ordena": "订购",
      "contacto": "联系",
      "titulo-tienda": "选择你的商店",
      "contenedor-preferencia": "你想怎样收到你的披萨？",
      "opcion": "外送",
      "opcion1": "在店内",
    }
  },
  pt: {
    translation: {
      "inicio": "Início",
      "menu": "Cardápio",
      "ordena": "Pedir",
      "contacto": "Contato",
      "titulo-tienda": "Escolha sua loja",
      "contenedor-preferencia": "Como você prefere receber sua pizza?",
      "opcion": "Em domicílio",
      "opcion1": "Na loja",
    }
  }
};


// Obtenemos idioma guardado o español por defecto
const idiomaInicial = localStorage.getItem("idioma") || "es";

// Inicializamos i18next
i18next.init({
  lng: idiomaInicial,
  debug: false,
  resources: resources
}, function(err, t) {
  actualizarTextos();
});

/* ===========================================================
    ACTUALIZAR TEXTOS
=========================================================== */

function actualizarTextos() {
  document.querySelectorAll("[data-i18n]").forEach(el => {
    const key = el.getAttribute("data-i18n");
    el.innerHTML = i18next.t(key);
  });
}

/* ===========================================================
    ELEMENTOS DEL MODAL
=========================================================== */

const modal = document.getElementById("modal-config");
const btnConfig = document.getElementById("btn-config");
const cerrarConfig = document.getElementById("cerrar-config");

// Switches
const darkToggle = document.getElementById("modo-oscuro");
const readToggle = document.getElementById("modo-lectura");
const selectIdioma = document.getElementById("select-idioma");

/* ===========================================================
    ABRIR / CERRAR MODAL
=========================================================== */

btnConfig?.addEventListener("click", () => {
  modal.style.display = "flex";
  cargarSwitches(); // ← IMPORTANTE: marca los switches correctamente
});

cerrarConfig?.addEventListener("click", () => {
  modal.style.display = "none";
});

window.addEventListener("click", (e) => {
  if (e.target === modal) modal.style.display = "none";
});

/* ===========================================================
    GUARDAR CONFIGURACIONES
=========================================================== */

darkToggle?.addEventListener("change", () => {
  document.body.classList.toggle("dark-mode", darkToggle.checked);
  localStorage.setItem("modoOscuro", darkToggle.checked);
});

readToggle?.addEventListener("change", () => {
  document.body.classList.toggle("read-mode", readToggle.checked);
  localStorage.setItem("modoLectura", readToggle.checked);
});

selectIdioma?.addEventListener("change", () => {
  const idioma = selectIdioma.value;
  localStorage.setItem("idioma", idioma);
  i18next.changeLanguage(idioma);
  actualizarTextos();
});

/* ===========================================================
    CARGAR CONFIGURACIONES EN TODAS LAS PÁGINAS
=========================================================== */

document.addEventListener("DOMContentLoaded", () => {

  // ---- Modo oscuro ----
  const modoOscuro = localStorage.getItem("modoOscuro") === "true";
  if (darkToggle) darkToggle.checked = modoOscuro;
  document.body.classList.toggle("dark-mode", modoOscuro);

  // ---- Modo lectura ----
  const modoLectura = localStorage.getItem("modoLectura") === "true";
  if (readToggle) readToggle.checked = modoLectura;
  document.body.classList.toggle("read-mode", modoLectura);

  // ---- Idioma ----
  const idiomaGuardado = localStorage.getItem("idioma") || "es";
  if (selectIdioma) selectIdioma.value = idiomaGuardado;
  i18next.changeLanguage(idiomaGuardado);

  // Refrescar textos
  setTimeout(actualizarTextos, 100);
});

/* ===========================================================
    MARCAR SWITCHES AL ABRIR EL MODAL
=========================================================== */

function cargarSwitches() {
  if (darkToggle)
    darkToggle.checked = localStorage.getItem("modoOscuro") === "true";

  if (readToggle)
    readToggle.checked = localStorage.getItem("modoLectura") === "true";

  if (selectIdioma)
    selectIdioma.value = localStorage.getItem("idioma") || "es";
}

