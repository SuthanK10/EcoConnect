<div class="max-w-6xl mx-auto px-4 py-12">
  <!-- Header -->
  <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div>
      <h1 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-2 flex items-center gap-3">
        Ayu-bowan, <?php echo h($user['name'] ?? 'Volunteer'); ?> <i data-lucide="leaf" class="w-8 h-8 text-[#4ade80]"></i>
      </h1>
      <p class="text-[15px] text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed max-w-xl">
        Your contribution is making Sri Lanka cleaner every day. Track your progress and find your next impact mission.
      </p>
    </div>
    <div class="flex gap-3">
        <a href="index.php?route=user_edit_profile" class="h-12 px-6 rounded-2xl bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 flex items-center justify-center gap-2 text-sm font-black text-[#121613] dark:text-white hover:shadow-lg transition-all active:scale-95">
            <i data-lucide="settings" class="w-4 h-4"></i> Settings
        </a>
        <button onclick="requestNotifyPermission()" id="notifyBtn" class="hidden h-12 px-6 rounded-2xl bg-[#e0f2fe] dark:bg-blue-500/10 flex items-center justify-center gap-2 text-sm font-black text-[#0369a1] dark:text-blue-400 hover:shadow-lg transition-all active:scale-95">
            <i data-lucide="bell" class="w-4 h-4"></i> Enable Alerts
        </button>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <!-- Points Card -->
    <div class="relative overflow-hidden group rounded-[40px] bg-gradient-to-br from-[#2c4931] to-[#121613] p-8 shadow-2xl">
      <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
      <p class="text-[10px] font-black text-white/60 uppercase tracking-[0.2em] mb-4">Spendable Balance</p>
      <div class="flex items-end gap-2 mb-2">
        <span class="text-5xl font-black text-white leading-none"><?php echo number_format($points); ?></span>
        <span class="text-lg font-bold text-[#4ade80] mb-1">PTS</span>
      </div>
      <p class="text-xs text-white/40 font-bold">Use these to redeem rewards</p>
    </div>

    <!-- Badge Card (NOW INCLUDES RANK) -->
    <div class="rounded-[40px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 p-8 shadow-sm flex flex-col justify-between hover:shadow-xl transition-all duration-300 relative group cursor-pointer" onclick="openRankModal()">
      <div class="absolute top-6 right-8 text-gray-300 group-hover:text-primary transition-colors">
        <i data-lucide="info" class="w-5 h-5"></i>
      </div>
      
      <div>
        <p class="text-[10px] font-black text-primary dark:text-[#4ade80]/60 uppercase tracking-[0.2em] mb-4">Lifetime Progress & Rank</p>
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform duration-500 text-primary dark:text-[#4ade80]">
                <?php 
                    if($lifetimePoints >= 1000) echo '<i data-lucide="crown" class="w-10 h-10"></i>';
                    elseif($lifetimePoints >= 500) echo '<i data-lucide="medal" class="w-10 h-10"></i>';
                    elseif($lifetimePoints >= 100) echo '<i data-lucide="award" class="w-10 h-10 text-gray-400"></i>';
                    else echo '<i data-lucide="sprout" class="w-10 h-10"></i>';
                ?>
            </div>
            <div>
                <h3 class="text-xl font-black text-[#121613] dark:text-white">
                    <?php 
                        if($lifetimePoints >= 1000) echo 'Elite Guardian';
                        elseif($lifetimePoints >= 500) echo 'Silver Warrior';
                        elseif($lifetimePoints >= 100) echo 'Green Hero';
                        else echo 'Eco Warrior';
                    ?>
                </h3>
                <p class="text-xs text-[#677e6b] dark:text-gray-400 font-bold">Level <?php echo floor($lifetimePoints/100) + 1; ?></p>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100 dark:border-white/5 flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Leaderboard Rank</p>
                <p class="text-[17px] font-black text-[#121613] dark:text-white flex items-center gap-2">
                    <?php
                    if ($userRank === null) {
                      echo '#--';
                    } elseif ($userRank <= 10) {
                      echo "#{$userRank}";
                    } else {
                      echo 'Top 50';
                    }
                    ?>
                    <?php if($userRank !== null && $userRank <= 3): ?>
                        <span class="text-[8px] font-black text-yellow-500 uppercase tracking-widest flex items-center gap-1">
                            <i data-lucide="star" class="w-2.5 h-2.5 fill-current"></i> Top 10
                        </span>
                    <?php endif; ?>
                </p>
            </div>
            <span class="text-[10px] font-black text-primary dark:text-[#4ade80] uppercase tracking-widest border-b border-primary/20 flex items-center gap-1">
                View Rank <i data-lucide="chevron-right" class="w-3 h-3"></i>
            </span>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Notifications & Recommended -->
    <div class="lg:col-span-2 space-y-6">
        <?php if (!empty($categorySuggestions)): ?>
          <div class="rounded-[40px] bg-[#eff6ff] dark:bg-blue-500/5 border border-[#dbeafe] dark:border-blue-500/10 p-8 mb-6">
            <div class="flex items-center justify-between mb-8">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600">
                  <i data-lucide="sparkles" class="w-5 h-5"></i>
                </div>
                <div>
                  <h3 class="text-xl font-black text-[#121613] dark:text-white">Recommended for You</h3>
                  <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest">More <?php echo h($favoriteCategory); ?></p>
                </div>
              </div>
              <a href="index.php?route=events&category=<?php echo urlencode($favoriteCategory); ?>" class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest hover:underline flex items-center gap-1">
                View All <i data-lucide="chevron-right" class="w-3 h-3"></i>
              </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <?php foreach ($categorySuggestions as $sug): ?>
                <div class="bg-white dark:bg-darkSurface rounded-3xl p-5 shadow-sm border border-black/5 dark:border-white/5 hover:border-blue-400 transition-all group">
                  <h4 class="text-sm font-black text-[#121613] dark:text-white group-hover:text-blue-600 transition-colors line-clamp-2 mb-3"><?php echo h($sug['title']); ?></h4>
                  <div class="space-y-1 mb-4">
                    <p class="text-[10px] font-bold text-[#677e6b] dark:text-gray-400 flex items-center gap-1">
                      <i data-lucide="calendar" class="w-3 h-3"></i> <?php echo h($sug['event_date']); ?>
                    </p>
                    <p class="text-[10px] font-bold text-[#677e6b] dark:text-gray-400 flex items-center gap-1">
                      <i data-lucide="map-pin" class="w-3 h-3"></i> <?php echo h($sug['location']); ?>
                    </p>
                  </div>
                  <a href="index.php?route=event_show&id=<?php echo (int)$sug['id']; ?>" 
                     class="block w-full text-center py-2.5 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest group-hover:bg-blue-600 group-hover:text-white transition-all">
                    Details
                  </a>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <?php if (!empty($nearbyProjects)): ?>
          <div class="rounded-[40px] bg-[#f0fdf4] dark:bg-green-500/5 border border-[#dcfce7] dark:border-green-500/10 p-8">
            <div class="flex items-center justify-between mb-8">
              <div class="flex items-center gap-3">
                <div class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 dark:bg-red-500/50 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </div>
                <h3 class="text-xl font-black text-[#121613] dark:text-white">Nearby Drives</h3>
              </div>
              <span class="px-4 py-1.5 rounded-full bg-white dark:bg-white/5 text-[10px] font-extrabold text-primary dark:text-[#4ade80] border border-[#dcfce7] dark:border-white/5">Within 5km</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <?php foreach ($nearbyProjects as $proj): ?>
                <div class="bg-white dark:bg-darkSurface rounded-3xl p-6 shadow-sm border border-black/5 dark:border-white/5 hover:border-[#4ade80] transition-all group">
                  <div class="mb-4">
                    <p class="text-[10px] font-black text-[#677e6b] dark:text-gray-500 uppercase tracking-widest mb-1">Local Event</p>
                    <h4 class="text-[17px] font-black text-[#121613] dark:text-white group-hover:text-primary dark:group-hover:text-[#4ade80] transition-colors"><?php echo h($proj['title']); ?></h4>
                  </div>
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-primary"></i>
                        <span class="text-xs font-bold text-[#677e6b] dark:text-gray-400"><?php echo round($proj['distance'], 1); ?> km away</span>
                    </div>
                    <a href="index.php?route=event_show&id=<?php echo (int)$proj['id']; ?>" 
                       class="text-xs font-black text-primary dark:text-primary uppercase tracking-widest bg-[#f0f5f1] dark:bg-[#4ade80] px-4 py-2 rounded-xl hover:scale-105 transition-all flex items-center gap-1">
                      Join <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php else: ?>
           <div class="rounded-[40px] bg-white dark:bg-darkSurface border border-dashed border-gray-200 dark:border-white/10 p-12 text-center">
              <div class="text-5xl mb-4 text-gray-300 flex justify-center">
                <i data-lucide="globe" class="w-12 h-12"></i>
              </div>
              <h3 class="text-lg font-black text-[#121613] dark:text-white mb-1">Ready for an adventure?</h3>
              <p class="text-sm text-[#677e6b] dark:text-gray-400 mb-6 font-medium">No drives found in your immediate area right now. Browse all events to find one near your city.</p>
              <a href="index.php?route=events" class="inline-flex rounded-2xl bg-primary dark:bg-[#4ade80] dark:text-primary px-8 py-4 text-sm font-black text-white hover:scale-105 transition-all shadow-lg shadow-primary/20">
                Explore All Drives
              </a>
           </div>
        <?php endif; ?>
    </div>

    <!-- Quick Actions -->
    <div class="space-y-4">
        <h3 class="text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em] px-4 mb-2">Impact Actions</h3>
        
        <a href="index.php?route=messages" class="group flex items-center justify-between p-6 rounded-[32px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all duration-300">
           <div class="flex items-center gap-4">
               <div class="w-12 h-12 rounded-2xl bg-[#f0f9ff] dark:bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                 <i data-lucide="message-square" class="w-6 h-6"></i>
               </div>
               <div>
                   <p class="text-sm font-black text-[#121613] dark:text-white">Messages</p>
                   <p class="text-[10px] text-[#677e6b] dark:text-gray-400 font-bold">Contact NGOs & Admin</p>
               </div>
           </div>
           <span class="text-gray-300 dark:text-gray-600 group-hover:translate-x-1 transition-transform">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
           </span>
        </a>
        <a href="index.php?route=user_propose_cleanup" class="group flex items-center justify-between p-6 rounded-[32px] bg-primary dark:bg-[#4ade80] border border-transparent hover:shadow-xl transition-all duration-300">
           <div class="flex items-center gap-4">
               <div class="w-12 h-12 rounded-2xl bg-white/10 dark:bg-black/10 flex items-center justify-center text-white dark:text-primary group-hover:scale-110 transition-transform">
                 <i data-lucide="lightbulb" class="w-6 h-6"></i>
               </div>
               <div>
                   <p class="text-sm font-black text-white dark:text-primary">Propose Cleanup</p>
                   <p class="text-[10px] text-white/60 dark:text-primary/60 font-bold">Start a new movement</p>
               </div>
           </div>
           <span class="text-white/40 dark:text-primary/40 group-hover:translate-x-1 transition-transform">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
           </span>
        </a>

        <a href="index.php?route=user_my_proposals" class="group flex items-center justify-between p-6 rounded-[32px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all duration-300">
           <div class="flex items-center gap-4">
               <div class="w-12 h-12 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80] group-hover:scale-110 transition-transform">
                 <i data-lucide="folder" class="w-6 h-6"></i>
               </div>
               <div>
                   <p class="text-sm font-black text-[#121613] dark:text-white">My Proposals</p>
                   <p class="text-[10px] text-[#677e6b] dark:text-gray-400 font-bold">Track your suggestions</p>
               </div>
           </div>
           <span class="text-gray-300 dark:text-gray-600 group-hover:translate-x-1 transition-transform">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
           </span>
        </a>

        <a href="index.php?route=events" class="group flex items-center justify-between p-6 rounded-[32px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all duration-300">
           <div class="flex items-center gap-4">
               <div class="w-12 h-12 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80] group-hover:scale-110 transition-transform">
                 <i data-lucide="search" class="w-6 h-6"></i>
               </div>
               <div>
                   <p class="text-sm font-black text-[#121613] dark:text-white">Browse Drives</p>
                   <p class="text-[10px] text-[#677e6b] dark:text-gray-400 font-bold">Find local missions</p>
               </div>
           </div>
           <span class="text-gray-300 dark:text-gray-600 group-hover:translate-x-1 transition-transform">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
           </span>
        </a>

        <a href="index.php?route=user_my_cleanups" class="group flex items-center justify-between p-6 rounded-[32px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all duration-300">
           <div class="flex items-center gap-4">
               <div class="w-12 h-12 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80] group-hover:scale-110 transition-transform">
                 <i data-lucide="history" class="w-6 h-6"></i>
               </div>
               <div>
                   <p class="text-sm font-black text-[#121613] dark:text-white">My History</p>
                   <p class="text-[10px] text-[#677e6b] dark:text-gray-400 font-bold">See your contributions</p>
               </div>
           </div>
           <span class="text-gray-300 dark:text-gray-600 group-hover:translate-x-1 transition-transform">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
           </span>
        </a>

        <a href="index.php?route=rewards" class="group flex items-center justify-between p-6 rounded-[32px] bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/5 hover:shadow-xl transition-all duration-300">
           <div class="flex items-center gap-4">
               <div class="w-12 h-12 rounded-2xl bg-[#fef3c7] dark:bg-yellow-500/10 flex items-center justify-center text-yellow-600 group-hover:scale-110 transition-transform">
                 <i data-lucide="gift" class="w-6 h-6"></i>
               </div>
               <div>
                   <p class="text-sm font-black text-[#121613] dark:text-white">Redeem Points</p>
                   <p class="text-[10px] text-[#677e6b] dark:text-gray-400 font-bold">Get eco-rewards</p>
               </div>
           </div>
           <span class="text-gray-300 dark:text-gray-600 group-hover:translate-x-1 transition-transform">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
           </span>
        </a>
    </div>
  </div>
</div>

<!-- RANK INFORMATION MODAL -->
<div id="rankModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeRankModal()"></div>
    <div class="relative w-full max-w-lg bg-white dark:bg-darkSurface rounded-[40px] shadow-2xl overflow-hidden animate-fade-in border border-white/20">
        <div class="p-10">
            <h3 class="text-2xl font-black text-[#121613] dark:text-white mb-2">Impact Ranks</h3>
            <p class="text-sm text-[#677e6b] dark:text-gray-400 mb-8 font-medium italic">Level up by participating in more cleanup drives and helping our planet.</p>
            
            <!-- PROGRESS BAR IN MODAL -->
            <div class="mb-8 p-6 bg-gray-50 dark:bg-darkBg rounded-3xl border border-gray-100 dark:border-white/5">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-[10px] font-black text-primary dark:text-[#4ade80] uppercase tracking-widest">Progress to Level <?php echo floor($lifetimePoints/100) + 2; ?></p>
                    <p class="text-[10px] font-black text-[#677e6b]"><?php echo ($lifetimePoints % 100); ?>/100 XP</p>
                </div>
                <div class="h-2 w-full bg-white dark:bg-white/5 rounded-full overflow-hidden shadow-inner">
                    <div class="h-full bg-[#4ade80] shadow-[0_0_15px_rgba(74,222,128,0.5)]" style="width: <?php echo ($lifetimePoints % 100); ?>%"></div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center gap-4 p-4 rounded-3xl bg-gray-50 dark:bg-darkBg border border-gray-100 dark:border-white/5 transition-all hover:scale-[1.02]">
                    <div class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center shadow-sm">
                        <i data-lucide="sprout" class="w-6 h-6"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-black text-[#121613] dark:text-white uppercase tracking-tight">Eco Warrior</p>
                        <p class="text-[10px] font-bold text-[#677e6b] uppercase tracking-widest">Base Rank (0-99 XP)</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 rounded-3xl bg-[#f0fdf4] dark:bg-green-500/5 border border-[#dcfce7] dark:border-green-500/10 transition-all hover:scale-[1.02]">
                    <div class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center shadow-sm text-gray-400">
                        <i data-lucide="award" class="w-6 h-6"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-black text-[#121613] dark:text-white uppercase tracking-tight">Green Hero</p>
                        <p class="text-[10px] font-bold text-[#2c4931] uppercase tracking-widest">Veteran (100+ XP)</p>
                    </div>
                    <span class="text-[10px] font-black uppercase flex items-center gap-1 <?= $lifetimePoints >= 100 ? 'text-green-500' : 'text-[#2c4931]/40' ?>">
                        <?= $lifetimePoints >= 100 ? 'Unlocked <i data-lucide="check" class="w-3 h-3"></i>' : 'Locked' ?>
                    </span>
                </div>
                <div class="flex items-center gap-4 p-4 rounded-3xl bg-[#fefce8] dark:bg-yellow-500/5 border border-[#fef9c3] dark:border-yellow-500/10 transition-all hover:scale-[1.02]">
                    <div class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center shadow-sm text-yellow-600">
                        <i data-lucide="medal" class="w-6 h-6"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-black text-[#121613] dark:text-white uppercase tracking-tight">Silver Warrior</p>
                        <p class="text-[10px] font-bold text-[#854d0e] uppercase tracking-widest">Champion (500+ XP)</p>
                    </div>
                    <span class="text-[10px] font-black uppercase flex items-center gap-1 <?= $lifetimePoints >= 500 ? 'text-yellow-600' : 'text-[#854d0e]/40' ?>">
                        <?= $lifetimePoints >= 500 ? 'Unlocked <i data-lucide="check" class="w-3 h-3"></i>' : 'Locked' ?>
                    </span>
                </div>
                <div class="flex items-center gap-4 p-4 rounded-3xl bg-[#f5f3ff] dark:bg-purple-500/5 border border-[#ede9fe] dark:border-purple-500/10 transition-all hover:scale-[1.02]">
                    <div class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center shadow-sm text-purple-600">
                        <i data-lucide="crown" class="w-6 h-6"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-black text-[#121613] dark:text-white uppercase tracking-tight">Elite Guardian</p>
                        <p class="text-[10px] font-bold text-purple-700 uppercase tracking-widest">Legend (1000+ XP)</p>
                    </div>
                    <span class="text-[10px] font-black uppercase flex items-center gap-1 <?= $lifetimePoints >= 1000 ? 'text-purple-700' : 'text-purple-700/40' ?>">
                        <?= $lifetimePoints >= 1000 ? 'Unlocked <i data-lucide="check" class="w-3 h-3"></i>' : 'Locked' ?>
                    </span>
                </div>
            </div>

            <!-- Level up info -->
            <div class="mt-8 p-6 bg-[#f0f5f1] dark:bg-white/5 rounded-[32px] border border-[#2c4931]/10">
                <h4 class="text-sm font-black text-[#2c4931] dark:text-[#4ade80] uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i data-lucide="gem" class="w-4 h-4"></i> How to Level Up
                </h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-tight">
                        <span class="text-[#677e6b] dark:text-gray-400">Join Cleanup Drive</span>
                        <span class="text-[#2c4931] dark:text-[#4ade80]">+20 XP / +20 PTS</span>
                    </div>
                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-tight">
                        <span class="text-[#677e6b] dark:text-gray-400">Refer a Friend</span>
                        <span class="text-[#2c4931] dark:text-[#4ade80]">+10 XP / +10 PTS</span>
                    </div>
                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-tight">
                        <span class="text-[#677e6b] dark:text-gray-400">Monthly Top 10</span>
                        <span class="text-[#2c4931] dark:text-[#4ade80]">+50 XP Bonus</span>
                    </div>
                    <p class="text-[9px] text-[#677e6b] dark:text-gray-500 italic mt-2 border-t border-gray-100 dark:border-white/5 pt-2">
                        * XP determines your Rank and is cumulative. PTS (Points) are spendable in the Rewards program for eco-merchandise.
                    </p>
                </div>
            </div>

            <button onclick="closeRankModal()" class="w-full mt-8 py-4 rounded-2xl bg-[#121613] text-white text-sm font-black uppercase tracking-widest hover:bg-[#2c4931] transition-all transform active:scale-95 shadow-xl shadow-black/20">
                Got it
            </button>
        </div>
    </div>
</div>

<script>
function openRankModal() {
    const modal = document.getElementById('rankModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeRankModal() {
    const modal = document.getElementById('rankModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function requestNotifyPermission() {
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notifications.");
    return;
  }
  Notification.requestPermission().then(permission => {
    if (permission === "granted") {
      alert("Notifications enabled! You will receive alerts for upcoming events.");
      document.getElementById('notifyBtn').classList.add('hidden');
    }
  });
}
window.addEventListener('load', function() {
  const btn = document.getElementById('notifyBtn');
  if ("Notification" in window && Notification.permission !== "granted") {
    btn.classList.remove('hidden');
  }
});
</script>