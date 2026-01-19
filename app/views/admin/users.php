<?php
// app/views/admin/users.php
?>

<div class="max-w-5xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-8">
    <h2 class="text-[#121613] dark:text-white text-3xl font-black flex items-center gap-3">
      <span class="w-2 h-8 bg-[#2c4931] rounded-full"></span>
      Manage Users
    </h2>
    <div class="bg-gray-50 dark:bg-darkSurface px-4 py-2 rounded-2xl border border-gray-100 dark:border-white/5">
      <p class="text-xs font-black text-[#677e6b] uppercase tracking-widest">Total Community: <?= count($users) ?></p>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-4">
    <?php foreach ($users as $u): ?>
      <div class="flex items-center justify-between bg-white dark:bg-darkSurface p-6 rounded-[32px] border border-gray-100 dark:border-white/5 shadow-sm group hover:shadow-md transition-all <?= $u['role'] !== 'admin' ? 'cursor-pointer hover:bg-gray-50 dark:hover:bg-white/5' : '' ?>"
           <?= $u['role'] !== 'admin' ? "onclick=\"window.location.href='index.php?route=admin_user_view&id={$u['id']}'\"" : '' ?>>
        <div class="flex items-center gap-5">
          <div class="w-12 h-12 rounded-2xl bg-[#ebefec] dark:bg-darkBg flex items-center justify-center font-black text-[#2c4931] dark:text-[#4ade80] text-xl shadow-inner">
            <?= strtoupper(substr($u['name'], 0, 1)) ?>
          </div>
          <div>
            <div class="flex items-center gap-2">
              <p class="font-black text-[#121613] dark:text-white text-lg"><?= h($u['name']) ?></p>
              <?php if ($u['role'] === 'admin'): ?>
                <span class="px-2 py-0.5 bg-[#2c4931] text-white text-[10px] font-black uppercase tracking-widest rounded-full">Admin</span>
              <?php endif; ?>
            </div>
            <p class="text-sm text-[#677e6b] dark:text-gray-400 font-medium lowercase">
              <?= h($u['email']) ?> · <span class="capitalize"><?= h($u['role']) ?></span>
            </p>
            
            <div class="flex flex-wrap gap-2 mt-2">
              <?php if (!$u['is_active']): ?>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-50 dark:bg-red-900/10 text-red-600 dark:text-red-400 text-[10px] font-black uppercase tracking-widest rounded-md border border-red-100 dark:border-red-900/20">
                  <span class="w-1 h-1 bg-red-600 rounded-full"></span> Deactivated
                </span>
              <?php endif; ?>

              <?php if ($u['role'] === 'user'): ?>
                <?php 
                  $lastActive = $u['last_active_at'] ? strtotime($u['last_active_at']) : 0;
                  $daysInactive = floor((time() - $lastActive) / 86400);
                  $statusColor = 'bg-green-50 text-green-600 border-green-100 dark:bg-green-500/10 dark:text-green-400 dark:border-green-500/20';
                  $statusText = 'Active Clean-up Warrior';
                  
                  if ($daysInactive >= 30) {
                      $statusColor = 'bg-red-50 text-red-600 border-red-100 dark:bg-red-500/10 dark:text-red-400 dark:border-red-900/20';
                      $statusText = 'Inactive (' . $daysInactive . ' days)';
                  } elseif ($daysInactive >= 7) {
                      $statusColor = 'bg-orange-50 text-orange-600 border-orange-100 dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20';
                      $statusText = 'Quiet for ' . $daysInactive . ' days';
                  }
                ?>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 <?= $statusColor ?> text-[10px] font-black uppercase tracking-widest rounded-md border">
                  Last Active: <?= $u['last_active_at'] ? date('M d, Y', $lastActive) : 'Never' ?> (<?= $statusText ?>)
                </span>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <?php if ($u['role'] !== 'admin'): ?>
          <div class="flex items-center gap-2 text-gray-400 group-hover:text-primary dark:group-hover:text-[#4ade80] transition-colors">
             <span class="text-[10px] font-black uppercase tracking-widest">View Details</span>
             <i data-lucide="chevron-right" class="w-4 h-4"></i>
          </div>
        <?php else: ?>
          <div class="px-4 py-2 bg-gray-50 dark:bg-darkBg rounded-xl border border-dashed border-gray-200 dark:border-white/10">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">System Protected</p>
          </div>
          <?php if ($u['id'] === $_SESSION['user_id']): ?>
            <!-- Current admin indicator -->
            <div class="ml-3 w-2 h-2 rounded-full bg-[#4ade80] shadow-[0_0_8px_#4ade80]" title="You are logged in as this user"></div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
