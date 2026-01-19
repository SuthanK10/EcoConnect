<?php
// app/views/leaderboard/index.php
?>

<div class="max-w-4xl mx-auto px-6 py-12">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h2 class="text-[#121613] dark:text-white text-2xl md:text-4xl font-black tracking-tight mb-2 flex items-center gap-3">
                <i data-lucide="trophy" class="w-8 h-8 text-yellow-500"></i> Monthly Leaderboard
            </h2>
            <p class="text-[#677e6b] dark:text-gray-400 font-medium">Top contributors for the month of <?php echo date('F Y'); ?></p>
        </div>
        <div class="bg-[#f0f5f1] dark:bg-white/5 border border-[#2c4931]/10 px-6 py-3 rounded-2xl">
            <p class="text-[10px] font-black text-[#2c4931] dark:text-[#4ade80] uppercase tracking-widest mb-1">Last Updated</p>
            <p class="text-xs font-bold text-[#121613] dark:text-white"><?php echo $lastUpdated; ?></p>
        </div>
    </div>

    <!-- Leaderboard Table -->
    <div class="bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 shadow-2xl overflow-hidden relative mb-16">
        <!-- Floating Decor -->
        <div class="absolute -right-12 -top-12 w-48 h-48 bg-[#4ade80]/5 rounded-full blur-3xl"></div>
        <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-[#2c4931]/5 rounded-full blur-3xl"></div>

        <div class="overflow-x-auto relative z-10">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-100 dark:border-white/5">
                        <th class="px-4 md:px-8 py-6 text-[10px] font-black text-[#677e6b] dark:text-gray-500 uppercase tracking-[0.2em]">Rank</th>
                        <th class="px-4 md:px-8 py-6 text-[10px] font-black text-[#677e6b] dark:text-gray-500 uppercase tracking-[0.2em]">Eco Warrior</th>
                        <th class="px-4 md:px-8 py-6 text-[10px] font-black text-[#677e6b] dark:text-gray-500 uppercase tracking-[0.2em] text-right">Monthly PTS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                    <?php if (!empty($leaders)): ?>
                        <?php foreach ($leaders as $i => $leader): ?>
                            <?php 
                                $rank = $i + 1;
                                $isTop3 = $rank <= 3;
                                $rowClass = $isTop3 ? 'bg-gradient-to-r from-transparent to-transparent hover:to-[#4ade80]/5' : 'hover:bg-gray-50 dark:hover:bg-white/5';
                            ?>
                            <tr class="transition-all <?php echo $rowClass; ?> group">
                                <td class="px-4 md:px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <?php if ($rank === 1): ?>
                                            <span class="w-8 h-8 rounded-lg bg-yellow-400 flex items-center justify-center text-white font-black text-xs shadow-lg shadow-yellow-400/30">1</span>
                                        <?php elseif ($rank === 2): ?>
                                            <span class="w-8 h-8 rounded-lg bg-gray-300 flex items-center justify-center text-white font-black text-xs shadow-lg shadow-gray-300/30">2</span>
                                        <?php elseif ($rank === 3): ?>
                                            <span class="w-8 h-8 rounded-lg bg-[#b87333] flex items-center justify-center text-white font-black text-xs shadow-lg shadow-[#b87333]/30">3</span>
                                        <?php else: ?>
                                            <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-darkBg flex items-center justify-center text-[#677e6b] font-black text-xs"><?php echo $rank; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-4 md:px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-[#ebefec] dark:bg-darkBg flex items-center justify-center font-black text-primary dark:text-[#4ade80] shadow-inner text-xs">
                                            <?php echo strtoupper(substr($leader['name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <p class="font-black text-[#121613] dark:text-white group-hover:text-primary dark:group-hover:text-[#4ade80] transition-colors"><?php echo h($leader['name']); ?></p>
                                            <?php if ($rank === 1): ?>
                                                <span class="text-[9px] font-black text-yellow-500 uppercase tracking-widest">Top Performer</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-8 py-6 text-right">
                                    <span class="text-lg font-black text-[#121613] dark:text-white"><?php echo number_format($leader['points']); ?></span>
                                    <span class="text-[10px] font-black text-[#4ade80] uppercase ml-1">Pts</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-8 py-20 text-center">
                                <p class="text-sm text-[#677e6b] italic">No activity recorded for this month yet. Be the first to join a drive!</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Previous Month Highlights -->
    <?php if (!empty($pastWinners)): ?>
    <div class="space-y-6 mb-16">
        <div class="flex items-center gap-4 px-4">
            <h3 class="text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em]">Previous Month Champions (<?php echo h($pastWinners[0]['month_year']); ?>)</h3>
            <div class="h-px flex-1 bg-gray-100 dark:bg-white/5"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <?php foreach ($pastWinners as $index => $winner): ?>
                <div class="bg-gray-50/50 dark:bg-white/5 rounded-3xl p-6 border border-gray-100 dark:border-white/5 text-center group hover:bg-white dark:hover:bg-darkSurface transition-all hover:shadow-xl">
                    <div class="w-12 h-12 rounded-2xl bg-white dark:bg-darkBg flex items-center justify-center text-sm font-black mx-auto mb-4 silver-badge">
                        <?php 
                            if ($index === 0) echo '<i data-lucide="crown" class="w-6 h-6 text-yellow-500"></i>'; 
                            elseif ($index === 1) echo '<i data-lucide="medal" class="w-6 h-6 text-gray-400"></i>'; 
                            elseif ($index === 2) echo '<i data-lucide="award" class="w-6 h-6 text-[#b87333]"></i>'; 
                            else echo ($index + 1); 
                        ?>
                    </div>
                    <p class="text-xs font-black text-[#121613] dark:text-white truncate mb-1"><?php echo h($winner['user_name']); ?></p>
                    <p class="text-[10px] font-bold text-primary dark:text-[#4ade80]"><?php echo number_format($winner['points']); ?> PTS</p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Info Box -->
    <div class="p-8 bg-primary rounded-[32px] text-white shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
            <div class="text-4xl text-[#4ade80]">
                <i data-lucide="gift" class="w-12 h-12"></i>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h4 class="text-xl font-bold mb-2">Build Your Legacy</h4>
                <p class="text-sm text-white/70 font-medium">The top 10 users at the end of each month receive exclusive bonus XP and special profile badges. Keep cleaning!</p>
            </div>
            <a href="index.php?route=events" class="px-8 py-4 bg-[#4ade80] text-primary rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-white transition-all transform hover:scale-105 active:scale-95 shadow-lg shadow-[#4ade80]/20 whitespace-nowrap">
                Join a Drive Now
            </a>
        </div>
    </div>
</div>
