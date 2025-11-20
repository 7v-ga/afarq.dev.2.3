document.addEventListener("DOMContentLoaded", function () {
  const overlay = document.getElementById("lightbox-overlay");
  const lightboxImg = document.getElementById("lightbox-image");
  const closeBtn = document.querySelector(".lightbox-close");
  const nextBtn = document.querySelector(".lightbox-nav.next");
  const prevBtn = document.querySelector(".lightbox-nav.prev");
  const lightboxTextEl = document.getElementById("lightbox-text");

  let images = Array.from(document.querySelectorAll(".lightbox-img"));
  let currentIndex = 0;

  // -----------------------------
  // Utilidades
  // -----------------------------

  // Define el texto del lightbox solo si la imagen viene de .oficinas-gallery
  function setLightboxContext(originImg) {
    if (!lightboxTextEl) return;
  
    // Texto crudo desde data-lightbox-text de la imagen
    const raw = originImg && originImg.dataset
      ? originImg.dataset.lightboxText || ""
      : "";
  
    // Si no hay texto: limpiar y ocultar
    if (raw.trim() === "") {
      lightboxTextEl.innerHTML = "";
      lightboxTextEl.classList.remove("visible");
      return;
    }
  
    // Normalizar saltos de línea (LF / CRLF / CR)
    const normalized = raw.replace(/\r\n/g, "\n").replace(/\r/g, "\n");
  
    // Convertir saltos de línea en <br>
    const html = normalized
      .split("\n")
      .map(line => line) // aquí podrías hacer .trim() si quisieras recortar espacios
      .join("<br>");
  
    // Insertar HTML + activar visibilidad
    lightboxTextEl.innerHTML = html;
    lightboxTextEl.classList.add("visible");
  }
  
  // Configura la imagen grande.
  // Si se pasa onReady, se invoca cuando la imagen termina de cargar.
  // Si no se pasa onReady, se hace visible inmediatamente al cargar.
  function setLightboxImage(fullSrc, onReady) {
    lightboxImg.classList.remove("loaded");

    lightboxImg.onload = () => {
      if (typeof onReady === "function") {
        onReady();
      } else {
        lightboxImg.classList.add("loaded");
        showLightboxText();
      }
    };

    lightboxImg.src = fullSrc;
  }

  function openOverlay() {
    overlay.classList.add("active");
  }

  // -----------------------------
  // Apertura animada desde miniatura (todas las galerías)
  // -----------------------------
  function openLightboxAnimated(index, originImg) {
    currentIndex = index;
    const img = images[currentIndex];
    const fullSrc = img.dataset.full || img.src;

    // Origen de la animación: la miniatura clickeada
    const origin = originImg || img;
    const rect = origin.getBoundingClientRect();

    // Clon que crece desde la miniatura
    const clone = origin.cloneNode(true);
    clone.style.position = "fixed";
    clone.style.left = rect.left + "px";
    clone.style.top = rect.top + "px";
    clone.style.width = rect.width + "px";
    clone.style.height = rect.height + "px";
    clone.style.margin = 0;
    clone.style.zIndex = 10001;
    clone.style.objectFit = "cover";
    clone.style.transition = "all 0.45s ease";
    clone.style.transformOrigin = "center center";

    document.body.appendChild(clone);

    // Activamos overlay global
    openOverlay();

    // Contexto (texto solo para oficinas si corresponde)
    setLightboxContext(origin);

    // Tamaño final centrado
    const viewportW = window.innerWidth;
    const viewportH = window.innerHeight;

    const naturalW = origin.naturalWidth || rect.width;
    const naturalH = origin.naturalHeight || rect.height;
    const imgRatio = naturalW / naturalH;

    let targetW = viewportW * 0.9;
    let targetH = targetW / imgRatio;

    if (targetH > viewportH * 0.9) {
      targetH = viewportH * 0.9;
      targetW = targetH * imgRatio;
    }

    const targetLeft = (viewportW - targetW) / 2;
    const targetTop = (viewportH - targetH) / 2;

    // Reflow y animación del clon
    clone.getBoundingClientRect();
    requestAnimationFrame(() => {
      clone.style.left = targetLeft + "px";
      clone.style.top = targetTop + "px";
      clone.style.width = targetW + "px";
      clone.style.height = targetH + "px";
    });

    // Coordinamos SIEMPRE imagen + clon (proyecto y oficinas)
    let imageReady = false;
    let cloneReady = false;

    function maybeShow() {
      // Solo cuando la imagen está cargada y el clon terminó de crecer
      if (imageReady && cloneReady) {
        clone.remove();                // quitamos la miniatura agrandada
        lightboxImg.classList.add("loaded"); // mostramos la imagen grande (sin fade)
        showLightboxText();
      }
    }

    // Cargamos la imagen pero NO la mostramos hasta que ambas cosas estén listas
    setLightboxImage(fullSrc, () => {
      imageReady = true;
      maybeShow();
    });

    clone.addEventListener(
      "transitionend",
      () => {
        cloneReady = true;
        maybeShow();
      },
      { once: true }
    );
  }

  // -----------------------------
  // Apertura básica (para navegar con flechas)
  // -----------------------------
  function openLightboxBasic(index) {
    currentIndex = index;
    const img = images[currentIndex];
    const fullSrc = img.dataset.full || img.src;

    if (!overlay.classList.contains("active")) {
      openOverlay();
    }

    // Contexto basado en la imagen actual (si viene de oficinas, define texto)
    setLightboxContext(img);

    // En navegación no usamos clon: la imagen aparece al cargar
    setLightboxImage(fullSrc);
  }

  // -----------------------------
  // Wrapper general de apertura (clic en miniatura)
  // -----------------------------
  function openLightbox(index, originImg) {
    openLightboxAnimated(index, originImg || images[index]);
  }

  // -----------------------------
  // Cerrar y navegación
  // -----------------------------
  function closeLightbox() {
    overlay.classList.remove("active");
    lightboxImg.src = "";
    lightboxImg.classList.remove("loaded");

    if (lightboxTextEl) {
      lightboxTextEl.classList.remove("visible");
      lightboxTextEl.textContent = "";
    }
  }

  function showNext() {
    if (!images.length) return;
    currentIndex = (currentIndex + 1) % images.length;
    openLightboxBasic(currentIndex);
  }

  function showPrev() {
    if (!images.length) return;
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    openLightboxBasic(currentIndex);
  }

  // -----------------------------
  // Eventos
  // -----------------------------

  // Click en miniaturas
  images.forEach((img, index) => {
    img.style.cursor = "pointer";
    img.addEventListener("click", () => openLightbox(index, img));
  });

  // Botón cerrar
  closeBtn?.addEventListener("click", (e) => {
    e.currentTarget.blur();
    closeLightbox();
  });

  // Flechas
  nextBtn?.addEventListener("click", (e) => {
    e.currentTarget.blur();
    showNext();
  });

  prevBtn?.addEventListener("click", (e) => {
    e.currentTarget.blur();
    showPrev();
  });

  // Teclado
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
});
