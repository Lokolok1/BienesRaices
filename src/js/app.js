document.addEventListener("DOMContentLoaded", function() {
    eventListeners();
    darkMode();
});

function darkMode() {
    const prefiereDarkMode = window.matchMedia("(prefers-color-scheme: dark)");

    if (prefiereDarkMode.matches) {
        document.body.classList.add("dark-mode");
    } else {
        document.body.classList.remove("dark-mode");
    }

    prefiereDarkMode.addEventListener("change", function() {
        if (prefiereDarkMode.matches) {
            document.body.classList.add("dark-mode");
        } else {
            document.body.classList.remove("dark-mode");
        }
    });

    const botonDarkMode = document.querySelector(".dark-mode-boton");
    botonDarkMode.addEventListener("click", function() {
        document.body.classList.toggle("dark-mode")
    });

    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("modo-oscuro",true);
    } else {
        localStorage.setItem("modo-oscuro",false);
    }

    if (localStorage.getItem('modo-oscuro') === 'true') {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
}

function eventListeners() {
    const mobileMenu = document.querySelector(".mobile-menu");
    mobileMenu.addEventListener("click", navegacionResponsive);

    // Muestra campos condicionales
    const metodoContacto = document.querySelectorAll('input[name="contacto"]');
    metodoContacto.forEach(input => input.addEventListener("click", mostrarMetodosContacto));
}

function navegacionResponsive() {
    const navegacion = document.querySelector(".navegacion");
    navegacion.classList.toggle("mostrar");
}

function mostrarMetodosContacto(e) {
    const contactoDiv = document.querySelector("#contacto");
    if (e.target.value === "telefono") {
        contactoDiv.innerHTML = `
            <label for="telefono">Número Teléfono:</label>
            <input type="tel" placeholder="Tu Teléfono" id="telefono" name="telefono">
        
            <p>Elija la fecha y hora para ser contactado</p>

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha">

            <label for="hora">Hora:</label>
            <input type="time" id="hora" min="09:00" max="18:00" name="hora">
        `;
    } else {
        contactoDiv.innerHTML = `
            <label for="email">E-mail:</label>
            <input type="email" placeholder="Tu Email" id="email" name="email" required>
        `;
    }
}