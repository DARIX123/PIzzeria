/* ===========================================================
    SISTEMA DE TRADUCCIÓN
=========================================================== */

const resources = {
  es: {
    translation: {
      "inicio": "Inicio",
      "menu": "Menú",
      "ordena": "Ordena",
      "contacto": "Contacto",
      "bienvenida": "Aquí la pizza no se comparte, se conquista. Bienvenidos A la 8VA Rebanada",
      "cerrar-sesion": "Cerrar Sesion",
      "btn-login": "Iniciar Sesión",
    }
  },
  en: {
    translation: {
      "inicio": "Home",
      "menu": "Menu",
      "ordena": "Order",
      "contacto": "Contact",
      "bienvenida": "Here, pizza isn’t shared, it’s conquered. Welcome to the 8th Slice.",
      "cerrar-sesion": "Log Out",
      "btn-login": "Log in",
    }
  },
  fr: {
    translation: {
      "inicio": "Accueil",
      "menu": "Menu",
      "ordena": "Commander",
      "contacto": "Contact",
      "bienvenida": "Ici, la pizza ne se partage pas, elle se conquiert. Bienvenue à la 8e Part",
      "cerrar-sesion": "déconnexion",
      "btn-login": "Se connecter",
    }
  },
  it: {
    translation: {
      "inicio": "Home",
      "menu": "Menu",
      "ordena": "Ordina",
      "contacto": "Contatto",
      "bienvenida": "Qui la pizza non si condivide, si conquista. Benvenuti all’8ª Fetta.",
      "cerrar-sesion": "disconnettersi",
      "btn-login": "Accedi",
    }
  },
  zh: {
    translation: {
      "inicio": "首页",
      "menu": "菜单",
      "ordena": "订购",
      "contacto": "联系",
      "bienvenida": "在这里，披萨不是分享的，而是征服的。欢迎来到第8片",
      "btn-login": "登录",
    }
  },
  pt: {
    translation: {
      "inicio": "Início",
      "menu": "Cardápio",
      "ordena": "Pedir",
      "contacto": "Contato",
      "bienvenida": "Aqui a pizza não se divide, conquista-se. Bem-vindos à 8ª Fatia.",
      "btn-login": "Entrar",
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
