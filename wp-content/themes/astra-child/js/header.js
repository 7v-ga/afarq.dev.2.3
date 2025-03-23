document.addEventListener("DOMContentLoaded", function () {
  const logo = document.querySelector(".site-branding");
  const headerBar = document.querySelector(".main-header-bar");

  // Animación de carga del logo
  logo.classList.add("scroll-logo");

  // Función para centrar el logo
  function centerLogo() {
    const headerContainer = document.querySelector(".main-header-container");
    if (headerContainer) {
      const headerWidth = headerContainer.offsetWidth; // Obtener el ancho del contenedor del header
      const logoWidth = logo.offsetWidth; // Obtener el ancho del logo

      // Calcular el margen izquierdo para centrar
      const marginLeft = (headerWidth - logoWidth) / 2;
      logo.style.marginLeft = `${marginLeft}px`; // Establecer el margen izquierdo del logo
    }
  }

  // Centrar el logo al cargar la página
  centerLogo();

  // Manejar el scroll para cambiar la opacidad del header
  window.addEventListener("scroll", function () {
    if (window.scrollY > 0) {
      headerBar.classList.add("header-fade");
    } else {
      headerBar.classList.remove("header-fade");
    }
  });

  // Recentrar el logo al cambiar el tamaño de la ventana
  window.addEventListener("resize", centerLogo);
});
