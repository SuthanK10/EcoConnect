<?php
// app/views/messages/contact_ngo.php
?>
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="mb-10">
        <h1 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-2">Select an NGO</h1>
        <p class="text-[15px] text-[#677e6b] dark:text-gray-400 font-medium">Choose an organization to message directly.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (empty($ngos)): ?>
            <div class="col-span-1 md:col-span-2 py-12 text-center rounded-[32px] bg-gray-50 dark:bg-darkSurface border border-dashed border-gray-200 dark:border-white/10">
                <i data-lucide="building-2" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-sm font-bold text-[#677e6b] dark:text-gray-400">No active NGOs found to contact at the moment.</p>
            </div>
        <?php else: ?>
            <?php foreach ($ngos as $ngo): ?>
                <a href="index.php?route=message_chat&with=<?= (int)$ngo['user_id'] ?>" 
                   class="group bg-white dark:bg-darkSurface rounded-[32px] p-8 shadow-sm border border-gray-100 dark:border-white/5 hover:border-primary transition-all duration-300">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gray-50 flex items-center justify-center">
                            <?php if ($ngo['logo_path']): ?>
                                <img src="<?= h($ngo['logo_path']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <i data-lucide="building-2" class="w-8 h-8 text-gray-300"></i>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-black text-[#121613] dark:text-white group-hover:text-primary transition-colors"><?= h($ngo['name']) ?></h3>
                            <p class="text-[10px] font-black text-primary dark:text-[#4ade80] uppercase tracking-widest mt-1 flex items-center gap-1.5">
                                Send Message <i data-lucide="message-square" class="w-3 h-3"></i>
                            </p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
