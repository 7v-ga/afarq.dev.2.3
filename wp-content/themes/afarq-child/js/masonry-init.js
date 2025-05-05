document.addEventListener("DOMContentLoaded", function () {
  const grid = document.querySelector(".masonry-gallery");

  if (grid) {
    new Masonry(grid, {
      itemSelector: ".masonry-item",
      columnWidth: ".masonry-sizer",
      percentPosition: true,
      gutter: 20,
    });
  }
});
