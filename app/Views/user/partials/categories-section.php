<?php
$categories = [
    ['id'=>1,'name'=>'Men Fashion','active'=>false],
    ['id'=>2,'name'=>'Women Fashion','active'=>false],
    ['id'=>3,'name'=>'Kids Fashion','active'=>false],
    ['id'=>4,'name'=>'Baby Fashion','active'=>false],
    ['id'=>5,'name'=>'Mobile Device','active'=>false],
    ['id'=>6,'name'=>'Computer Device','active'=>false],
    ['id'=>7,'name'=>'Beauty Products','active'=>false],
    ['id'=>8,'name'=>'Furniture','active'=>false],
];
?>
<section class="py-16 px-4 bg-gray-50">
  <div class="max-w-7xl mx-auto">
    <div class="mb-12">
      <h2 class="text-4xl md:text-5xl font-bold font-serif text-gray-900">
        Explore, find exactly<br />what you need
      </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($categories as $category): ?>
        <?php include 'category-card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
