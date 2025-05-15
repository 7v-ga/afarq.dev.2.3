document.addEventListener("DOMContentLoaded", () => {
  const containers = document.querySelectorAll(".parallax-container");

  containers.forEach((container) => {
    const img = container.querySelector("img");
    if (!img) {
      console.warn("⚠️ No se encontró <img> en .parallax-container");
      return;
    }

    const setup = () => {
      const naturalWidth = img.naturalWidth;
      const naturalHeight = img.naturalHeight;
      const containerWidth = container.offsetWidth;

      const scrollFactor = window.innerWidth <= 768 ? 0.1 : 0.25;
      const adjustedFactor = scrollFactor * 0.5; // Reducido a la mitad

      const aspectRatio = naturalHeight / naturalWidth;
      const fullHeight = containerWidth * aspectRatio;
      const movement = fullHeight * adjustedFactor;
      const containerHeight = fullHeight - movement;

      container.style.height = `${containerHeight}px`;
      container.style.position = "relative";
      container.style.overflow = "hidden";

      img.style.position = "absolute";
      img.style.top = `0`;
      img.style.left = "0";
      img.style.width = "100%";
      img.style.height = `${fullHeight}px`;
      img.style.objectFit = "cover";
      img.style.willChange = "transform";
      img.style.transition = "transform 0.3s ease-out";

      container.dataset.scrollFactor = adjustedFactor;

      observeVisibility(container, img, adjustedFactor);
    };

    if (img.complete) {
      setup();
    } else {
      img.addEventListener("load", setup, { once: true });
    }
  });

  function observeVisibility(container, img, factor) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            window.addEventListener("scroll", () =>
              applyParallax(container, img, factor)
            );
            // Activar inmediatamente si ya visible
            applyParallax(container, img, factor);
          } else {
            // Opcional: reset transform si sale de vista
            img.style.transform = "translateY(0)";
          }
        });
      },
      { threshold: 0.05 }
    );

    observer.observe(container);
  }

  function applyParallax(container, img, factor) {
    const rect = container.getBoundingClientRect();
    const windowHeight = window.innerHeight;

    if (rect.top < windowHeight && rect.bottom > 0) {
      const translateY = -rect.top * factor;
      img.style.transform = `translateY(${translateY}px)`;
    }
  }
});
