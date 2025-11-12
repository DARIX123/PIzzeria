const resources = {
  es: {
    translation: {
      "inicio": "Inicio",
      "menu": "Menú",
      "ordena": "Ordena",
      "contacto": "Contacto",
      "bienvenida": ""
    }
  },
  en: {
    translation: {
      "inicio": "Home",
      "menu": "Menu",
      "ordena": "Order",
      "contacto": "Contact",
      "bienvenida": "Here pizza is not shared..."
    }
  },
  fr: {
    translation: {
      "inicio": "Accueil",
      "menu": "Menu",
      "ordena": "Commander",
      "contacto": "Contact",
      "bienvenida": "Ici, la pizza n'est pas partagée..."
    }
  },
  it: {
    translation: {
      "inicio": "Home",
      "menu": "Menu",
      "ordena": "Ordina",
      "contacto": "Contatto",
      "bienvenida": "Qui la pizza non si condivide..."
    }
  },
  zh: {
    translation: {
      "inicio": "首页",
      "menu": "菜单",
      "ordena": "订购",
      "contacto": "联系",
      "bienvenida": "这里的披萨不可分享..."
    }
  },
  pt: {
    translation: {
      "inicio": "Início",
      "menu": "Cardápio",
      "ordena": "Pedir",
      "contacto": "Contato",
      "bienvenida": "Aqui a pizza não é compartilhada..."
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
