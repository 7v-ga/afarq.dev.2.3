document.getElementById("mobile-menu").addEventListener("click", function () {
  const nav = document.querySelector(".nav");
  const menuToggle = document.getElementById("mobile-menu");

  nav.classList.toggle("active"); // Alternar la clase 'active' para mostrar/ocultar el menú

  // Cambiar el ícono de hamburguesa a "X"
  if (nav.classList.contains("active")) {
    menuToggle.classList.add("open"); // Clase para la "X"
    // Forzamos la opacidad a 0 para iniciar la animación
    nav.style.display = "flex"; // Hacer visible el menú
    setTimeout(() => {
      nav.style.opacity = "0.9"; // Cambiar opacidad luego de mostrar
    }, 10);
  } else {
    menuToggle.classList.remove("open"); // Volver al ícono hamburguesa
    nav.style.opacity = "0"; // Iniciar fade out
    setTimeout(() => {
      nav.style.display = "none"; // Ocultar el menú después de la animación
    }, 500); // Tiempo debe coincidir con la duración de la transición
  }
});


document.addEventListener('DOMContentLoaded', function () {
  const offset = 120; // mismo valor que en CSS

  function scrollConOffset(hash) {
    const target = document.querySelector(hash);
    if (!target) return;
    const top = target.getBoundingClientRect().top + window.pageYOffset - offset;
    window.scrollTo({ top, behavior: 'instant' in window ? 'instant' : 'auto' });
  }

  if (window.location.hash) {
    scrollConOffset(window.location.hash);
  }
});
