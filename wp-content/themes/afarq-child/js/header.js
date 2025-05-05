document.addEventListener("DOMContentLoaded", function () {
  const logo = document.querySelector(".site-branding");
  const headerBar = document.querySelector(".main-header-bar");

  // Animación de carga del logo
  logo.classList.add("scroll-logo");

  // Función para centrar el logo y el contenedor
  function centerLogoAndBranding() {
    const headerContainer = document.querySelector(".main-header-container");
    const branding = document.querySelector(".site-branding");
    const logo = document.querySelector(".site-logo-img");

    if (branding && logo && headerContainer) {
      // Centrar el logo dentro de su contenedor (tu código original)
      const headerWidth = headerContainer.offsetWidth;
      const logoWidth = logo.offsetWidth;
      const marginLeft = (headerWidth - logoWidth) / 2;
      logo.style.marginLeft = `${marginLeft}px`;

      // Centrar el contenedor .site-branding en la página (nuevo código)
      const brandingRect = branding.getBoundingClientRect();
      const logoRect = logo.getBoundingClientRect(); // Necesitas obtener la posición del logo para el cálculo

      const logoCenterX = logoRect.left + logoRect.width / 2;
      const pageCenterX = window.innerWidth / 2;
      const offsetX = pageCenterX - logoCenterX;

      branding.style.left = offsetX + "px";
      branding.style.position = "relative";
      branding.style.marginLeft = 0; // Asegúrate de que no haya márgenes adicionales
    }
  }

  // Llama a la función al cargar la página y al redimensionar la ventana
  window.addEventListener("load", centerLogoAndBranding);
  window.addEventListener("resize", centerLogoAndBranding);

  // Manejar el scroll para cambiar la opacidad del header
  window.addEventListener("scroll", function () {
    if (window.scrollY > 0) {
    } else {
    }
  });

  // Recentrar el logo al cambiar el tamaño de la ventana
  window.addEventListener("resize", centerLogo);
});
