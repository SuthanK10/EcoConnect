<?php
// app/views/admin/project_volunteers.php
?>

<div class="max-w-6xl mx-auto px-6 py-8">
  <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
      <div class="flex items-center gap-3 mb-2">
        <a href="index.php?route=admin_projects" class="text-xs font-black uppercase tracking-widest text-[#677e6b] hover:text-primary transition dark:text-gray-400 dark:hover:text-[#4ade80] flex items-center gap-1.5">
          <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i> Back to Projects
        </a>
      </div>
      <h2 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-2">Project Volunteers</h2>
      <p class="text-[15px] text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed italic">
        Viewing participants for <span class="text-primary dark:text-[#4ade80] font-black not-italic">"<?php echo h($project['title']); ?>"</span>
      </p>
    </div>
    
    <div class="px-6 py-3 rounded-2xl bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/10 shadow-sm flex items-center gap-3">
       <span class="w-3 h-3 rounded-full bg-primary dark:bg-[#4ade80]"></span>
       <span class="text-sm font-black text-[#121613] dark:text-white"><?php echo count($volunteers); ?> Registered Volunteers</span>
    </div>
  </div>

  <div class="bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden text-left">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 dark:bg-white/5 border-b border-gray-100 dark:border-white/10 text-left">
          <tr>
            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Name & Contact</th>
            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Location</th>
            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Status</th>
            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Applied On</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50 dark:divide-white/5 text-left">
          <?php if (empty($volunteers)): ?>
            <tr>
              <td colspan="4" class="px-8 py-20 text-center">
                <div class="text-4xl mb-4 flex justify-center text-gray-300">
                  <i data-lucide="users" class="w-16 h-16"></i>
                </div>
                <p class="text-sm font-bold text-gray-400 italic">No volunteers have signed up for this drive yet.</p>
              </td>
            </tr>
          <?php endif; ?>
          
          <?php foreach ($volunteers as $v): ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
              <td class="px-8 py-6">
                <a href="index.php?route=admin_user_view&id=<?php echo (int)$v['user_id']; ?>" class="block group-hover:translate-x-1 transition-transform">
                  <p class="text-sm font-black text-[#121613] dark:text-white mb-1 uppercase tracking-tight"><?php echo h($v['name']); ?></p>
                  <p class="text-xs font-bold text-[#677e6b] dark:text-gray-500"><?php echo h($v['email']); ?></p>
                </a>
              </td>
              <td class="px-8 py-6">
                <p class="text-xs font-bold text-[#121613] dark:text-white uppercase tracking-tighter italic">
                  <?php echo h($v['city'] ?: 'Not Specified'); ?>
                </p>
              </td>
              <td class="px-8 py-6">
                <?php
                  $statusClasses = [
                      'applied' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400',
                      'accepted' => 'bg-[#f0f5f1] dark:bg-[#4ade80]/10 text-primary dark:text-[#4ade80]',
                      'attended' => 'bg-[#f0f5f1] dark:bg-[#4ade80]/10 text-primary dark:text-[#4ade80]',
                      'completed' => 'bg-[#f0f5f1] dark:bg-[#4ade80]/10 text-primary dark:text-[#4ade80]',
                      'cancelled' => 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400'
                  ];
                  $cls = $statusClasses[$v['status']] ?? 'bg-gray-100 text-gray-600';
                ?>
                <span class="px-3 py-1 rounded-full <?php echo $cls; ?> text-[10px] font-black uppercase tracking-widest">
                  <?php echo h($v['status']); ?>
                </span>
              </td>
              <td class="px-8 py-6 text-right">
                <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 italic uppercase">
                  <?php echo date('M d, Y', strtotime($v['applied_at'])); ?>
                </p>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
