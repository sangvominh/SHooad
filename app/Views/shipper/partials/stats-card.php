<div class="bg-gradient-to-br from-slate-800 to-slate-900 border border-blue-500/20 rounded-xl p-6 hover:border-blue-500/40 transition-all">
    <div class="flex items-start justify-between mb-4">
        <div>
            <p class="text-gray-400 text-sm mb-1"><?php echo isset($label) ? $label : 'Stat'; ?></p>
            <p class="text-3xl font-bold text-white"><?php echo isset($value) ? $value : '0'; ?></p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center">
            <?php if(isset($icon)): ?>
                <?php echo $icon; ?>
            <?php else: ?>
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            <?php endif; ?>
        </div>
    </div>
    <p class="text-sm text-gray-400">
        <span class="text-green-400">+<?php echo isset($change) ? $change : '0'; ?></span> this week
    </p>
</div>
