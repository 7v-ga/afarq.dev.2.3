document.addEventListener('DOMContentLoaded', function () {
  const nav = document.querySelector('.nav');
  const menuToggle = document.getElementById('mobile-menu');
  const offset = 120; // mismo valor que en CSS

  if (!nav || !menuToggle) return;

  // --- Helpers de apertura/cierre ---

  function openMenu() {
    nav.classList.add('active');
    menuToggle.classList.add('open');

    nav.style.display = 'flex';
    // pequeño delay para que la transición de opacidad se aplique
    setTimeout(() => {
      nav.style.opacity = '1';
    }, 10);
  }

  function closeMenu() {
    if (!nav.classList.contains('active')) return;

    nav.classList.remove('active');
    menuToggle.classList.remove('open');

    nav.style.opacity = '0';
    setTimeout(() => {
      nav.style.display = 'none';
    }, 500); // mismo tiempo que la transición en CSS
  }

  function toggleMenu() {
    if (nav.classList.contains('active')) {
      closeMenu();
    } else {
      openMenu();
    }
  }

  // --- Botón hamburguesa ---

  menuToggle.addEventListener('click', function () {
    toggleMenu();
  });

  // --- Scroll con offset para anclas ---

  function scrollConOffset(hash) {
    const target = document.querySelector(hash);
    if (!target) return;

    const top =
      target.getBoundingClientRect().top +
      window.pageYOffset -
      offset;

    window.scrollTo({
      top,
      behavior: 'instant' in window ? 'instant' : 'auto'
    });
  }

  // Al cargar la página con hash ya presente
  if (window.location.hash) {
    scrollConOffset(window.location.hash);
  }

  // --- Cerrar menú al hacer clic en un link del overlay ---
  // y, si es ancla de esta misma página, hacer scroll con offset.

  nav.addEventListener('click', function (e) {
    const link = e.target.closest('a');
    if (!link) return;

    const href = link.getAttribute('href') || '';

    // Siempre cerramos el menú al clicar cualquier link del overlay
    // (sea a otra página o a la misma).
    // Cerramos primero para que no se quede el overlay.
    const isSamePageAnchor =
      href.startsWith('#') ||
      href.startsWith(window.location.pathname + '#') ||
      href.startsWith(window.location.origin + window.location.pathname + '#');

    if (isSamePageAnchor) {
      e.preventDefault();

      // Normalizar el hash (solo lo que va después de #)
      const hashIndex = href.indexOf('#');
      const hash = href.slice(hashIndex);

      closeMenu();
      scrollConOffset(hash);
      // Actualizamos la barra de direcciones
      history.replaceState(null, '', hash);
    } else {
      // Link a otra página o dominio: cerramos menú y dejamos navegar normal
      closeMenu();
      // No hacemos preventDefault para que el navegador siga el enlace
    }
  });
});
