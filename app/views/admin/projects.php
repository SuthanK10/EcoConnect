<?php
// app/views/admin/projects.php
?>

<div class="max-w-6xl mx-auto px-6 py-8">
  <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
      <h2 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-2">Drive Management</h2>
      <p class="text-[15px] text-[#677e6b] dark:text-gray-400 font-medium">Global overview of all cleanup projects and drives across the platform.</p>
    </div>
    <div class="flex flex-col md:flex-row items-center gap-4">
        <!-- Filter Toggle -->
        <div class="flex p-1 bg-white dark:bg-darkSurface rounded-2xl border border-gray-100 dark:border-white/10 shadow-sm">
            <button onclick="filterProjects('ongoing')" id="filter-ongoing" class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-[#2c4931] text-white dark:bg-[#4ade80] dark:text-primary">
                Ongoing
            </button>
            <button onclick="filterProjects('all')" id="filter-all" class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-gray-400 hover:text-[#121613] dark:hover:text-white">
                All
            </button>
        </div>

        <div class="px-6 py-3 rounded-2xl bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/10 shadow-sm flex items-center gap-3">
           <span class="w-3 h-3 rounded-full bg-[#4ade80] animate-pulse"></span>
           <span class="text-sm font-black text-[#121613] dark:text-white"><span id="project-count"><?php echo count($projects); ?></span> Registered Drives</span>
        </div>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-6">
    <?php if (empty($projects)): ?>
        <div class="rounded-[40px] border border-dashed border-gray-200 dark:border-white/10 bg-white dark:bg-darkSurface p-16 text-center">
            <div class="text-5xl mb-4 flex justify-center text-gray-300">
                <i data-lucide="leaf" class="w-16 h-16"></i>
            </div>
            <p class="text-sm font-bold text-gray-400 italic">No drives have been created yet.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($projects as $project): ?>
      <?php 
        $isExpired = strtotime($project['event_date']) < strtotime(date('Y-m-d'));
        $displayStatus = $project['status'];
        if ($isExpired && $displayStatus === 'open') {
            $displayStatus = 'expired';
        }
      ?>
      <div class="project-card rounded-[40px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 p-8 shadow-sm hover:shadow-xl transition-all duration-300 group" 
           data-expired="<?php echo $isExpired ? 'true' : 'false'; ?>">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
          
          <!-- Drive Basic Info -->
          <div class="flex-1">
            <div class="flex items-center gap-3 mb-4">
              <span class="px-3 py-1 rounded-full <?php echo $displayStatus === 'expired' ? 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400' : 'bg-[#f0f5f1] dark:bg-white/5 text-[#2c4931] dark:text-[#4ade80]'; ?> text-[10px] font-black uppercase tracking-widest">
                <?php echo h($displayStatus); ?>
              </span>
              <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Added by <?php echo h($project['ngo_name']); ?></span>
            </div>
            
            <h3 class="text-2xl font-black text-[#121613] dark:text-white mb-4 group-hover:text-primary dark:group-hover:text-[#4ade80] transition-colors">
                <?php echo h($project['title']); ?>
            </h3>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#ebefec] dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80]">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                </div>
                <div>
                  <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Location</p>
                  <p class="text-xs font-bold text-[#121613] dark:text-white"><?php echo h($project['location']); ?></p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#ebefec] dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80]">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <div>
                  <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Event Date</p>
                  <p class="text-xs font-bold text-[#121613] dark:text-white"><?php echo date('M d, Y', strtotime($project['event_date'])); ?></p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#fef9c3] dark:bg-yellow-500/10 flex items-center justify-center text-[#854d0e] dark:text-yellow-500">
                    <i data-lucide="gem" class="w-5 h-5"></i>
                </div>
                <div>
                  <p class="text-[9px] font-black text-[#854d0e] dark:text-yellow-500 uppercase tracking-widest mb-0.5">Reward</p>
                  <p class="text-xs font-bold text-[#121613] dark:text-white"><?php echo (int)$project['points_reward']; ?> Points</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Controls -->
          <div class="w-full lg:w-64 flex flex-col gap-2">
            <form method="post" class="grid grid-cols-1 gap-2">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="project_id" value="<?php echo (int)$project['id']; ?>" />
              
              <?php if (!$isExpired): ?>
              <div class="grid grid-cols-2 gap-2">
                <button name="status" value="open" 
                      class="flex items-center justify-center py-3 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 text-[#121613] dark:text-white text-xs font-black uppercase tracking-widest hover:bg-[#2c4931] hover:text-white dark:hover:bg-[#4ade80] dark:hover:text-primary transition-all <?php echo $project['status'] === 'open' ? 'ring-2 ring-primary dark:ring-[#4ade80] ring-offset-2 dark:ring-offset-darkBg' : ''; ?>">
                  Open
                </button>
                <button name="status" value="closed" 
                      class="flex items-center justify-center py-3 rounded-2xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 text-[#121613] dark:text-white text-xs font-black uppercase tracking-widest hover:bg-yellow-500 hover:text-white transition-all <?php echo $project['status'] === 'closed' ? 'ring-2 ring-yellow-500 ring-offset-2 dark:ring-offset-darkBg' : ''; ?>">
                  Close
                </button>
              </div>
              <?php endif; ?>

              <button name="delete" value="1" 
                    onclick="return confirm('Remove this project? This will also remove all associated volunteer applications.');"
                    class="w-full py-4 rounded-2xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-xs font-black hover:bg-red-600 hover:text-white transition-all uppercase tracking-widest border border-red-100 dark:border-red-500/20">
                Delete Project Drive
              </button>
              
              <a href="index.php?route=admin_project_volunteers&id=<?php echo (int)$project['id']; ?>"
                 class="w-full py-4 rounded-2xl bg-[#ebefec] dark:bg-[#4ade80]/10 text-primary dark:text-[#4ade80] text-xs font-black text-center uppercase tracking-widest hover:bg-[#2c4931] hover:text-white dark:hover:bg-[#4ade80] dark:hover:text-primary transition-all border border-gray-100 dark:border-white/10">
                View Volunteers
              </a>

              <a href="index.php?route=event_show&id=<?php echo (int)$project['id']; ?>" target="_blank"
                 class="w-full py-3 rounded-2xl bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 text-[10px] font-black text-center uppercase tracking-widest hover:bg-gray-100 dark:hover:bg-white/10 transition-all border border-transparent dark:border-white/5 flex items-center justify-center gap-2">
                Preview Public Page <i data-lucide="external-link" class="w-3 h-3"></i>
              </a>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
function filterProjects(mode) {
    const cards = document.querySelectorAll('.project-card');
    const btnOngoing = document.getElementById('filter-ongoing');
    const btnAll = document.getElementById('filter-all');
    let count = 0;

    // Toggle button styles
    if (mode === 'ongoing') {
        btnOngoing.classList.add('bg-[#2c4931]', 'text-white', 'dark:bg-[#4ade80]', 'dark:text-primary');
        btnOngoing.classList.remove('text-gray-400');
        btnAll.classList.remove('bg-[#2c4931]', 'text-white', 'dark:bg-[#4ade80]', 'dark:text-primary');
        btnAll.classList.add('text-gray-400');
    } else {
        btnAll.classList.add('bg-[#2c4931]', 'text-white', 'dark:bg-[#4ade80]', 'dark:text-primary');
        btnAll.classList.remove('text-gray-400');
        btnOngoing.classList.remove('bg-[#2c4931]', 'text-white', 'dark:bg-[#4ade80]', 'dark:text-primary');
        btnOngoing.classList.add('text-gray-400');
    }

    cards.forEach(card => {
        const isExpired = card.getAttribute('data-expired') === 'true';
        if (mode === 'ongoing' && isExpired) {
            card.style.display = 'none';
        } else {
            card.style.display = 'block';
            count++;
        }
    });

    document.getElementById('project-count').textContent = count;
}

// Ensure "Ongoing" is the default view on load
document.addEventListener('DOMContentLoaded', () => {
    filterProjects('ongoing');
});
</script>
