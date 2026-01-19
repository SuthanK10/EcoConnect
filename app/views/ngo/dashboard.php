<div class="max-w-6xl mx-auto px-4 py-12">
  <!-- Top Header -->
  <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
      <?php if (isset($_SESSION['flash_success'])): ?>
          <div class="mb-6 p-4 rounded-2xl bg-green-500/10 border border-green-500/20 text-green-500 text-sm font-black uppercase tracking-widest flex items-center gap-3 animate-bounce">
              <i data-lucide="check-circle-2" class="w-5 h-5"></i> <?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
          </div>
      <?php endif; ?>
      <div class="flex items-center gap-3 mb-3">
        <h1 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight">NGO Command Center</h1>
        <?php if ($ngo['status'] === 'approved'): ?>
          <span class="px-3 py-1 rounded-full bg-[#4ade80]/10 text-primary dark:text-[#4ade80] text-[10px] font-black uppercase tracking-widest border border-[#4ade80]/20 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-[#4ade80] shadow-[0_0_8px_#4ade80]"></span> Verified Partner
          </span>
        <?php else: ?>
          <span class="px-3 py-1 rounded-full bg-yellow-400/10 text-yellow-700 dark:text-yellow-400 text-[10px] font-black uppercase tracking-widest border border-yellow-400/20 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span> Pending Review
          </span>
        <?php endif; ?>
      </div>
      <p class="text-[17px] text-[#677e6b] dark:text-gray-400 font-bold"><?php echo h($ngo['name']); ?></p>
    </div>
    
    <div class="flex flex-wrap gap-3">
      <a href="index.php?route=messages" class="min-h-[48px] px-6 py-3 rounded-2xl bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/10 flex items-center justify-center text-center text-sm font-black text-[#121613] dark:text-white hover:shadow-lg transition-all active:scale-95 gap-2">
        Messages <i data-lucide="message-square" class="w-4 h-4 text-primary dark:text-[#4ade80]"></i>
      </a>
      <a href="index.php?route=contact_admin" class="min-h-[48px] px-6 py-3 rounded-2xl bg-[#ebefec] dark:bg-white/5 flex items-center justify-center text-center text-sm font-black text-[#121613] dark:text-white hover:shadow-lg transition-all active:scale-95 gap-2">
        Contact Admin <i data-lucide="shield-check" class="w-4 h-4 text-primary dark:text-[#4ade80]"></i>
      </a>
      <a href="index.php?route=ngo_profile_edit" class="min-h-[48px] px-6 py-3 rounded-2xl bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/10 flex items-center justify-center text-center text-sm font-black text-[#121613] dark:text-white hover:shadow-lg transition-all active:scale-95 gap-2">
        Manage Profile <i data-lucide="settings" class="w-4 h-4 text-primary dark:text-[#4ade80]"></i>
      </a>
      <?php if ($ngo['status'] === 'approved'): ?>
        <a href="index.php?route=ngo_feedback" class="min-h-[48px] px-6 py-3 rounded-2xl bg-[#ebefec] dark:bg-white/5 flex items-center justify-center text-center text-sm font-black text-[#121613] dark:text-white hover:shadow-lg transition-all active:scale-95 gap-2">
          Review Feedback <i data-lucide="message-circle" class="w-4 h-4 text-primary dark:text-[#4ade80]"></i>
        </a>
        <a href="index.php?route=ngo_proposals" class="min-h-[48px] px-6 py-3 rounded-2xl bg-[#ebefec] dark:bg-white/5 flex items-center justify-center text-center text-sm font-black text-[#121613] dark:text-white hover:shadow-lg transition-all active:scale-95 gap-2">
          Adopt Community Ideas <i data-lucide="lightbulb" class="w-4 h-4 text-primary dark:text-[#4ade80]"></i>
        </a>
        <a href="index.php?route=ngo_project_new" class="min-h-[48px] px-8 py-3 rounded-2xl bg-primary dark:bg-[#4ade80] dark:text-primary flex items-center justify-center text-center text-sm font-black text-white hover:scale-105 transition-all shadow-xl shadow-primary/20 active:scale-95 gap-2">
          New Cleanup Drive <i data-lucide="plus" class="w-4 h-4"></i>
        </a>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($ngo['status'] !== 'approved'): ?>
    <div class="mb-10 rounded-[32px] bg-yellow-50 dark:bg-yellow-500/5 border border-yellow-100 dark:border-yellow-500/10 p-8 flex items-start gap-6">
      <div class="w-12 h-12 rounded-2xl bg-white dark:bg-darkSurface flex items-center justify-center text-yellow-600 shadow-sm">
        <i data-lucide="clock" class="w-6 h-6"></i>
      </div>
      <div>
        <h4 class="text-lg font-black text-yellow-800 dark:text-yellow-400 mb-1">Account Under Review</h4>
        <p class="text-sm text-yellow-700/80 dark:text-yellow-600 font-medium leading-relaxed max-w-2xl">
          The Eco-Connect administration is currently verifying your NGO details. You can explore the dashboard, but drive creation and attendance management will be enabled once your account is fully approved.
        </p>
      </div>
    </div>
  <?php endif; ?>

  <!-- Projects List -->
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-4 mb-2">
        <div class="flex items-center gap-4">
            <h3 class="text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em]">Cleanup Missions</h3>
            <span class="px-2 py-0.5 bg-gray-100 dark:bg-white/5 rounded-full text-[10px] font-black text-primary dark:text-[#4ade80]">
                <span id="project-count">0</span> Registered
            </span>
        </div>
        
        <!-- Filter Toggle -->
        <div class="flex bg-[#ebefec] dark:bg-white/5 p-1 rounded-2xl w-full sm:w-auto">
            <button onclick="filterNGOProjects('ongoing')" id="btn-ongoing" 
                    class="flex-1 sm:flex-none px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                Ongoing
            </button>
            <button onclick="filterNGOProjects('all')" id="btn-all" 
                    class="flex-1 sm:flex-none px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                All History
            </button>
        </div>
    </div>

    <?php if (!empty($projects)): ?>
      <div class="grid grid-cols-1 gap-6">
        <?php foreach ($projects as $project): ?>
          <?php
            $isActuallyPast = project_is_past($project);
            $isClosedInDb = ($project['status'] === 'closed');
            $isExpired = ($isActuallyPast || $isClosedInDb);
            $displayStatus = $isExpired ? 'Closed' : 'Open';
            $isLive = ($displayStatus === 'Open');
            $statusColor = $isLive ? 'bg-green-500' : 'bg-red-500';
            $cardAccent = $isLive ? 'border-l-4 border-l-[#4ade80]' : 'border-l-4 border-l-red-200 dark:border-l-red-900';
          ?>
          <div class="ngo-project-card group bg-white dark:bg-darkSurface rounded-[32px] border border-gray-100 dark:border-white/5 p-8 <?php echo $cardAccent; ?> shadow-sm hover:shadow-xl transition-all duration-300"
               data-expired="<?php echo $isExpired ? 'true' : 'false'; ?>">
            <div class="flex flex-col lg:flex-row justify-between gap-8">
              
              <!-- Project Meta -->
              <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                  <div class="flex items-center gap-1.5 px-3 py-1 rounded-full <?php echo $isLive ? 'bg-[#f0fdf4] dark:bg-green-500/10 text-primary dark:text-[#4ade80]' : 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400'; ?> text-[10px] font-black uppercase tracking-widest">
                    <span class="w-1.5 h-1.5 rounded-full <?php echo $statusColor; ?> <?php echo $isLive ? 'animate-pulse' : ''; ?>"></span>
                    <?php echo $displayStatus; ?>
                  </div>
                  <span class="text-[10px] font-black text-gray-300 dark:text-gray-600 uppercase tracking-widest">
                    <?php echo (int)$project['points_reward']; ?> XP Rewarded
                  </span>
                </div>
                
                <h4 class="text-2xl font-black text-[#121613] dark:text-white mb-4 group-hover:text-primary dark:group-hover:text-[#4ade80] transition-colors leading-tight">
                  <?php echo h($project['title']); ?>
                </h4>
                
                <div class="flex flex-wrap gap-6 text-sm font-bold text-[#677e6b] dark:text-gray-400">
                  <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80]">
                      <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </span>
                    <?php echo h($project['location']); ?>
                  </div>
                  <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80]">
                      <i data-lucide="calendar" class="w-4 h-4"></i>
                    </span>
                    <?php echo date('M d, Y', strtotime($project['event_date'])); ?>
                  </div>
                </div>
              </div>

              <!-- Action Stack -->
              <div class="flex flex-col sm:flex-row lg:flex-col gap-3 min-w-[240px]">
                <?php if ($ngo['status'] === 'approved'): ?>
                  <a href="index.php?route=ngo_project_edit&id=<?php echo (int)$project['id']; ?>"
                     class="flex-1 py-4 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 flex items-center justify-center text-xs font-black text-[#121613] dark:text-white hover:bg-gray-50 dark:hover:bg-white/10 transition-all active:scale-95 gap-2">
                    Update Details <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                  </a>
                  
                  <?php if ($isLive): ?>
                    <div class="flex gap-3 flex-1">
                        <a href="index.php?route=ngo_generate_qr&project_id=<?php echo (int)$project['id']; ?>&type=checkin"
                           class="flex-1 py-4 rounded-2xl bg-primary dark:bg-[#4ade80] dark:text-primary flex items-center justify-center text-[10px] font-black text-white uppercase tracking-widest hover:scale-95 transition-all shadow-lg shadow-primary/10">
                          Check-In QR
                        </a>
                        <a href="index.php?route=ngo_generate_qr&project_id=<?php echo (int)$project['id']; ?>&type=checkout"
                           class="flex-1 py-4 rounded-2xl bg-[#0369a1] dark:bg-blue-400 dark:text-primary flex items-center justify-center text-[10px] font-black text-white uppercase tracking-widest hover:scale-95 transition-all shadow-lg shadow-blue-500/10">
                          Check-Out QR
                        </a>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>

            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-center py-24 bg-white dark:bg-darkSurface rounded-[40px] border border-dashed border-gray-200 dark:border-white/10">
        <div class="text-6xl mb-6 font-bold flex justify-center text-gray-300">
          <i data-lucide="sprout" class="w-16 h-16"></i>
        </div>
        <h3 class="text-xl font-black text-[#121613] dark:text-white mb-2">Build your first drive</h3>
        <p class="text-[#677e6b] dark:text-gray-400 mb-8 max-w-md mx-auto font-medium">Create impact by listing your cleanup drives. Once approved, you can start tracking volunteer attendance.</p>
        <?php if ($ngo['status'] === 'approved'): ?>
          <a href="index.php?route=ngo_project_new" class="inline-flex rounded-2xl bg-primary dark:bg-[#4ade80] dark:text-primary px-8 py-4 text-sm font-black text-white hover:scale-105 transition-all shadow-lg">
            Create Project Now
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
function filterNGOProjects(mode) {
    const cards = document.querySelectorAll('.ngo-project-card');
    const btnOngoing = document.getElementById('btn-ongoing');
    const btnAll = document.getElementById('btn-all');
    let visibleCount = 0;

    cards.forEach(card => {
        const isExpired = card.dataset.expired === 'true';
        if (mode === 'ongoing' && isExpired) {
            card.style.display = 'none';
        } else {
            card.style.display = 'block';
            visibleCount++;
        }
    });

    // Update count
    document.getElementById('project-count').innerText = visibleCount;

    // Update buttons
    const activeClasses = ['bg-white', 'dark:bg-white/10', 'text-primary', 'dark:text-[#4ade80]', 'shadow-sm'];
    const inactiveClasses = ['text-gray-400', 'hover:text-primary', 'dark:hover:text-white'];

    if (mode === 'ongoing') {
        btnOngoing.classList.add(...activeClasses);
        btnOngoing.classList.remove(...inactiveClasses);
        btnAll.classList.remove(...activeClasses);
        btnAll.classList.add(...inactiveClasses);
    } else {
        btnAll.classList.add(...activeClasses);
        btnAll.classList.remove(...inactiveClasses);
        btnOngoing.classList.remove(...activeClasses);
        btnOngoing.classList.add(...inactiveClasses);
    }
}

// Set default view
document.addEventListener('DOMContentLoaded', () => {
    filterNGOProjects('ongoing');
});
</script>
