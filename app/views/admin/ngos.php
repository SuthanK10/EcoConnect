<?php
// app/views/admin/ngos.php
?>

<div class="max-w-6xl mx-auto px-6 py-8">
  <div class="mb-10">
    <h2 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-2">Manage NGOs</h2>
    <p class="text-[15px] text-[#677e6b] dark:text-gray-400 font-medium">Review, approve, and manage environmental organizations on the platform.</p>
  </div>

  <!-- PENDING NGO REQUESTS -->
  <section class="mb-16">
    <h3 class="flex items-center gap-3 text-lg font-black text-[#121613] dark:text-white mb-6">
      <span class="w-2 h-7 bg-yellow-400 rounded-full"></span>
      Pending Approval
      <span class="ml-2 px-3 py-1 bg-yellow-400/10 text-yellow-700 dark:text-yellow-500 text-[10px] font-black uppercase rounded-full">
        <?php echo count($pendingNgos); ?> Pending
      </span>
    </h3>

    <?php if (empty($pendingNgos)): ?>
      <div class="rounded-[40px] border border-dashed border-gray-200 bg-white/50 p-12 text-center">
        <p class="text-sm font-bold text-gray-400 italic">No pending NGO requests right now.</p>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($pendingNgos as $ngo): ?>
          <div class="group rounded-[40px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-xl transition-all duration-300 p-8 flex flex-col">
            <div class="flex items-start justify-between mb-6">
              <div class="w-16 h-16 rounded-[24px] bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-3xl shadow-inner font-black text-[#2c4931] dark:text-[#4ade80]">
                <?php echo strtoupper(substr($ngo['name'], 0, 1)); ?>
              </div>
              <div class="flex flex-col gap-2">
                <form method="post" class="flex flex-col gap-2">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="ngo_id" value="<?php echo (int)$ngo['id']; ?>" />
                  <button name="action" value="approve" class="px-6 py-2.5 rounded-2xl bg-[#2c4931] dark:bg-[#4ade80] text-white dark:text-primary text-xs font-black hover:bg-[#121613] dark:hover:bg-white transition-all shadow-lg active:scale-95">
                    Approve
                  </button>
                  <button name="action" value="reject" class="px-6 py-2.5 rounded-2xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-xs font-black hover:bg-red-100 dark:hover:bg-red-500/20 transition-all active:scale-95">
                    Reject
                  </button>
                </form>
              </div>
            </div>

            <div class="mb-6 flex-1">
              <div class="mb-4">
                <?php if (!empty($ngo['logo_path'])): ?>
                    <img src="<?php echo h($ngo['logo_path']); ?>" alt="Logo" class="w-16 h-16 rounded-2xl object-contain border border-gray-100 dark:border-white/5 p-1 mb-4">
                <?php endif; ?>
                <h4 class="text-xl font-black text-[#121613] dark:text-white mb-1"><?php echo h($ngo['name']); ?></h4>
                <div class="mt-2">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Contact Email</p>
                    <p class="text-sm font-bold text-[#2c4931] dark:text-[#4ade80]"><?php echo h($ngo['email']); ?></p>
                </div>
              </div>
              
              <?php if (!empty($ngo['description'])): ?>
                <p class="text-xs text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed mb-6 italic line-clamp-3">
                  "<?php echo h($ngo['description']); ?>"
                </p>
              <?php endif; ?>

              <?php if (!empty($ngo['verification_link'])): ?>
                <a href="<?php echo h($ngo['verification_link']); ?>" target="_blank" 
                   class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-800 transition-colors">
                  Check Verification URL 
                  <i data-lucide="external-link" class="w-3 h-3"></i>
                </a>
              <?php endif; ?>
            </div>
            
            <div class="pt-6 border-t border-gray-50 dark:border-white/5 flex items-center justify-between">
              <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Awaiting Review</span>
              <span class="text-[10px] font-bold text-gray-400"><?php echo date('M d, Y'); ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <!-- APPROVED NGOs -->
  <section>
    <h3 class="flex items-center gap-3 text-lg font-black text-[#121613] dark:text-white mb-6">
      <span class="w-2 h-7 bg-[#4ade80] rounded-full"></span>
      Active Organizations
      <span class="ml-2 px-3 py-1 bg-[#4ade80]/10 text-primary dark:text-[#4ade80] text-[10px] font-black uppercase rounded-full border border-transparent dark:border-[#4ade80]/20">
        <?php echo count($approvedNgos); ?> Active
      </span>
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php if (empty($approvedNgos)): ?>
        <div class="col-span-full rounded-[40px] border border-[#d8dfd9] bg-white p-12 text-center">
          <p class="text-sm font-bold text-gray-400 italic">No approved NGOs yet.</p>
        </div>
      <?php endif; ?>

      <?php foreach ($approvedNgos as $ngo): ?>
        <div class="rounded-[40px] bg-white dark:bg-darkSurface border border-gray-50 dark:border-white/5 shadow-sm hover:shadow-md transition-all p-8 border-t-4 border-t-[#4ade80]">
          <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-[#ebefec] dark:bg-white/5 flex items-center justify-center text-xl font-black text-[#2c4931] dark:text-[#4ade80]">
              <?php echo strtoupper(substr($ngo['name'], 0, 1)); ?>
            </div>
            <div class="flex-1">
              <h4 class="text-base font-black text-[#121613] dark:text-white leading-tight"><?php echo h($ngo['name']); ?></h4>
              <p class="text-[10px] font-bold text-[#4ade80] uppercase tracking-wider mt-0.5">Verified</p>
            </div>
          </div>

          <div class="space-y-4 mb-8">
            <div>
              <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Organization Email</p>
              <p class="text-xs font-bold text-[#121613] dark:text-white break-all"><?php echo h($ngo['email']); ?></p>
            </div>
          </div>

          <form method="post" onsubmit="return confirm('Archive this NGO? User access will be revoked.');">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="ngo_id" value="<?php echo (int)$ngo['id']; ?>" />
            <button name="action" value="delete" 
                    class="w-full py-3 rounded-2xl bg-gray-50 dark:bg-white/5 text-gray-500 text-[10px] font-black uppercase tracking-widest hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400 transition-all border border-gray-100 dark:border-white/10">
              Archive Organization
            </button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>
