<?php

// expects $category from parent
$name = isset($category['name']) ? strtolower($category['name']) : '';
$icon = 'fa-solid fa-box'; // default

if (stripos($name, 'women') !== false) {
    $icon = 'fa-solid fa-person-dress';
} elseif (stripos($name, 'men') !== false) {
    $icon = 'fa-solid fa-vest';
} elseif (stripos($name, 'kid') !== false) {
    $icon = 'fa-solid fa-children';
} elseif (stripos($name, 'baby') !== false) {
    $icon = 'fa-solid fa-baby';
} elseif (stripos($name, 'mobile') !== false || stripos($name, 'phone') !== false) {
    $icon = 'fa-solid fa-mobile-screen-button';
} elseif (stripos($name, 'computer') !== false || stripos($name, 'laptop') !== false || stripos($name, 'pc') !== false) {
    $icon = 'fa-solid fa-laptop-code';
} elseif (stripos($name, 'beauty') !== false || stripos($name, 'cosmetic') !== false) {
    $icon = 'fa-solid fa-magic-wand-sparkles';
} elseif (stripos($name, 'furniture') !== false) {
    $icon = 'fa-solid fa-couch';    
} else {
    $icon = 'fa-solid fa-bag-shopping';
}

?>
<div class="group">
  <a href="#" class="block <?php echo $category['active'] ? 'border-4 border-teal-600' : 'border-4 border-transparent'; ?> bg-yellow-100 rounded-lg overflow-hidden transition-transform hover:scale-105 cursor-pointer">
    <div class="h-48 flex items-center justify-center overflow-hidden bg-yellow-50">
      <i class="<?php echo $icon; ?> text-5xl text-gray-600"></i>
    </div>
  </a>

  <div class="mt-4 text-center">
    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-teal-600 transition-colors">
      <?php echo htmlspecialchars($category['name']); ?>
    </h3>
  </div>
</div>
