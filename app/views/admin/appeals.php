<?php
// app/views/admin/appeals.php
?>

<div class="max-w-5xl mx-auto px-4 py-12">
  <div class="mb-10 flex items-center justify-between">
    <div>
      <h1 class="text-[#121613] dark:text-white text-3xl font-black tracking-tight mb-2">Reactivation Appeals</h1>
      <p class="text-sm text-[#677e6b] dark:text-gray-400">Review and handle account reactivation requests from deactivated users.</p>
    </div>
    <a href="index.php?route=admin_dashboard" class="text-sm font-bold text-[#677e6b] hover:text-[#2c4931] dark:hover:text-[#4ade80] transition-colors flex items-center gap-2">
      <i data-lucide="chevron-left" class="w-4 h-4"></i> Back to Dashboard
    </a>
  </div>

  <?php if (!empty($pendingAppeals)): ?>
    <div class="space-y-6">
      <?php foreach ($pendingAppeals as $appeal): ?>
        <div class="bg-white dark:bg-darkSurface rounded-[32px] border border-gray-100 dark:border-white/5 p-8 shadow-sm hover:shadow-md transition-all">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            
            <div class="flex-1">
              <div class="flex items-center gap-3 mb-4">
                <span class="px-3 py-1 rounded-full bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5">
                  <i data-lucide="clock" class="w-3 h-3"></i> Pending Review
                </span>
                <span class="text-[10px] font-bold text-gray-300 dark:text-gray-500 uppercase tracking-widest">
                  Submitted <?php echo date('M d, Y', strtotime($appeal['created_at'])); ?>
                </span>
              </div>
              
              <h3 class="text-xl font-black text-[#121613] dark:text-white mb-1">
                <?php echo h($appeal['name']); ?>
              </h3>
              <p class="text-sm text-[#677e6b] dark:text-gray-400 mb-4">
                <?php echo h($appeal['email']); ?> • <span class="uppercase font-bold tracking-tighter"><?php echo h($appeal['role']); ?></span>
              </p>
              
              <div class="bg-[#f0f5f1] dark:bg-white/5 rounded-2xl p-6 border border-[#2c4931]/5 dark:border-white/5">
                <p class="text-[10px] font-black text-[#2c4931] dark:text-[#4ade80] uppercase tracking-[0.2em] mb-2">Internal Message</p>
                <p class="text-sm text-[#2c4931] dark:text-gray-300 leading-relaxed italic">
                  "<?php echo h($appeal['message']); ?>"
                </p>
              </div>
            </div>

            <div class="flex flex-row md:flex-col gap-3">
              <form action="index.php?route=admin_handle_appeal" method="post" class="w-full">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="appeal_id" value="<?php echo $appeal['id']; ?>">
                <input type="hidden" name="action" value="approve">
                <button type="submit" class="w-full py-4 px-8 rounded-2xl bg-[#2c4931] dark:bg-[#4ade80] text-white dark:text-primary text-sm font-black tracking-wide hover:shadow-lg active:scale-95 transition-all">
                  Approve & Reactivate
                </button>
              </form>
              
              <form action="index.php?route=admin_handle_appeal" method="post" class="w-full">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="appeal_id" value="<?php echo $appeal['id']; ?>">
                <input type="hidden" name="action" value="reject">
                <button type="submit" class="w-full py-4 px-8 rounded-2xl bg-white dark:bg-white/5 border border-red-100 dark:border-red-500/20 text-red-600 dark:text-red-400 text-sm font-black tracking-wide hover:bg-red-50 dark:hover:bg-red-500/10 transition-all">
                  Reject Appeal
                </button>
              </form>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="text-center py-24 bg-white dark:bg-darkSurface rounded-[40px] border border-dashed border-gray-200 dark:border-white/10">
      <div class="text-6xl mb-6 flex justify-center text-gray-300">
        <i data-lucide="mail-open" class="w-16 h-16"></i>
      </div>
      <h3 class="text-xl font-black text-[#121613] dark:text-white mb-2">No pending appeals</h3>
      <p class="text-[#677e6b] dark:text-gray-400">Fantastic! All account reactivation requests have been processed.</p>
    </div>
  <?php endif; ?>
</div>
