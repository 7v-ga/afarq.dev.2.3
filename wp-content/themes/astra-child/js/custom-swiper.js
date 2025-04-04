document.addEventListener("DOMContentLoaded", function () {
  const swiper = new Swiper(".swiper-container", {
    loop: true,
    speed: 1000, // Duración del fade
    effect: "fade",
    fadeEffect: { crossFade: true },
    autoplay: {
      delay: 4000, // Cada 4s inicia el cambio
      disableOnInteraction: false,
    },
    on: {
      slideChangeTransitionStart: function () {
        startZoom(this);
      },
    },
  });

  let zoomResetTimers = {};

  function startZoom(swiper) {
    const currentIndex = swiper.realIndex;

    // Cancela cualquier reset pendiente
    for (const key in zoomResetTimers) {
      clearTimeout(zoomResetTimers[key]);
      delete zoomResetTimers[key];
    }

    // Aplica zoom al slide activo
    const activeSlide = swiper.slides[swiper.activeIndex];
    if (!activeSlide) return;

    const imgs = activeSlide.querySelectorAll(".parallax-layer img");
    imgs.forEach((img) => {
      // Comienza el zoom
      img.style.transition = "transform 7s ease-out";
      img.style.transform = "scale(1.1)";
    });

    // Programa el reset después de 7s
    zoomResetTimers[currentIndex] = setTimeout(() => {
      // Solo reseteamos si el slide YA NO es el activo
      const stillActive = swiper.slides[swiper.activeIndex];
      const stillActiveIndex = stillActive?.getAttribute(
        "data-swiper-slide-index"
      );

      if (
        stillActiveIndex !== null &&
        stillActiveIndex.toString() !== currentIndex.toString()
      ) {
        imgs.forEach((img) => {
          img.style.transition = "none";
          img.style.transform = "scale(1)";
        });
        delete zoomResetTimers[currentIndex];
      }
    }, 7000);
  }

  swiper.on("init", () => startZoom(swiper));
  swiper.init();

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
