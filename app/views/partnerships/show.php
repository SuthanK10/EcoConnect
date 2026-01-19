<?php
// app/views/partnerships/show.php
?>

<div class="max-w-5xl mx-auto px-6 py-12">
    <!-- NGO Profile Header -->
    <div class="bg-white dark:bg-darkSurface rounded-[40px] p-8 md:p-12 border border-gray-100 dark:border-white/5 shadow-2xl mb-12 relative overflow-hidden">
        <!-- Decor -->
        <div class="absolute -right-12 -top-12 w-48 h-48 bg-primary/5 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
            <!-- Logo Section -->
            <div class="relative">
                <?php if (!empty($ngo['logo_path'])): ?>
                    <div class="w-48 h-48 bg-white dark:bg-darkBg rounded-[40px] flex items-center justify-center p-8 shadow-inner border border-gray-50 dark:border-white/5">
                        <img src="<?php echo h($ngo['logo_path']); ?>" alt="<?php echo h($ngo['name']); ?>" class="max-w-full max-h-full object-contain">
                    </div>
                <?php else: ?>
                    <div class="w-48 h-48 bg-[#f0f5f1] dark:bg-white/5 rounded-[40px] flex items-center justify-center text-6xl font-black text-primary dark:text-[#4ade80] shadow-inner">
                        <?php echo strtoupper(substr($ngo['name'], 0, 1)); ?>
                    </div>
                <?php endif; ?>
                <div class="absolute -bottom-4 -right-4 bg-[#2c4931] text-white px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl border-4 border-white dark:border-darkSurface">
                    Verified NGO
                </div>
            </div>

            <!-- Content Section -->
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-4xl md:text-5xl font-black text-[#121613] dark:text-white mb-4 tracking-tight">
                    <?php echo h($ngo['name']); ?>
                </h1>
                
                <p class="text-lg text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed mb-8 max-w-2xl">
                    <?php echo h($ngo['description'] ?? 'A dedicated environmental organization committed to organizing community-led cleanup projects across Sri Lanka.'); ?>
                </p>

                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <?php if (!empty($ngo['website'])): ?>
                        <a href="<?php echo h($ngo['website']); ?>" target="_blank" class="px-8 py-4 bg-primary text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-[#121613] transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                             Visit Website <i data-lucide="external-link" class="w-3 h-3"></i>
                        </a>
                    <?php endif; ?>
                    <div class="px-8 py-4 bg-gray-50 dark:bg-white/5 text-[#677e6b] dark:text-gray-400 rounded-2xl font-black uppercase tracking-widest text-xs border border-gray-100 dark:border-white/5">
                        Impact: <?php echo count($completed_drives); ?> Successful Drives
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Impact History Section -->
    <div class="space-y-8">
        <div class="flex items-center gap-4">
            <h2 class="text-xl font-black text-[#121613] dark:text-white uppercase tracking-widest">Impact History</h2>
            <div class="h-px flex-1 bg-gray-100 dark:bg-white/5"></div>
        </div>

        <?php if (empty($completed_drives)): ?>
            <div class="text-center py-20 bg-gray-50/50 dark:bg-white/5 rounded-[40px] border border-dashed border-gray-200 dark:border-white/10">
                <div class="text-5xl mb-4 flex justify-center text-gray-300">
                    <i data-lucide="leaf" class="w-12 h-12"></i>
                </div>
                <p class="text-sm font-bold text-gray-400 italic">This NGO is newly verified and is preparing its first drives.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($completed_drives as $drive): ?>
                    <div class="bg-white dark:bg-darkSurface rounded-3xl p-6 border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-xl transition-all group">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-[10px] font-black text-primary dark:text-[#4ade80] uppercase tracking-widest mb-1">Completed Mission</p>
                                <h3 class="text-lg font-black text-[#121613] dark:text-white group-hover:text-primary transition-colors"><?php echo h($drive['title']); ?></h3>
                            </div>
                            <span class="text-primary dark:text-[#4ade80]">
                                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center gap-2">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#677e6b] dark:text-gray-400"></i>
                                <span class="text-[11px] font-bold text-[#677e6b] dark:text-gray-400"><?php echo date('M d, Y', strtotime($drive['event_date'])); ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#677e6b] dark:text-gray-400"></i>
                                <span class="text-[11px] font-bold text-[#677e6b] dark:text-gray-400"><?php echo h($drive['location']); ?></span>
                            </div>
                        </div>

                        <p class="text-xs text-[#677e6b] dark:text-gray-500 line-clamp-2 leading-relaxed italic mb-0">
                            "<?php echo h(substr($drive['description'], 0, 100)); ?>..."
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Navigation Back -->
    <div class="mt-16 text-center">
        <a href="index.php?route=partnerships" class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-[#677e6b] hover:text-primary transition-all">
            <i data-lucide="chevron-left" class="w-4 h-4"></i> Back to All Partners
        </a>
    </div>
</div>
