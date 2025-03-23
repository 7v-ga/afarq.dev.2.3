document.addEventListener("DOMContentLoaded", function () {
  const grid = document.querySelector(".projects-grid");
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          startAnimation(); // Iniciar animación si visible
        } else {
          stopAnimation(); // Detener animación si no es visible
        }
      });
    },
    { threshold: 0.1 }
  );

  if (grid) {
    observer.observe(grid); // Observamos el contenedor de la grilla
  }

  let currentPosition = 0; // Posición actual del desplazamiento
  const itemWidth = 316; // Ancho aproximado de cada elemento (incluye margen)
  const totalItems = grid.children.length; // Total de elementos
  let intervalId; // ID del intervalo para controlar la animación

  function startAnimation() {
    moveGrid(); // Hacer el primer movimiento
    intervalId = setInterval(moveGrid, 4000); // Moverse cada 4 segundos
  }

  function stopAnimation() {
    clearInterval(intervalId); // Detener la animación
    currentPosition = 0; // Reiniciar la posición
    grid.style.transform = "translateX(0)"; // Restablecer la posición inicial
  }

  function moveGrid() {
    currentPosition -= itemWidth; // Mover la posición hacia la izquierda
    grid.style.transform = `translateX(${currentPosition}px)`;

    // Si alcanza el final, restablece la posición y reinicia
    if (Math.abs(currentPosition) >= (itemWidth * totalItems) / 2) {
      // Re-establecer la posición para crear un efecto de bucle
      setTimeout(() => {
        currentPosition = 0; // Reinicia la posición
        grid.style.transition = "none"; // Desactiva la transición temporalmente
        grid.style.transform = `translateX(${currentPosition}px)`;
        setTimeout(() => {
          grid.style.transition = "transform 1s ease"; // Reiniciar la transición
        }, 50); // Pausa breve para la transición
      }, 2000); // Pausa de 2 segundos antes de reiniciar
    }
  }
});
