<div class="max-w-7xl mx-auto px-4 py-12">
  <!-- Header -->
  <div class="mb-12">
    <h1 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-2">Central Command</h1>
    <p class="text-[15px] text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed">
      Monitor system vitals, manage global partnerships, and oversee the Eco-Connect movement.
    </p>
  </div>

  <!-- STATS GRID -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
    <!-- Total Users -->
    <div class="group bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 p-8 shadow-sm hover:shadow-xl transition-all duration-300">
      <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
        <i data-lucide="users" class="w-6 h-6"></i>
      </div>
      <p class="text-[10px] font-black text-[#677e6b] dark:text-gray-400 uppercase tracking-[0.2em] mb-1">Total Volunteers</p>
      <h3 class="text-3xl font-black text-[#121613] dark:text-white"><?php echo number_format($totalUsers); ?></h3>
    </div>

    <!-- Active NGOs -->
    <div class="group bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 p-8 shadow-sm hover:shadow-xl transition-all duration-300">
      <div class="w-12 h-12 rounded-2xl bg-green-50 dark:bg-green-500/10 flex items-center justify-center text-green-600 mb-6 group-hover:scale-110 transition-transform">
        <i data-lucide="building-2" class="w-6 h-6"></i>
      </div>
      <p class="text-[10px] font-black text-[#677e6b] dark:text-gray-400 uppercase tracking-[0.2em] mb-1">Partner NGOs</p>
      <h3 class="text-3xl font-black text-[#121613] dark:text-white"><?php echo number_format($totalNgos); ?></h3>
    </div>

    <!-- Live Projects -->
    <div class="group bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 p-8 shadow-sm hover:shadow-xl transition-all duration-300">
      <div class="w-12 h-12 rounded-2xl bg-orange-50 dark:bg-orange-500/10 flex items-center justify-center text-orange-600 mb-6 group-hover:scale-110 transition-transform">
        <i data-lucide="leaf" class="w-6 h-6"></i>
      </div>
      <p class="text-[10px] font-black text-[#677e6b] dark:text-gray-400 uppercase tracking-[0.2em] mb-1">Cleanup Missions</p>
      <h3 class="text-3xl font-black text-[#121613] dark:text-white"><?php echo number_format($totalProjects); ?></h3>
    </div>

    <!-- Total Impact -->
    <div class="group bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 p-8 shadow-sm hover:shadow-xl transition-all duration-300">
      <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
        <i data-lucide="sparkles" class="w-6 h-6"></i>
      </div>
      <p class="text-[10px] font-black text-[#677e6b] dark:text-gray-400 uppercase tracking-[0.2em] mb-1">Impact Requests</p>
      <h3 class="text-3xl font-black text-[#121613] dark:text-white"><?php echo number_format($totalApplications); ?></h3>
    </div>

    <!-- Inactive Volunteers -->
    <div class="group bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 p-8 shadow-sm border-l-4 border-l-red-500 hover:shadow-xl transition-all duration-300">
      <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center text-red-600 mb-6 group-hover:scale-110 transition-transform">
        <i data-lucide="trending-down" class="w-6 h-6"></i>
      </div>
      <p class="text-[10px] font-black text-[#677e6b] dark:text-gray-400 uppercase tracking-[0.2em] mb-1">Inactive Volunteers</p>
      <h3 class="text-3xl font-black text-[#121613] dark:text-white"><?php echo number_format($inactiveVolunteers); ?></h3>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Management Section -->
    <div class="lg:col-span-2 space-y-6">
      <h3 class="text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em] px-4">System Governance</h3>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Manage NGOs -->
        <a href="index.php?route=admin_ngos" class="group p-8 rounded-[32px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all duration-300 border-l-4 border-l-[#2c4931]">
           <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-[#2c4931] dark:text-[#4ade80] group-hover:scale-110 transition-transform">
                 <i data-lucide="handshake" class="w-7 h-7"></i>
               </div>
               <i data-lucide="chevron-right" class="w-5 h-5 text-gray-200 group-hover:text-[#2c4931] transition-colors"></i>
           </div>
           <h4 class="text-lg font-black text-[#121613] dark:text-white mb-1">Manage NGOs</h4>
           <p class="text-xs text-[#677e6b] dark:text-gray-400 font-medium">Verify partners and approve new organizations</p>
        </a>

        <!-- Manage Projects -->
        <a href="index.php?route=admin_projects" class="group p-8 rounded-[32px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all duration-300 border-l-4 border-l-orange-200">
           <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 rounded-2xl bg-orange-50 dark:bg-orange-500/10 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                 <i data-lucide="calendar" class="w-7 h-7"></i>
               </div>
               <i data-lucide="chevron-right" class="w-5 h-5 text-gray-200 group-hover:text-orange-400 transition-colors"></i>
           </div>
           <h4 class="text-lg font-black text-[#121613] dark:text-white mb-1">Manage Projects</h4>
           <p class="text-xs text-[#677e6b] dark:text-gray-400 font-medium">Oversee global cleanup missions and status</p>
        </a>

        <!-- Community Proposals -->
        <a href="index.php?route=admin_proposals" class="group p-8 rounded-[32px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all duration-300 border-l-4 border-l-yellow-400 relative">
           <span class="absolute top-6 right-8 px-2 py-0.5 rounded-full bg-yellow-400 text-[8px] font-black text-white uppercase tracking-tighter">New</span>
           <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 rounded-2xl bg-yellow-50 dark:bg-yellow-500/10 flex items-center justify-center text-yellow-600 group-hover:scale-110 transition-transform">
                 <i data-lucide="lightbulb" class="w-7 h-7"></i>
               </div>
               <i data-lucide="chevron-right" class="w-5 h-5 text-gray-200 group-hover:text-yellow-500 transition-colors"></i>
           </div>
           <h4 class="text-lg font-black text-[#121613] dark:text-white mb-1">Community Proposals</h4>
           <p class="text-xs text-[#677e6b] dark:text-gray-400 font-medium">Review volunteer suggestions for drives</p>
        </a>

        <!-- Manage Users -->
        <a href="index.php?route=admin_users" class="group p-8 rounded-[32px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all duration-300 border-l-4 border-l-blue-200">
           <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                 <i data-lucide="user" class="w-7 h-7"></i>
               </div>
               <i data-lucide="chevron-right" class="w-5 h-5 text-gray-200 group-hover:text-blue-400 transition-colors"></i>
           </div>
           <h4 class="text-lg font-black text-[#121613] dark:text-white mb-1">Manage Users</h4>
           <p class="text-xs text-[#677e6b] dark:text-gray-400 font-medium">Review volunteer accounts and rewards</p>
        </a>

        <!-- Reactivation Appeals -->
        <a href="index.php?route=admin_appeals" class="group p-8 rounded-[32px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all duration-300 border-l-4 border-l-purple-200 relative overflow-hidden">
           <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500/5 -mr-10 -mt-10 rounded-full"></div>
           <div class="flex items-center justify-between mb-4">
               <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                 <i data-lucide="mail" class="w-7 h-7"></i>
               </div>
               <i data-lucide="chevron-right" class="w-5 h-5 text-gray-200 group-hover:text-purple-400 transition-colors"></i>
           </div>
           <h4 class="text-lg font-black text-[#121613] dark:text-white mb-1">Reactivation Appeals</h4>
           <p class="text-xs text-[#677e6b] dark:text-gray-400 font-medium">Review requests from deactivated NGOs</p>
        </a>
         <!-- Messages / NGO Requests -->
         <a href="index.php?route=messages" class="group p-8 rounded-[32px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all duration-300 border-l-4 border-l-green-400 relative">
            <div class="flex items-center justify-between mb-4">
                 <div class="w-14 h-14 rounded-2xl bg-green-50 dark:bg-green-500/10 flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                   <i data-lucide="message-square" class="w-7 h-7"></i>
                 </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-200 group-hover:text-green-500 transition-colors"></i>
            </div>
            <h4 class="text-lg font-black text-[#121613] dark:text-white mb-1">Messages / NGO Requests</h4>
            <p class="text-xs text-[#677e6b] dark:text-gray-400 font-medium">Communicate with NGOs and volunteers</p>
         </a>

         <!-- Content Moderation -->
         <a href="index.php?route=admin_post_moderation" class="group p-8 rounded-[32px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all duration-300 border-l-4 border-l-red-400 relative">
            <div class="flex items-center justify-between mb-4">
                 <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform">
                   <i data-lucide="shield-alert" class="w-7 h-7"></i>
                 </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-200 group-hover:text-red-500 transition-colors"></i>
            </div>
            <h4 class="text-lg font-black text-[#121613] dark:text-white mb-1">Content Moderation</h4>
            <p class="text-xs text-[#677e6b] dark:text-gray-400 font-medium">Approve or reject community feed posts</p>
         </a>
      </div>
    </div>

    <!-- Global Actions Section -->
    <div class="space-y-6">
      <h3 class="text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em] px-4">Global Operations</h3>
      
      <div class="bg-[#121613] rounded-[40px] p-8 shadow-2xl relative overflow-hidden group">
         <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
         <h4 class="text-xl font-black text-white mb-4">Monthly Finalization</h4>
         <p class="text-sm text-white/60 mb-8 leading-relaxed font-medium">Calculate rankings and distribute bonus points to the top warriors of the past month.</p>
         <form action="index.php?route=admin_finalize_rankings" method="post">
            <?php echo csrf_field(); ?>
            <button type="submit" 
               onclick="return confirm('Finalize the latest monthly rankings? This will distribute bonus points to the top warriors. This cannot be undone.')"
               class="block w-full py-4 rounded-2xl bg-[#4ade80] text-[#121613] text-sm font-black text-center shadow-lg hover:bg-white transition-all active:scale-95">
               Finalize Rankings Now
            </button>
         </form>
      </div>

      <div class="bg-red-50 border border-red-100 rounded-[40px] p-8">
         <h4 class="text-xl font-black text-red-900 mb-2">Security Audit</h4>
         <p class="text-sm text-red-700/70 mb-8 font-medium italic">Deactivates NGOs with no portal activity for over 90 days.</p>
         <form action="index.php?route=admin_run_ngo_maintenance" method="post">
            <?php echo csrf_field(); ?>
            <button type="submit"
               onclick="return confirm('Scan for and deactivate NGOs inactive for over 90 days?')"
               class="block w-full py-4 rounded-2xl bg-red-600 text-white text-sm font-black text-center shadow-lg hover:bg-red-700 transition-all active:scale-95">
               Run Inactivity Cleanup
            </button>
         </form>
      </div>

      <div class="bg-blue-50 border border-blue-100 rounded-[40px] p-8">
         <h4 class="text-xl font-black text-blue-900 mb-2">Community Pulse</h4>
         <p class="text-sm text-blue-700/70 mb-8 font-medium italic">Sends re-engagement emails to volunteers inactive for over 30 days.</p>
         <form action="index.php?route=admin_run_user_reengagement" method="post">
            <?php echo csrf_field(); ?>
            <button type="submit"
               onclick="return confirm('Send re-engagement emails to inactive volunteers? This will suggest upcoming drives.')"
               class="block w-full py-4 rounded-2xl bg-blue-600 text-white text-sm font-black text-center shadow-lg hover:bg-blue-700 transition-all active:scale-95">
               Reach Out to Inactive Users
            </button>
         </form>
      </div>

       <a href="index.php?route=leaderboard" class="flex items-center justify-center gap-3 w-full py-4 rounded-3xl bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 text-[#121613] dark:text-white font-black text-sm hover:shadow-lg transition-all">
          View Live Leaderboard <i data-lucide="trophy" class="w-4 h-4"></i>
       </a>
    </div>
  </div>
</div>
