const resources = {
  es: {
    translation: {
      "inicio": "Inicio",
      "menu": "Menú",
      "ordena": "Ordena",
      "contacto": "Contacto",
      "bienvenida": "Aquí la pizza no se comparte, se conquista. Bienvenidos A la 8VA Rebanada",
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
      "btn-login": "Se connecter",
    }
  },
  it: {
    translation: {
      "inicio": "Home",
      "menu": "Menu",
      "ordena": "Ordina",
      "contacto": "Contatto",
      "bienvenida": " Qui la pizza non si condivide, si conquista. Benvenuti all’8ª Fetta.",
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
      "bienvenida": " Aqui a pizza não se divide, conquista-se. Bem-vindos à 8ª Fatia.",
      "btn-login": "Entrar",
    }
  }
};

// Inicializamos i18next
i18next.init({
  lng: 'es', 
  debug: true,
  resources: resources
}, function(err, t) {
  actualizarTextos();
});

// Función para actualizar textos en la página
function actualizarTextos() {
  $('[data-i18n]').each(function() {
    const key = $(this).data('i18n');
    $(this).text(i18next.t(key));
  });
}

// Cambio de idioma desde el select
$('#select-idioma').on('change', function() {
  const idioma = $(this).val();
  i18next.changeLanguage(idioma, actualizarTextos);
});

 const modal = document.getElementById("modal-config");
    const btnConfig = document.getElementById("btn-config");
    const cerrarConfig = document.getElementById("cerrar-config");

    const darkToggle = document.getElementById("modo-oscuro");
    const readToggle = document.getElementById("modo-lectura");
    const selectIdioma = document.getElementById("select-idioma");

    /* ---------------- ABRIR MODAL ---------------- */
    btnConfig.addEventListener("click", () => {
        modal.style.display = "flex";
    });

    /* ---------------- CERRAR MODAL ---------------- */
    cerrarConfig.addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) modal.style.display = "none";
    });

    /* ---------------- MODO OSCURO ---------------- */
    darkToggle.addEventListener("change", () => {
        document.body.classList.toggle("dark-mode", darkToggle.checked);
        localStorage.setItem("modoOscuro", darkToggle.checked);
    });

    /* ---------------- MODO LECTURA ---------------- */
    readToggle.addEventListener("change", () => {
        document.body.classList.toggle("read-mode", readToggle.checked);
        localStorage.setItem("modoLectura", readToggle.checked);
    });

    /* ---------------- IDIOMA ---------------- */
    selectIdioma.addEventListener("change", () => {
        const idioma = selectIdioma.value;
        localStorage.setItem("idioma", idioma);

        i18next.changeLanguage(idioma);
        actualizarTraducciones();
    });

    /* ---------------- CARGAR CONFIG AL INICIAR ---------------- */
    document.addEventListener("DOMContentLoaded", () => {

        const modoOscuro = localStorage.getItem("modoOscuro") === "true";
        darkToggle.checked = modoOscuro;
        document.body.classList.toggle("dark-mode", modoOscuro);

        const modoLectura = localStorage.getItem("modoLectura") === "true";
        readToggle.checked = modoLectura;
        document.body.classList.toggle("read-mode", modoLectura);

        const idiomaGuardado = localStorage.getItem("idioma") || "es";
        selectIdioma.value = idiomaGuardado;

        if (typeof i18next !== "undefined") {
            i18next.changeLanguage(idiomaGuardado);
            setTimeout(actualizarTraducciones, 150);
        }
    });

    /* ---------------- FUNCIÓN DE REFRESCO DE TEXTOS ---------------- */
    function actualizarTraducciones() {
        document.querySelectorAll("[data-i18n]").forEach(el => {
            const key = el.getAttribute("data-i18n");
            el.innerHTML = i18next.t(key);
        });
    }