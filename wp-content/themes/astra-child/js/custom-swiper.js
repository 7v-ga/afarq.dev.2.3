document.addEventListener("DOMContentLoaded", function () {
  const swiper = new Swiper(".swiper-container", {
    effect: "fade",
    zoom: true,
    autoplay: {
      delay: 7000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    on: {
      init: function () {
        const navItems = document.querySelectorAll(".nav-item");
        if (navItems.length > 0) {
          navItems[0].classList.add("active");
        }

        adjustSliderMargins();
        setNavBarHeight(); // Llamar a la función para ajustar la altura de las barras
      },
      slideChange: function () {
        const navItems = document.querySelectorAll(".nav-item");
        if (navItems.length > 0) {
          navItems.forEach((item) => {
            item.classList.remove("active");
          });
          navItems[this.activeIndex].classList.add("active");
        }
      },
    },
  });

  window.addEventListener("resize", function () {
    adjustSliderMargins();
    setNavBarHeight(); // Ajustar la altura al redimensionar la ventana
  });

  const navItems = document.querySelectorAll(".nav-item");
  navItems.forEach((item, index) => {
    item.addEventListener("click", () => {
      swiper.slideTo(index);
    });
  });

  function adjustSliderMargins() {
    const slider = document.querySelector(".full-slider");
    const content = document.querySelector(".content-area");
    const contentPosition = content.getBoundingClientRect();

    slider.style.marginLeft = `-${contentPosition.left}px`;
    slider.style.marginTop = `-${contentPosition.top}px`;

    setTimeout(() => {
      slider.classList.add("visible");
    }, 50);
  }

  function setNavBarHeight() {
    const nav = document.querySelector(".vertical-navigation");
    const bars = document.querySelectorAll(".nav-item");

    // Verificar si hay barras y nav
    if (nav && bars.length > 0) {
      // Calcular la altura total disponible para la barra de navegación
      const navHeight = nav.clientHeight;
      const barHeight = navHeight / bars.length; // Dividir la altura total entre el número de barras

      // Aplicar la altura calculada a cada barra
      bars.forEach((bar) => {
        bar.style.height = `${barHeight}px`; // Establecer la altura de cada barra
      });
    }
  }

  adjustSliderMargins(); // Ajustar inicialmente los márgenes
  setNavBarHeight(); // Establecer la altura de las barras en carga inicial
});
