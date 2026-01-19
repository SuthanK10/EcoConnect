<?php
// app/views/events/index.php
?>

<div class="max-w-7xl mx-auto px-4 py-12">
  <!-- Page Header -->
  <div class="mb-12">
    <h1 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-4">Cleanup Drives</h1>
    <p class="text-lg text-[#677e6b] dark:text-gray-400 max-w-2xl font-medium">
      Join our mission to restore the natural beauty of Sri Lanka. Browse local cleanups and start making an impact today.
    </p>
  </div>

  <!-- Filter Section -->
  <div class="bg-white dark:bg-darkSurface rounded-[40px] p-6 border border-gray-100 dark:border-white/5 shadow-sm mb-12">
    <form method="get" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
      <input type="hidden" name="route" value="events" />
      
      <!-- District Filter -->
      <div class="md:col-span-3 relative">
        <label class="absolute -top-2 left-4 bg-white dark:bg-darkSurface px-2 text-[10px] font-black text-primary dark:text-[#4ade80] uppercase tracking-widest z-10">District</label>
        <select
          name="district"
          class="w-full bg-gray-50 dark:bg-white/5 border-2 border-transparent dark:text-white rounded-2xl py-4 pl-6 pr-10 text-sm font-bold appearance-none focus:ring-2 focus:ring-primary focus:bg-white dark:focus:bg-darkSurface transition-all outline-none"
        >
          <option value="">All Districts</option>
          <?php
          $districts = ['Ampara', 'Anuradhapura', 'Badulla', 'Batticaloa', 'Colombo', 'Galle', 'Gampaha', 'Hambantota', 'Jaffna', 'Kalutara', 'Kandy', 'Kegalle', 'Kilinochchi', 'Kurunegala', 'Mannar', 'Matale', 'Matara', 'Moneragala', 'Mullaitivu', 'Nuwara Eliya', 'Polonnaruwa', 'Puttalam', 'Ratnapura', 'Trincomalee', 'Vavuniya'];
          foreach ($districts as $d):
          ?>
            <option value="<?php echo $d; ?>" <?php echo ($_GET['district'] ?? '') === $d ? 'selected' : ''; ?>>
              <?php echo $d; ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-xs opacity-50 dark:text-white/50">
          <i data-lucide="chevron-down" class="w-4 h-4"></i>
        </div>
      </div>

      <!-- Category Filter -->
      <div class="md:col-span-3 relative">
        <label class="absolute -top-2 left-4 bg-white dark:bg-darkSurface px-2 text-[10px] font-black text-primary dark:text-[#4ade80] uppercase tracking-widest z-10">Category</label>
        <select
          name="category"
          class="w-full bg-gray-50 dark:bg-white/5 border-2 border-transparent dark:text-white rounded-2xl py-4 pl-6 pr-10 text-sm font-bold appearance-none focus:ring-2 focus:ring-primary focus:bg-white dark:focus:bg-darkSurface transition-all outline-none"
        >
          <option value="">All Categories</option>
          <?php
          $categories = ['Beach & Coastal Cleanups', 'Waterway & Wetland Cleanups', 'Park & Forest Cleanups', 'Urban & Street Cleanups', 'Underwater/Dive Cleanups', 'Tree Planting & Reforestation', 'General Cleanup'];
          foreach ($categories as $c):
          ?>
            <option value="<?php echo $c; ?>" <?php echo ($_GET['category'] ?? '') === $c ? 'selected' : ''; ?>>
              <?php echo $c; ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-xs opacity-50 dark:text-white/50">
          <i data-lucide="chevron-down" class="w-4 h-4"></i>
        </div>
      </div>

      <!-- Keyword Search -->
      <div class="md:col-span-4 relative">
        <label class="absolute -top-2 left-4 bg-white dark:bg-darkSurface px-2 text-[10px] font-black text-primary dark:text-[#4ade80] uppercase tracking-widest z-10">Specific Area</label>
        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-gray-400">
          <i data-lucide="search" class="w-4 h-4"></i>
        </div>
        <input
          type="text"
          name="search"
          value="<?php echo h($_GET['search'] ?? ''); ?>"
          placeholder="Keyword..."
          class="w-full bg-gray-50 dark:bg-white/5 border-2 border-transparent dark:text-white rounded-2xl py-4 pl-14 pr-6 text-sm font-bold focus:ring-2 focus:ring-primary focus:bg-white dark:focus:bg-darkSurface transition-all outline-none placeholder:dark:text-white/30"
        />
      </div>

      <!-- Action Button -->
      <div class="md:col-span-2">
        <button
          type="submit"
          class="w-full bg-primary dark:bg-[#4ade80] dark:text-primary text-white py-4 rounded-2xl font-black text-sm hover:scale-[1.02] transition-all shadow-lg shadow-primary/20"
        >
          Explore
        </button>
      </div>
    </form>
    
    <?php if (!empty($_GET['district']) || !empty($_GET['search'])): ?>
      <div class="mt-4 flex items-center gap-3">
        <span class="text-xs font-bold text-[#677e6b] dark:text-gray-500">Filtered results for: </span>
        <div class="flex gap-2">
          <?php if (!empty($_GET['district']) && $_GET['district'] !== 'All Districts'): ?>
            <span class="bg-[#f0f5f1] dark:bg-white/5 text-primary dark:text-[#4ade80] text-[10px] font-black px-3 py-1 rounded-full border border-primary/10 uppercase tracking-tighter flex items-center gap-1">
              <i data-lucide="map-pin" class="w-3 h-3"></i> <?php echo h($_GET['district']); ?>
            </span>
          <?php endif; ?>
          <?php if (!empty($_GET['category']) && $_GET['category'] !== 'All Categories'): ?>
            <span class="bg-[#f0f5f1] dark:bg-white/5 text-primary dark:text-[#4ade80] text-[10px] font-black px-3 py-1 rounded-full border border-primary/10 uppercase tracking-tighter flex items-center gap-1">
              <i data-lucide="tag" class="w-3 h-3"></i> <?php echo h($_GET['category']); ?>
            </span>
          <?php endif; ?>
          <?php if (!empty($_GET['search'])): ?>
            <span class="bg-[#f0f5f1] dark:bg-white/5 text-primary dark:text-[#4ade80] text-[10px] font-black px-3 py-1 rounded-full border border-primary/10 uppercase tracking-tighter flex items-center gap-1">
              <i data-lucide="search" class="w-3 h-3"></i> "<?php echo h($_GET['search']); ?>"
            </span>
          <?php endif; ?>
          <a href="index.php?route=events" class="text-[10px] font-black text-red-500 hover:text-red-700 uppercase tracking-tighter ml-2 self-center flex items-center gap-1">
            <i data-lucide="x" class="w-3 h-3"></i> Clear Filters
          </a>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Events Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php if (!empty($projects)): ?>
      <?php foreach ($projects as $project): ?>
        <?php $img = $project['image_path'] ?? 'assets/default-event.jpg'; ?>
        <div class="group flex flex-col bg-white dark:bg-darkSurface rounded-[32px] border border-gray-100 dark:border-white/5 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
          <!-- Image Container -->
          <div class="relative h-60 overflow-hidden">
            <img 
              src="<?php echo h($img); ?>" 
              alt="<?php echo h($project['title']); ?>" 
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <!-- Points Badge -->
            <div class="absolute top-4 right-4 bg-white/90 dark:bg-black/40 backdrop-blur px-4 py-1.5 rounded-full shadow-sm flex items-center gap-2">
              <i data-lucide="trophy" class="w-3 h-3 text-primary dark:text-[#4ade80]"></i>
              <span class="text-[10px] font-black text-primary dark:text-[#4ade80] uppercase tracking-widest"><?php echo (int)$project['points_reward']; ?> Points</span>
            </div>
          </div>

          <!-- Content -->
          <div class="p-8 flex flex-col flex-1">
            <div class="mb-4">
              <div class="flex flex-wrap gap-2 mb-3">
                <span class="text-[10px] font-black text-primary dark:text-[#4ade80] uppercase tracking-widest bg-[#f0f5f1] dark:bg-white/5 px-3 py-1 rounded-full">
                  <?php echo h($project['ngo_name']); ?>
                </span>
                <span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest bg-blue-50 dark:bg-blue-500/5 px-3 py-1 rounded-full">
                  <?php echo h($project['category'] ?? 'General'); ?>
                </span>
              </div>
              <h3 class="text-xl font-black text-[#121613] dark:text-white leading-tight group-hover:text-primary dark:group-hover:text-[#4ade80] transition-colors line-clamp-2">
                <?php echo h($project['title']); ?>
              </h3>
            </div>
            
            <div class="space-y-2 mb-8 flex-grow">
              <div class="flex items-center gap-2 text-sm text-[#677e6b] dark:text-gray-400">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
                <span class="font-medium truncate"><?php echo h($project['location']); ?></span>
              </div>
              <div class="flex items-center gap-2 text-sm text-[#677e6b] dark:text-gray-400">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span class="font-medium"><?php echo h($project['event_date']); ?></span>
              </div>
            </div>

              <a
                href="index.php?route=event_show&id=<?php echo (int)$project['id']; ?>"
                class="w-full py-4 rounded-2xl bg-gray-50 dark:bg-white/5 text-[#121613] dark:text-white text-sm font-black text-center transition-all group-hover:bg-primary dark:group-hover:bg-[#4ade80] group-hover:text-white dark:group-hover:text-primary flex items-center justify-center gap-2"
              >
                Learn More <i data-lucide="chevron-right" class="w-4 h-4"></i>
              </a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-span-full py-20 text-center">
        <div class="text-6xl mb-6 font-bold flex justify-center text-gray-300">
            <i data-lucide="search-x" class="w-20 h-20"></i>
        </div>
        <h3 class="text-2xl font-black text-[#121613] dark:text-white mb-2">No matching events found</h3>
        <p class="text-[#677e6b] dark:text-gray-400 font-medium">Try searching for a different location or check back soon for new drives.</p>
        <a href="index.php?route=events" class="inline-block mt-8 text-primary dark:text-[#4ade80] font-black uppercase tracking-widest text-xs hover:underline">
          View all events
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>
