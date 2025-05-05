document.addEventListener("DOMContentLoaded", function () {
  const grid = document.querySelector(".projects-grid");
  if (!grid) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          clearTimeout(stopTimeout);
          startAnimation();
        } else {
          stopTimeout = setTimeout(stopAnimation, 3000);
        }
      });
    },
    { threshold: 0.1 }
  );

  observer.observe(grid);

  let currentPosition = 0;
  let intervalId;
  let stopTimeout;

  function getItemStep() {
    const firstItem = grid.querySelector(".project-item");
    const style = window.getComputedStyle(grid);
    const gap = parseFloat(style.gap || "0");
    const itemWidth = firstItem ? firstItem.getBoundingClientRect().width : 0;
    return itemWidth + gap;
  }

  function startAnimation() {
    if (intervalId) return;
    moveGrid();
    intervalId = setInterval(moveGrid, 4000);
  }

  function stopAnimation() {
    clearInterval(intervalId);
    intervalId = null;
    currentPosition = 0;
    grid.style.transition = "transform 1s ease";
    grid.style.transform = "translateX(0)";
  }

  function moveGrid() {
    const step = getItemStep();
    const totalItems = grid.querySelectorAll(".project-item").length;
    const visibleItems = Math.round(grid.offsetWidth / step);
    const maxOffset = step * (totalItems / 2); // mitad porque se duplican

    currentPosition -= step;
    grid.style.transition = "transform 1s ease-in-out";
    grid.style.transform = `translateX(${currentPosition}px)`;

    if (Math.abs(currentPosition) >= maxOffset) {
      setTimeout(() => {
        grid.style.transition = "none";
        currentPosition = 0;
        grid.style.transform = `translateX(0)`;
        setTimeout(() => {
          grid.style.transition = "transform 1s ease-in-out";
        }, 50);
      }, 2000);
    }
  }
});
