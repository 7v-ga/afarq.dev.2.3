document.addEventListener("DOMContentLoaded", function () {
  const overlay = document.getElementById("lightbox-overlay");
  const lightboxImg = document.getElementById("lightbox-image");
  const closeBtn = document.querySelector(".lightbox-close");
  const nextBtn = document.querySelector(".lightbox-nav.next");
  const prevBtn = document.querySelector(".lightbox-nav.prev");

  let images = Array.from(document.querySelectorAll(".lightbox-img"));
  let currentIndex = 0;

  function openLightbox(index) {
    currentIndex = index;
    const fullSrc =
      images[currentIndex].dataset.full || images[currentIndex].src;

    lightboxImg.classList.remove("loaded");
    lightboxImg.style.opacity = 0;

    lightboxImg.onload = () => {
      lightboxImg.classList.add("loaded");
      console.log("✅ Imagen cargada:", fullSrc);
    };

    setTimeout(() => {
      lightboxImg.src = "";
      lightboxImg.src = fullSrc;
    }, 20);

    overlay.classList.add("active");
  }

  function closeLightbox() {
    overlay.classList.remove("active");
    lightboxImg.src = "";
  }

  function showNext() {
    currentIndex = (currentIndex + 1) % images.length;
    openLightbox(currentIndex);
  }

  function showPrev() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    openLightbox(currentIndex);
  }

  // Click en imágenes
  images.forEach((img, index) => {
    img.style.cursor = "pointer";
    img.addEventListener("click", () => openLightbox(index));
  });

  // Botones con blur para evitar pegado de foco
  closeBtn?.addEventListener("click", (e) => {
    e.currentTarget.blur();
    closeLightbox();
  });

  nextBtn?.addEventListener("click", (e) => {
    e.currentTarget.blur();
    showNext();
  });

  prevBtn?.addEventListener("click", (e) => {
    e.currentTarget.blur();
    showPrev();
  });

  // Navegación con teclado
  document.addEventListener("keydown", (e) => {
    if (!overlay.classList.contains("active")) return;
    if (e.key === "Escape") closeLightbox();
    if (e.key === "ArrowRight") showNext();
    if (e.key === "ArrowLeft") showPrev();
  });

  // Swipe en móviles
  let touchStartX = 0;
  let touchEndX = 0;

  lightboxImg.addEventListener(
    "touchstart",
    (e) => {
      touchStartX = e.changedTouches[0].screenX;
    },
    { passive: true }
  );

  lightboxImg.addEventListener(
    "touchend",
    (e) => {
      touchEndX = e.changedTouches[0].screenX;
      handleSwipe();
    },
    { passive: true }
  );

  function handleSwipe() {
    const swipeDistance = touchEndX - touchStartX;
    if (Math.abs(swipeDistance) > 50) {
      swipeDistance > 0 ? showPrev() : showNext();
    }
  }

  // Animación con scroll (fade+zoom)
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 }
  );

  images.forEach((img) => observer.observe(img));
});
