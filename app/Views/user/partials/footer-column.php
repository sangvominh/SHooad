<?php if (isset($column['is_social']) && $column['is_social']): ?>
    <!-- Social Links Column -->
    <div>
        <h3 class="text-white font-bold text-lg mb-4"><?php echo htmlspecialchars($column['title']); ?></h3>
        <div class="flex gap-3">
            <?php foreach ($column['socials'] as $social): ?>
                <a href="<?php echo htmlspecialchars($social['href']); ?>" 
                   title="<?php echo htmlspecialchars($social['label']); ?>"
                   class="bg-teal-600 hover:bg-teal-700 text-white w-10 h-10 flex items-center justify-center rounded transition-colors">
                    <?php 
                    // Social media icons
                    switch ($social['icon']) {
                        case 'f':
                            // Facebook
                            echo '<i class="fa-brands fa-facebook-f"></i>';
                            break;

                        case 'in':
                            // Instagram (đổi từ LinkedIn)
                            echo '<i class="fa-brands fa-instagram"></i>';
                            break;

                        case 'tw':
                            // Twitter (X)
                            echo '<i class="fa-brands fa-x-twitter"></i>';
                            // hoặc nếu bạn muốn icon Twitter cũ:
                            // echo '<i class="fa-brands fa-twitter"></i>';
                            break;
                    }

                    ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <!-- Regular Links Column -->
    <div>
        <h3 class="text-white font-bold text-lg mb-4"><?php echo htmlspecialchars($column['title']); ?></h3>
        <ul class="space-y-2">
            <?php foreach ($column['links'] as $link): ?>
                <li>
                    <a href="<?php echo htmlspecialchars($link['href']); ?>" 
                       class="text-gray-400 hover:text-white transition-colors">
                        <?php echo htmlspecialchars($link['text']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
