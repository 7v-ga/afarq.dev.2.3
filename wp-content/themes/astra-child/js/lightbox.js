document.addEventListener("DOMContentLoaded", function () {
  const overlay = document.getElementById("lightbox-overlay");
  const lightboxImg = document.getElementById("lightbox-image");
  const loader = document.getElementById("lightbox-loader");
  const closeBtn = document.querySelector(".lightbox-close");
  const nextBtn = document.querySelector(".lightbox-nav.next");
  const prevBtn = document.querySelector(".lightbox-nav.prev");

  let images = Array.from(document.querySelectorAll(".lightbox-img"));
  let currentIndex = 0;

  function openLightbox(index) {
    currentIndex = index;
    const fullSrc =
      images[currentIndex].dataset.full || images[currentIndex].src;

    loader.style.display = "block";
    lightboxImg.classList.remove("loaded");
    lightboxImg.style.opacity = 0;

    lightboxImg.onload = () => {
      loader.style.display = "none";
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

  images.forEach((img, index) => {
    img.style.cursor = "pointer";
    img.addEventListener("click", () => openLightbox(index));
  });

  closeBtn.addEventListener("click", closeLightbox);
  nextBtn.addEventListener("click", showNext);
  prevBtn.addEventListener("click", showPrev);

  document.addEventListener("keydown", (e) => {
    if (!overlay.classList.contains("active")) return;
    if (e.key === "Escape") closeLightbox();
    if (e.key === "ArrowRight") showNext();
    if (e.key === "ArrowLeft") showPrev();
  });

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
      if (swipeDistance > 0) {
        showPrev();
      } else {
        showNext();
      }
    }
  }

  // ✅ Animación on scroll para las imágenes
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.1,
    }
  );

  images.forEach((img) => {
    observer.observe(img);
  });
});
