// js/product-gallery.js
document.addEventListener('DOMContentLoaded', function () {
  const mainImage = document.getElementById('mainImage');
  let thumbnails = Array.from(document.querySelectorAll('.thumbnail-img'));
  let currentIndex = 0;

  function updateThumbnailList() {
    thumbnails = Array.from(document.querySelectorAll('.thumbnail-img'));
  }

  function findInitialIndex() {
    const mainSrc = mainImage?.getAttribute('src') || '';
    const idx = thumbnails.findIndex(t => t.getAttribute('src') === mainSrc);
    return idx >= 0 ? idx : 0;
  }

  function setActiveThumbnail(index) {
    updateThumbnailList();
    currentIndex = Math.max(0, Math.min(index, thumbnails.length - 1));
    thumbnails.forEach((t, i) => {
      t.classList.remove('border-yellow-400');
      t.classList.add('border-gray-200');
      if (i === currentIndex) {
        t.classList.add('border-yellow-400');
        t.classList.remove('border-gray-200');
      }
    });
  }

  function changeMainImageByIndex(index) {
    updateThumbnailList();
    if (!thumbnails.length) return;
    index = ((index % thumbnails.length) + thumbnails.length) % thumbnails.length; // wrap-around
    const src = thumbnails[index].getAttribute('src');
    if (src && mainImage) {
      // fade out -> change src -> fade in
      mainImage.style.opacity = 0;
      setTimeout(() => {
        mainImage.setAttribute('src', src);
        mainImage.style.opacity = 1;
      }, 120);
      setActiveThumbnail(index);
    }
  }

  function bindThumbnailClicks() {
    updateThumbnailList();
    thumbnails.forEach((thumb, i) => {
      // remove previous handler safety
      thumb.replaceWith(thumb.cloneNode(true));
    });
    updateThumbnailList();
    thumbnails.forEach((thumb, i) => {
      thumb.addEventListener('click', (e) => {
        e.preventDefault();
        changeMainImageByIndex(i);
      });
    });
  }

  // Prev / Next
  document.getElementById('prevBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
    changeMainImageByIndex(currentIndex - 1);
  });
  document.getElementById('nextBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
    changeMainImageByIndex(currentIndex + 1);
  });

  // Keyboard support
  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') changeMainImageByIndex(currentIndex - 1);
    if (e.key === 'ArrowRight') changeMainImageByIndex(currentIndex + 1);
  });

  // init
  bindThumbnailClicks();
  currentIndex = findInitialIndex();
  setActiveThumbnail(currentIndex);
});
