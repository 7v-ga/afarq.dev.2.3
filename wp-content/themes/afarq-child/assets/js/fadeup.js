document.addEventListener("DOMContentLoaded", function () {
  const multiElems = document.querySelectorAll(".fade-up");
  const onceElems = document.querySelectorAll(".fade-up-once");

  if (!multiElems.length && !onceElems.length) return;

  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        const el = entry.target;
        const isOnce = el.classList.contains("fade-up-once");

        if (entry.isIntersecting) {
          el.classList.add("visible");

          // Si es versión "una sola vez", dejamos de observar este elemento
          if (isOnce) {
            obs.unobserve(el);
          }
        } else {
          // Solo quitamos .visible en la versión “normal”
          if (!isOnce) {
            el.classList.remove("visible");
          }
        }
      });
    },
    { threshold: 0.15 }
  );

  multiElems.forEach((el) => observer.observe(el));
  onceElems.forEach((el) => observer.observe(el));
});
