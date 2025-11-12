document.addEventListener("DOMContentLoaded", () => {
  const gap = 12;
  const filas = document.querySelectorAll(".fila-proyectos");

  function recalcularLayout() {
    filas.forEach((fila) => {
      const items = Array.from(fila.querySelectorAll(".proyecto-item"));
      let ratios = [];

      // 1. Calcular ratios y asignar --aspect-ratio como CSS variable
      items.forEach((item) => {
        const img = item.querySelector("img");
        let ratio = parseFloat(item.dataset.ratio);

        if ((!ratio || ratio <= 0) && img?.naturalWidth && img?.naturalHeight) {
          ratio = img.naturalWidth / img.naturalHeight;
          item.dataset.ratio = ratio.toFixed(4);
          item.dataset.log = `${img.naturalWidth}x${
            img.naturalHeight
          } ratio=${ratio.toFixed(4)}`;
        }

        if (!ratio || ratio <= 0) ratio = 1.5;

        // Aplicar variable CSS para aspect-ratio
        const thumb = item.querySelector(".proyecto-thumb");
        if (thumb) {
          thumb.style.setProperty("--aspect-ratio", `${ratio.toFixed(4)}`);
        }

        ratios.push(ratio);
      });

      // 2. Calcular ancho relativo de cada item en base al total
      const totalRatio = ratios.reduce((sum, r) => sum + r, 0);
      const filaWidth = fila.clientWidth - (items.length - 1) * gap;
      const unidad = filaWidth / totalRatio;

      items.forEach((item, index) => {
        const width = Math.round(unidad * ratios[index]);
        item.style.flex = `0 0 ${width}px`;
      });
    });
  }

  recalcularLayout();
  window.addEventListener("resize", recalcularLayout);

  // Fade-up
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) entry.target.classList.add("visible");
      });
    },
    { threshold: 0.1 }
  );

  filas.forEach((fila) => observer.observe(fila));
});
