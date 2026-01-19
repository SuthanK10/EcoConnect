<?php
// app/views/ngo/proposals.php
?>

<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="mb-12 flex items-center justify-between">
        <div>
            <h1 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-2">Community Initiatives</h1>
            <p class="text-[17px] text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed">
                Browse environmental concerns reported by the community and adopt them as official cleanup drives.
            </p>
        </div>
        <a href="index.php?route=ngo_dashboard" class="text-sm font-bold text-[#677e6b] dark:text-gray-500 hover:text-[#2c4931] dark:hover:text-[#4ade80] transition-colors flex items-center gap-1">
            <i data-lucide="chevron-left" class="w-4 h-4"></i> Back to Dashboard
        </a>
    </div>

    <?php if (!empty($proposals)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($proposals as $prop): ?>
                <div class="group bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-500/20 flex items-center gap-1.5">
                            <i data-lucide="globe" class="w-3 h-3"></i> Community Suggestion
                        </span>
                        <span class="text-[10px] font-bold text-gray-300 dark:text-gray-600 uppercase tracking-widest">
                            Reported by <?php echo h($prop['user_name']); ?>
                        </span>
                    </div>

                    <h3 class="text-2xl font-black text-[#121613] dark:text-white mb-4 group-hover:text-[#2c4931] dark:group-hover:text-[#4ade80] transition-colors leading-tight">
                        <?php echo h($prop['title']); ?>
                    </h3>

                    <div class="space-y-3 mb-8 text-sm font-bold text-[#677e6b] dark:text-gray-400">
                        <div class="flex items-center gap-2">
                            <span class="w-10 h-10 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80]">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </span>
                            <?php echo h($prop['location']); ?>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-10 h-10 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80]">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </span>
                            Suggested: <?php echo date('M d, Y', strtotime($prop['proposed_date'])); ?>
                        </div>
                    </div>

                    <div class="bg-[#f0f5f1] dark:bg-white/5 rounded-[32px] p-8 border border-[#2c4931]/5 dark:border-white/5 mb-8">
                        <p class="text-[10px] font-black text-[#2c4931] dark:text-[#4ade80] uppercase tracking-[0.2em] mb-2">Volunteer Observation</p>
                        <p class="text-[15px] text-[#2c4931] dark:text-gray-300 leading-relaxed italic font-medium">
                            "<?php echo h($prop['description']); ?>"
                        </p>
                    </div>

                     <a href="index.php?route=ngo_adopt_proposal&proposal_id=<?php echo (int)$prop['id']; ?>" 
                        class="block w-full py-5 rounded-2xl bg-[#2c4931] dark:bg-[#4ade80] text-white dark:text-primary text-sm font-black text-center uppercase tracking-widest hover:bg-[#121613] dark:hover:bg-[#22c55e] transition-all shadow-xl shadow-[#2c4931]/10 dark:shadow-[#4ade80]/10 active:scale-95 flex items-center justify-center gap-2">
                         Adopt this Initiative <i data-lucide="arrow-right" class="w-4 h-4"></i>
                     </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-32 bg-white dark:bg-darkSurface rounded-[40px] border border-dashed border-gray-200 dark:border-white/10">
            <div class="text-7xl mb-6 flex justify-center text-gray-300">
                <i data-lucide="leaf" class="w-20 h-20"></i>
            </div>
            <h3 class="text-2xl font-black text-[#121613] dark:text-white mb-2">The community is quiet</h3>
            <p class="text-[#677e6b] dark:text-gray-400 font-medium">No approved community proposals are currently available for adoption.</p>
        </div>
    <?php endif; ?>
</div>
