<?php
// app/views/admin/user_view.php
?>

<div class="max-w-4xl mx-auto px-6 py-8">
    <!-- Header with Back Button -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-[#121613] dark:text-white text-3xl font-black mb-2"><?= h($user['name']) ?></h2>
            <p class="text-[15px] text-[#677e6b] dark:text-gray-400 font-medium">Detailed overview of volunteer activity and account status.</p>
        </div>
        <a href="index.php?route=admin_users" 
           onclick="if(document.referrer.includes('route=admin_users')){history.back(); return false;}"
           class="px-5 py-2.5 rounded-2xl bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/10 text-xs font-black uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-white/5 transition-all shadow-sm flex items-center gap-2">
            <i data-lucide="chevron-left" class="w-4 h-4 text-primary dark:text-[#4ade80]"></i> Back to List
        </a>
    </div>

    <!-- User Profile Stats Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white dark:bg-darkSurface p-6 rounded-[32px] border border-gray-100 dark:border-white/5 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Email Address</p>
            <p class="text-sm font-bold text-[#121613] dark:text-white break-all"><?= h($user['email']) ?></p>
        </div>
        <div class="bg-white dark:bg-darkSurface p-6 rounded-[32px] border border-gray-100 dark:border-white/5 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Spendable Balance</p>
            <p class="text-2xl font-black text-[#2c4931] dark:text-[#4ade80]"><?= (int)$user['points'] ?> <span class="text-xs font-bold text-gray-400">PTS</span></p>
        </div>
        <div class="bg-white dark:bg-darkSurface p-6 rounded-[32px] border border-gray-100 dark:border-white/5 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Lifetime Experience</p>
            <p class="text-2xl font-black text-blue-600 dark:text-blue-400"><?= (int)$user['lifetime_points'] ?> <span class="text-xs font-bold text-gray-400">XP</span></p>
        </div>
        <div class="bg-white dark:bg-darkSurface p-6 rounded-[32px] border border-gray-100 dark:border-white/5 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Account Status</p>
            <?php if ($user['is_active']): ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-green-100 dark:border-green-500/20">
                    Active
                </span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-red-100 dark:border-red-500/20">
                    Deactivated
                </span>
            <?php endif; ?>
        </div>
        <div class="bg-white dark:bg-darkSurface p-6 rounded-[32px] border border-gray-100 dark:border-white/5 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Last Activity</p>
            <p class="text-sm font-bold text-[#121613] dark:text-white">
                <?php 
                   if ($user['last_active_at']) {
                       $lastActive = strtotime($user['last_active_at']);
                       echo date('M d, Y', $lastActive);
                       echo " <span class='text-[10px] font-normal text-gray-400'>(" . date('H:i', $lastActive) . ")</span>";
                   } else {
                       echo "Never";
                   }
                ?>
            </p>
            <?php if ($user['last_active_at']): ?>
                <?php 
                    $days = floor((time() - strtotime($user['last_active_at'])) / 86400); 
                    $color = $days >= 30 ? 'text-red-500' : ($days >= 7 ? 'text-orange-500' : 'text-green-500');
                ?>
                <p class="text-[10px] font-black uppercase tracking-tighter <?= $color ?> mt-1">
                    <?= $days == 0 ? 'Active Today' : ($days == 1 ? '1 day inactive' : $days . ' days inactive') ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Drives Participated -->
    <div class="mb-12">
        <h3 class="text-xl font-black text-[#121613] dark:text-white mb-6 flex items-center gap-3">
            <span class="w-1.5 h-6 bg-[#4ade80] rounded-full"></span>
            Volunteer History
            <span class="text-xs font-medium text-gray-400">(<?= count($drives) ?> Drives)</span>
        </h3>

        <div class="space-y-4">
            <?php if (empty($drives)): ?>
                <div class="p-12 text-center rounded-[32px] border-2 border-dashed border-gray-100 dark:border-white/5">
                    <p class="text-gray-400 italic text-sm font-medium">This user hasn't joined any drives yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($drives as $drive): ?>
                    <?php 
                        $statusClass = 'bg-gray-100 text-gray-600';
                        if ($drive['status'] === 'completed') $statusClass = 'bg-[#f0f5f1] text-[#2c4931] dark:bg-white/5 dark:text-[#4ade80]';
                        if ($drive['status'] === 'accepted') $statusClass = 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400';
                    ?>
                    <div class="flex items-center justify-between bg-white dark:bg-darkSurface p-5 rounded-[28px] border border-gray-100 dark:border-white/5 shadow-sm transition-transform hover:scale-[1.01]">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-darkBg flex items-center justify-center text-primary dark:text-[#4ade80]">
                                <i data-lucide="leaf" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="font-black text-[#121613] dark:text-white text-sm"><?= h($drive['title']) ?></p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                    <?= date('M d, Y', strtotime($drive['event_date'])) ?> · <?= h($drive['location']) ?>
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full <?= $statusClass ?> text-[9px] font-black uppercase tracking-widest">
                            <?= h($drive['status']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Account Actions -->
    <div class="pt-10 border-t border-gray-100 dark:border-white/10 flex flex-col md:flex-row gap-4">
        <form action="index.php?route=admin_users" method="post" class="flex-1">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <input type="hidden" name="action" value="<?= $user['is_active'] ? 'deactivate' : 'activate' ?>">
            <button class="w-full py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg transform active:scale-95
                <?= $user['is_active'] ? 'bg-amber-500 hover:bg-amber-600 text-white shadow-amber-500/20' : 'bg-[#2c4931] hover:bg-[#121613] text-white shadow-primary/20' ?>">
                <?= $user['is_active'] ? 'Deactivate Account' : 'Reactivate Account' ?>
            </button>
        </form>

        <form action="index.php?route=admin_users" method="post" class="flex-1" onsubmit="return confirm('🚨 DANGER: This will permanently delete this user and all their associated data. This action CANNOT be undone. Continue?');">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <input type="hidden" name="action" value="delete">
            <button class="w-full py-4 rounded-2xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 font-black text-xs uppercase tracking-widest transition-all border border-red-100 dark:border-red-500/20 shadow-lg shadow-red-500/10 transform active:scale-95">
                Delete User Permanently
            </button>
        </form>
    </div>
</div>
