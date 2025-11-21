<!-- Botón Configuración -->
<button id="btn-config" class="btn-config">
    ⚙️
</button>

<!-- Modal Configuración -->
<div id="modal-config" class="modal-config">
    <div class="modal-contenido">

        <span id="cerrar-config" class="cerrar-config">&times;</span>
        <h2 data-i18n="config_title">Configuración</h2>

        <div class="config-item">
            <label for="modo-oscuro" data-i18n="dark_mode">Modo Oscuro</label>
            <input type="checkbox" id="modo-oscuro">
        </div>

        <div class="config-item">
            <label for="modo-lectura" data-i18n="reading_mode">Modo Lectura</label>
            <input type="checkbox" id="modo-lectura">
        </div>

        <div class="config-item">
            <label for="select-idioma" data-i18n="language">Idioma</label>
            <select id="select-idioma">
                <option value="es">Español</option>
                <option value="en">Inglés</option>
                <option value="fr">Francés</option>
                <option value="it">Italiano</option>
                <option value="pt">Portugués</option>
                <option value="zh">Chino</option>
            </select>
        </div>

    </div>
</div>
