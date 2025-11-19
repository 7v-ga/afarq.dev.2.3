document.addEventListener("DOMContentLoaded", function () {
  const container = document.querySelector(".swiper-wrapper");
  const slides = Array.from(container.children);

  // Mezclar slides aleatoriamente
  slides.sort(() => Math.random() - 0.5);
  slides.forEach((slide) => container.appendChild(slide));

  const swiper = new Swiper(".swiper-container", {
    loop: true,
    speed: 1800,
    effect: "fade",
    fadeEffect: { crossFade: true },
    autoplay: {
      delay: 4900,
      disableOnInteraction: false,
    },
    on: {
      slideChangeTransitionStart: function () {
        aplicarZoom(this);
      },
    },
  });

  let zoomResetTimers = {};

  function aplicarZoom(swiper) {
    const currentIndex = swiper.realIndex;

    // Limpiar resets anteriores
    for (const key in zoomResetTimers) {
      clearTimeout(zoomResetTimers[key]);
      delete zoomResetTimers[key];
    }

    // Limpiar clase de todos los slides
    swiper.slides.forEach((slide) => {
      slide.classList.remove("zoom-activo");
    });

    const activeSlide = swiper.slides[swiper.activeIndex];
    if (!activeSlide) return;

    activeSlide.classList.add("zoom-activo");

    const imgs = activeSlide.querySelectorAll(".parallax-layer img");

    imgs.forEach((img) => {
      if (img.complete) {
        animarZoom(img);
      } else {
        img.addEventListener("load", () => animarZoom(img), { once: true });
      }
    });

    // Reset cuando ya no esté activo
    zoomResetTimers[currentIndex] = setTimeout(() => {
      const stillActive = swiper.slides[swiper.activeIndex];
      const stillActiveIndex = stillActive?.getAttribute(
        "data-swiper-slide-index"
      );

      if (
        stillActiveIndex !== null &&
        stillActiveIndex.toString() !== currentIndex.toString()
      ) {
        imgs.forEach((img) => {
          img.style.transition = "transform 1.8s ease";
          img.style.transform = "scale(1)";
        });
        delete zoomResetTimers[currentIndex];
      }
    }, 7000);
  }

  function animarZoom(img) {
    img.style.transition = "none";
    img.style.transform = "scale(1)";
    setTimeout(() => {
      img.style.transition = "transform 8.6s ease-in-out";
      img.style.transform = "scale(1.22)";
    }, 50);
  }

  // 🚀 Iniciar Swiper y aplicar zoom al primer slide justo después
  swiper.init();

  // 🔧 Ejecutar zoom inicial manualmente tras init
  setTimeout(() => {
    aplicarZoom(swiper);
  }, 100);
  
  // Indicador hacia abajo
  var indicator = document.querySelector('.slider-down-indicator');
  if (!indicator) return;
  function updateIndicatorVisibility() {
      var rect = indicator.getBoundingClientRect();
      var distanceFromBottom = window.innerHeight - rect.top;
      // Si está más de 150px por encima del borde inferior, ocultar
      if (distanceFromBottom > 220) {
          indicator.classList.add('is-hidden');
      } else {
          indicator.classList.remove('is-hidden');
      }
  }
  // Ejecutar al cargar, al hacer scroll y al cambiar tamaño de la ventana
  window.addEventListener('scroll', updateIndicatorVisibility);
  window.addEventListener('resize', updateIndicatorVisibility);
  updateIndicatorVisibility();
});
