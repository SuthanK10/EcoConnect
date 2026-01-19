<?php
// app/views/home/index.php

// Safely get current role (if helper exists)
$role = function_exists('current_user_role')
    ? current_user_role()
    : ($_SESSION['role'] ?? null);

// Check login status from session
$loggedIn = !empty($_SESSION['user_id'] ?? null);
?>

<div class="max-w-7xl mx-auto px-6 md:px-10 py-12">
  <!-- HERO SECTION -->
  <div class="mb-12">
    <div
      class="flex min-h-[480px] flex-col gap-6 bg-cover bg-center bg-no-repeat rounded-3xl items-center justify-center p-8 shadow-2xl relative overflow-hidden"
      style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('assets/hero1.webp');"
    >
      <div class="flex flex-col gap-4 text-center max-w-3xl relative z-10">
        <h1 class="text-white text-5xl md:text-6xl font-black leading-tight tracking-tight">
          Join the Movement for a Cleaner Sri Lanka
        </h1>
        <p class="text-white text-lg md:text-xl leading-relaxed opacity-90">
          Eco-Connect brings together NGOs, students, and everyday people to organize and join cleanup drives across Sri Lanka.
        </p>
      </div>

      <!-- Role-based buttons -->
      <div class="flex flex-wrap gap-4 justify-center mt-6 relative z-10">
        <?php if (!$loggedIn): ?>
          <a href="index.php?route=login" class="px-8 py-3 bg-[#2c4931] text-white rounded-xl font-bold hover:bg-[#121613] transition-all transform hover:scale-105 shadow-lg">
            Join a Cleanup
          </a>
        <?php elseif ($role === 'user'): ?>
          <a href="index.php?route=events" class="px-8 py-3 bg-[#2c4931] text-white rounded-xl font-bold hover:bg-[#121613] transition-all transform hover:scale-105 shadow-lg">
            Join a Cleanup
          </a>
        <?php elseif ($role === 'ngo'): ?>
          <a href="index.php?route=ngo_project_new" class="px-8 py-3 bg-white text-[#2c4931] rounded-xl font-bold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">
            Create Event
          </a>
        <?php endif; ?>
        
        <a href="index.php?route=explore_drives" class="px-8 py-3 bg-white/20 backdrop-blur-md text-white border border-white/30 rounded-xl font-bold hover:bg-white/30 transition-all shadow-lg">
          Explore Map
        </a>
      </div>
    </div>
  </div>

  <!-- MISSION SECTION -->
  <div class="mb-20">
    <div class="flex flex-col md:flex-row gap-12 items-center">
      <div class="flex-1">
        <h2 class="text-[#121613] dark:text-white text-3xl font-bold mb-6">Our Mission</h2>
        <p class="text-[#677e6b] dark:text-gray-400 text-lg leading-relaxed font-medium">
          Our mission is to protect and restore the natural beauty of Sri Lanka by connecting passionate volunteers with impactful cleanup projects organized by dedicated NGOs. We believe in the power of community action to create a cleaner, greener future.
        </p>
      </div>
      <div class="flex-1 grid grid-cols-2 gap-4">
        <div class="bg-gray-50 dark:bg-darkSurface p-8 rounded-2xl">
          <p class="text-[#2c4931] dark:text-[#4ade80] text-3xl font-bold mb-1">7000+</p>
          <p class="text-sm text-[#677e6b] dark:text-gray-500 font-bold">Tons of waste produced daily in SL</p>
        </div>
        <div class="bg-gray-50 dark:bg-darkSurface p-8 rounded-2xl">
          <p class="text-[#2c4931] dark:text-[#4ade80] text-3xl font-bold mb-1">30%</p>
          <p class="text-sm text-[#677e6b] dark:text-gray-500 font-bold">Of waste currently managed properly</p>
        </div>
      </div>
    </div>
  </div>

  <!-- FEATURED EVENTS -->
  <div class="mb-20">
    <div class="flex items-center justify-between mb-10">
      <h2 class="text-[#121613] dark:text-white text-3xl font-bold tracking-tight">Featured Cleanup Events</h2>
      <a href="index.php?route=events" class="text-primary dark:text-[#4ade80] font-black uppercase tracking-widest text-xs hover:underline flex items-center gap-1.5">
        View All <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php if (!empty($featured)): ?>
        <?php foreach ($featured as $project): ?>
          <a href="index.php?route=event_show&id=<?php echo (int)$project['id']; ?>" class="group">
            <div class="bg-white dark:bg-darkSurface rounded-3xl overflow-hidden border border-gray-100 dark:border-white/5 hover:shadow-2xl transition-all h-full flex flex-col">
              <div class="h-56 overflow-hidden relative">
                <img 
                  src="<?php echo h($project['image_path'] ?? 'assets/default-event.jpg'); ?>" 
                  alt="<?php echo h($project['title']); ?>"
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                >
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
              </div>
              <div class="p-8 flex-1 flex flex-col">
                <h3 class="text-xl font-bold text-[#121613] dark:text-white mb-4 group-hover:text-primary dark:group-hover:text-[#4ade80] transition-colors leading-tight">
                  <?php echo h($project['title']); ?>
                </h3>
                <div class="flex flex-col gap-2 text-[#677e6b] dark:text-gray-400 text-sm font-bold mt-auto">
                  <span class="flex items-center gap-2"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> <?php echo h($project['location']); ?></span>
                  <span class="flex items-center gap-2"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> <?php echo date('M d, Y', strtotime($project['event_date'])); ?></span>
                </div>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="col-span-full text-center text-[#677e6b] py-20 font-bold bg-white dark:bg-darkSurface rounded-3xl border border-dashed border-gray-200 dark:border-white/10">No featured events at the moment. Check back later!</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- IMPACT CARDS -->
  <div class="mb-24">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="bg-white dark:bg-darkSurface p-10 rounded-3xl border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-xl transition-all">
        <div class="text-4xl mb-6 text-primary dark:text-[#4ade80]"><i data-lucide="globe" class="w-10 h-10"></i></div>
        <h3 class="text-xl font-bold text-[#121613] dark:text-white mb-4">Our Impact</h3>
        <p class="text-sm text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed">Join a community dedicated to making a real difference in Sri Lanka's environment.</p>
      </div>
      <div class="bg-white dark:bg-darkSurface p-10 rounded-3xl border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-xl transition-all">
        <div class="text-4xl mb-6 text-primary dark:text-[#4ade80]"><i data-lucide="building-2" class="w-10 h-10"></i></div>
        <h3 class="text-xl font-bold text-[#121613] dark:text-white mb-4">NGO Partners</h3>
        <p class="text-sm text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed">Verified NGOs organizing and leading cleanups across the island.</p>
      </div>
      <div class="bg-white dark:bg-darkSurface p-10 rounded-3xl border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-xl transition-all">
        <div class="text-4xl mb-6 text-primary dark:text-[#4ade80]"><i data-lucide="award" class="w-10 h-10"></i></div>
        <h3 class="text-xl font-bold text-[#121613] dark:text-white mb-4">Earn Rewards</h3>
        <p class="text-sm text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed">Get points for participation and redeem them for exclusive rewards.</p>
      </div>
      <div class="bg-[#f0f9ff] dark:bg-blue-500/5 p-10 rounded-3xl border border-blue-100 dark:border-blue-500/10 shadow-sm hover:shadow-xl transition-all">
        <div class="text-4xl mb-6 text-blue-600"><i data-lucide="camera" class="w-10 h-10"></i></div>
        <h3 class="text-xl font-bold text-[#121613] dark:text-white mb-4">Eco-Action Feed</h3>
        <p class="text-sm text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed">Share your journey! Post photos from your drives and inspire the nation.</p>
        <a href="index.php?route=gallery" class="inline-block mt-4 text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest hover:underline flex items-center gap-1.5">
          View Feed <i data-lucide="chevron-right" class="w-3 h-3"></i>
        </a>
      </div>
    </div>
  </div>

  <!-- COMMUNITY FEED CTA SECTION -->
  <div class="mb-24 px-2 md:px-0">
    <div class="bg-gradient-to-br from-blue-700 via-blue-900 to-slate-900 rounded-[40px] p-10 md:p-20 text-white relative overflow-hidden shadow-2xl border border-white/10 group">
      <!-- Animated Background Layers -->
      <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-[100px] -mr-64 -mt-64 group-hover:scale-110 transition-transform duration-1000"></div>
      <div class="absolute bottom-0 left-0 w-80 h-80 bg-cyan-500/10 rounded-full blur-[80px] -ml-40 -mb-40 group-hover:scale-125 transition-transform duration-1000"></div>

      <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <!-- Text Content -->
        <div class="text-center lg:text-left order-2 lg:order-1">
          <div class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-white/10 border border-white/20 mb-8 backdrop-blur-md">
             <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
             </span>
             <span class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-100">Live Impact Stream</span>
          </div>
          
          <h2 class="text-4xl md:text-6xl font-black mb-8 leading-[1.1] tracking-tight">
            See the Change <br/>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-400">As It Happens.</span>
          </h2>
          
          <p class="text-lg text-white/70 leading-relaxed max-w-xl mb-12 font-medium">
            Eco-Connect is more than a platform—it's a movement. Dive into our live feed to see volunteers across Sri Lanka sharing real progress, real photos, and real impact.
          </p>

          <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
             <a href="index.php?route=gallery" class="group flex items-center gap-3 h-16 px-10 rounded-2xl bg-[#4ade80] text-[#121613] font-black uppercase tracking-widest text-xs hover:bg-white transition-all shadow-xl shadow-[#4ade80]/20 active:scale-95">
               Explore the Feed <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
             </a>
             <p class="text-[10px] text-white/40 font-bold uppercase tracking-widest italic hidden sm:flex items-center gap-1.5">
                Moderated community verified <i data-lucide="check-circle" class="w-3 h-3"></i>
             </p>
          </div>
        </div>

        <!-- Visual Showcase (Mock Feed) -->
        <div class="order-1 lg:order-2 flex justify-center">
            <div class="relative w-full max-w-[400px]">
               <!-- Post 1 (Top) -->
               <?php 
               $post1 = $recentPosts[0] ?? null;
               $img1 = $post1 ? h($post1['media_path']) : 'assets/hero1.webp';
               $name1 = $post1 ? ($post1['user_name'] ?? $post1['ngo_name'] ?? 'Volunteer') : 'Eco-Warrior';
               $initial1 = strtoupper(substr($name1, 0, 1));
               ?>
               <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-5 rounded-[32px] shadow-2xl transform rotate-3 relative z-20 group-hover:rotate-0 transition-transform duration-700">
                  <div class="aspect-square rounded-2xl overflow-hidden mb-4 bg-slate-800">
                     <img src="<?php echo $img1; ?>" class="w-full h-full object-cover opacity-90" alt="Action">
                  </div>
                  <div class="flex items-center gap-3">
                     <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-[10px] font-black"><?php echo $initial1; ?></div>
                     <div>
                        <div class="h-2 w-24 bg-white/20 rounded-full mb-1"></div>
                        <div class="h-1.5 w-16 bg-white/10 rounded-full"></div>
                     </div>
                  </div>
               </div>

               <!-- Post 2 (Bottom) -->
               <?php 
               $post2 = $recentPosts[1] ?? null;
               if ($post2 || !$post1): // Show if we have a second post, or if we have none (to show fallbacks)
                  $img2 = $post2 ? h($post2['media_path']) : 'assets/hero.jpg';
               ?>
               <div class="absolute -bottom-10 -left-10 w-2/3 bg-white/10 backdrop-blur-xl border border-white/20 p-4 rounded-[28px] shadow-2xl -rotate-6 z-10 hidden md:block group-hover:rotate-0 transition-transform duration-700">
                  <div class="aspect-[4/3] rounded-xl overflow-hidden mb-3 bg-slate-800">
                     <img src="<?php echo $img2; ?>" class="w-full h-full object-cover opacity-80" alt="Action 2">
                  </div>
                  <div class="h-1.5 w-full bg-white/10 rounded-full"></div>
               </div>
               <?php endif; ?>
            </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ECO-AWARENESS TIPS -->
  <div class="mb-24">
    <div class="bg-gradient-to-br from-[#121613] to-[#2c4931] rounded-3xl p-10 md:p-16 text-white relative overflow-hidden shadow-2xl">
      <!-- Background Decor -->
      <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 -mr-48 -mt-48 rounded-full blur-3xl"></div>
      <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 -ml-32 -mb-32 rounded-full blur-2xl"></div>

      <div class="relative z-10 flex flex-col lg:flex-row gap-12 items-center">
        <div class="lg:w-1/2">
          <h2 class="text-4xl font-black mb-6 leading-tight tracking-tight flex items-center gap-3">
            Eco-Warrior Tips <i data-lucide="leaf" class="w-8 h-8 text-[#4ade80]"></i>
          </h2>
          <p class="text-lg text-white/80 leading-relaxed mb-8 font-medium">
            Cleaning up is vital, but reducing pollution starts with conscious daily choices. Learn how you can make a difference beyond the cleanup drive.
          </p>
          <a href="index.php?route=events" class="inline-flex h-14 px-8 items-center justify-center rounded-2xl bg-[#4ade80] text-[#121613] font-black uppercase tracking-widest text-sm hover:bg-white transition-all shadow-lg shadow-[#4ade80]/20 gap-2">
            Join the Movement <i data-lucide="chevron-right" class="w-4 h-4"></i>
          </a>
        </div>

        <div class="lg:w-1/2 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:bg-white/20 transition-all group">
            <div class="text-2xl mb-4 group-hover:scale-110 transition-transform text-[#4ade80]">
                <i data-lucide="ban"></i>
            </div>
            <h4 class="font-bold text-lg mb-2 text-[#4ade80]">Refuse Single-Use</h4>
            <p class="text-xs text-white/70 leading-relaxed font-medium">Swap plastic bags, straws, and bottles for sustainable alternatives.</p>
          </div>
          <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:bg-white/20 transition-all group">
            <div class="text-2xl mb-4 group-hover:scale-110 transition-transform text-[#4ade80]">
                <i data-lucide="trash-2"></i>
            </div>
            <h4 class="font-bold text-lg mb-2 text-[#4ade80]">Mindful Disposal</h4>
            <p class="text-xs text-white/70 leading-relaxed font-medium">Separate your waste at the source. Composting can reduce landfill waste by 40%.</p>
          </div>
          <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:bg-white/20 transition-all group">
            <div class="text-2xl mb-4 group-hover:scale-110 transition-transform text-[#4ade80]">
                <i data-lucide="zap"></i>
            </div>
            <h4 class="font-bold text-lg mb-2 text-[#4ade80]">Energy Sync</h4>
            <p class="text-xs text-white/70 leading-relaxed font-medium">Unplug devices when not in use. Small savings lead to massive global impact.</p>
          </div>
          <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 hover:bg-white/20 transition-all group">
            <div class="text-2xl mb-4 group-hover:scale-110 transition-transform text-[#4ade80]">
                <i data-lucide="droplets"></i>
            </div>
            <h4 class="font-bold text-lg mb-2 text-[#4ade80]">Water Guardians</h4>
            <p class="text-xs text-white/70 leading-relaxed font-medium">Fix leaks promptly. Let's protect our Sri Lankan waterways together.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
