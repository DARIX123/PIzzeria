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
