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
