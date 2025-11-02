document.addEventListener("DOMContentLoaded", function() {
    const totalSlides = 5; 
    let currentSlide = 1; 
    const intervalTime = 3000;
    let interval; 

    // Función para mostrar/ocultar los controles (Flechas)
    function updateControls(slideNumber) {
        // Oculta todos los controles (asegura el reseteo)
        document.querySelectorAll('.slide-control').forEach(ctrl => {
            ctrl.style.display = 'none';
        });
        // Muestra solo los controles para la diapositiva actual
        document.querySelectorAll(`.control-${slideNumber}`).forEach(ctrl => {
            ctrl.style.display = 'block';
        });
    }

    // Función que avanza el carrusel
    function nextSlide() {
        currentSlide++;
        if (currentSlide > totalSlides) {
            currentSlide = 1;
        }

        const nextRadio = document.getElementById(`slide-${currentSlide}`);
        if (nextRadio) {
            nextRadio.checked = true;
            updateControls(currentSlide);
        }
    }
    
    // Función para iniciar o reiniciar el auto-avance
    function startAutoPlay() {
        clearInterval(interval); 
        interval = setInterval(nextSlide, intervalTime);
    }

    // --- Lógica Principal de Inicialización ---
    
    // 1. Inicializar el estado de los botones (importante para el primer slide)
    updateControls(currentSlide); 
    
    // 2. Empezar el ciclo de auto-avance (debe girar desde el inicio)
    startAutoPlay();

    // 3. Manejo de Interacción: Detener/Reanudar con el ratón
    const slideElement = document.querySelector('.slide');
    if (slideElement) {
        slideElement.addEventListener('mouseenter', () => clearInterval(interval));
        slideElement.addEventListener('mouseleave', () => startAutoPlay());
    }
    
    // 4. Función para manejar el clic en cualquier control (Flechas y Círculos)
    function handleControlClick(e) {
        // Buscamos el radio button que quedó marcado después del clic
        const checkedRadio = document.querySelector('.slide-open:checked');
        
        if (checkedRadio) {
            // Actualizar el estado actual del carrusel
            currentSlide = parseInt(checkedRadio.id.replace('slide-', ''));
            
            // Actualizar la visibilidad de los botones de las flechas
            updateControls(currentSlide);
            
            // Reiniciar el ciclo de auto-avance
            startAutoPlay();
        }
    }

    // 5. Agregar listeners de click a todos los controles
    document.querySelectorAll('.slide-control, .slide-circulo').forEach(control => {
        // Usamos addEventListener directamente sin el setTimeout, ya que el evento de click es síncrono
        // y el <label for="..."> ya habrá marcado el input antes de que esta función se ejecute completamente.
        control.addEventListener('click', handleControlClick);
    });
});