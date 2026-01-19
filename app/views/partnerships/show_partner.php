<?php
// app/views/partnerships/show_partner.php
?>

<div class="max-w-4xl mx-auto px-6 py-20 text-center">
    <div class="mb-10 flex justify-center">
        <?php if (!empty($partner['logo'])): ?>
            <div class="w-48 h-48 bg-white rounded-full flex items-center justify-center p-8 shadow-2xl border border-gray-100">
                <img src="<?php echo h($partner['logo']); ?>" alt="<?php echo h($partner['name']); ?>" class="max-w-full max-h-full object-contain">
            </div>
        <?php else: ?>
            <div class="w-48 h-48 bg-primary text-white rounded-full flex items-center justify-center text-6xl font-black">
                <?php echo strtoupper(substr($partner['name'], 0, 1)); ?>
            </div>
        <?php endif; ?>
    </div>

    <h1 class="text-4xl font-black text-[#121613] dark:text-white mb-6"><?php echo h($partner['name']); ?></h1>
    <p class="text-lg text-[#677e6b] dark:text-gray-400 mb-12 max-w-2xl mx-auto font-medium">
        We are proud to have <?php echo h($partner['name']); ?> as a corporate partner. Together, we are building a more sustainable future for our communities.
    </p>

    <div class="flex flex-col md:flex-row gap-4 justify-center">
        <a href="index.php?route=partnerships" class="px-8 py-4 bg-primary text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-[#121613] transition-all transform hover:scale-105">
            Back to Partners
        </a>
    </div>
</div>
