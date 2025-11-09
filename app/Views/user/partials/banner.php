<?php
// $banners đến từ home.php
$isEmpty = empty($banners);
// URL base cho ảnh banner
?>

<section class="bg-[#F1DEB4]">
  <div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-8">
      
      <!-- LEFT TEXT -->
      <div class="text-center md:text-center">
        <h1 class="text-16xl md:text-9xl font-bold text-gray-900">
          SHooad
        </h1>
        <p class="mt-4 text-5xl text-gray-700">
          Quality, Fast, Reliable
        </p>
      </div>
      
      <!-- RIGHT IMAGE (SLIDER) -->
      <div class="flex justify-center">
        <div class="relative w-full h-96 overflow-hidden rounded-xl ">
          <?php if ($isEmpty): ?>
            <img src="/placeholder.svg?height=400&width=400"
                alt="Placeholder"
                class="w-full h-full object-cover drop-shadow-2xl">
          <?php else: ?>
            <?php foreach ($banners as $i => $img): ?>
              <?php
                $src = "/SHooad/public/assets/banner/" . rawurlencode($img['filename']);
                $alt = $img['title'] ?? 'Banner';
              ?>
                <img
                  src="<?= htmlspecialchars($src, ENT_QUOTES) ?>"
                  alt="<?= htmlspecialchars($alt, ENT_QUOTES) ?>"
                  class="banner-slide w-full h-full object-cover drop-shadow-2xl
                        absolute inset-0 mx-auto transition-opacity duration-500
                        <?= $i === 0 ? 'opacity-100 relative' : 'opacity-0 pointer-events-none' ?>"
                  data-index="<?= $i ?>"
                />
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</section>


<?php if (!$isEmpty): ?>
<script>
  (function () {
    const slides = Array.from(document.querySelectorAll('.banner-slide'));
    if (!slides.length) return;

    let i = 0;
    const DURATION = 2000;

    const show = (idx) => {
      slides.forEach((img, k) => {
        if (k === idx) {
          img.classList.remove('opacity-0', 'pointer-events-none', 'absolute');
          img.classList.add('opacity-100', 'relative');
        } else {
          img.classList.remove('opacity-100', 'relative');
          img.classList.add('opacity-0', 'pointer-events-none', 'absolute');
        }
      });
    };

    setInterval(() => {
      i = (i + 1) % slides.length;
      show(i);
    }, DURATION);
  })();
</script>
<?php endif; ?>
