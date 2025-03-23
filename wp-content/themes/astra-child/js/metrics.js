document.addEventListener("DOMContentLoaded", function () {
  // Función para iniciar los contadores
  function startCounters() {
    const countElements = document.querySelectorAll(".metric-count"); // Selecciona todos los contadores

    countElements.forEach((element) => {
      const target = parseInt(element.innerText); // Obtener el valor objetivo de la meta box
      let count = 0; // Contador inicial
      const duration = 2400; // Duración de la animación en milisegundos (2.4 segundos)

      const stepTime = Math.ceil(duration / target); // Tiempo entre cada incremento

      // Validar que el número objetivo sea positivo para evitar problemas
      if (target > 0) {
        const counterInterval = setInterval(() => {
          if (target < 1000) {
            count++;
          } else {
            count = count + 10;
          }
          element.innerText = count
            .toString()
            .replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Formatear con punto como separador de miles

          if (count >= target) {
            element.innerText =
              "+" + count.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Formatear con punto como separador de miles
            clearInterval(counterInterval); // Detener el contador cuando alcanza el objetivo
          }
        }, stepTime);
      } else {
        element.innerText = target; // Si no hay cambios, establecer el número directamente
      }
    });
  }

  // Emitir la animación de contadores cuando el usuario hace scroll a la sección
  const metricsSection = document.querySelector(".metric-container");
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        startCounters(); // Iniciar los contadores cuando la sección es visible
        observer.unobserve(entry.target); // Dejar de observar una vez que los contadores han iniciado
      }
    });
  });

  if (metricsSection) {
    observer.observe(metricsSection); // Observar la sección de métricas
  }
});
