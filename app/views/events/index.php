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
      <div class="md:col-span-3 relative group">
        <label class="block text-xs font-black text-[#121613] dark:text-gray-200 uppercase tracking-[0.2em] mb-2">District</label>
        <input type="hidden" name="district" id="district_input" value="<?php echo h($_GET['district'] ?? ''); ?>">
        
        <div id="district_trigger" onclick="toggleDropdown('district_menu', 'district_arrow')" 
             class="w-full px-5 py-4 rounded-2xl bg-gray-50 dark:bg-white/5 border-2 border-transparent hover:border-primary/10 cursor-pointer flex items-center justify-between transition-all">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-8 h-8 rounded-full bg-primary/5 dark:bg-white/5 flex-shrink-0 flex items-center justify-center text-primary dark:text-[#4ade80]">
                    <i data-lucide="map" class="w-4 h-4"></i>
                </div>
                <span id="district_text" class="font-bold text-[#121613] dark:text-white truncate">
                    <?php echo !empty($_GET['district']) ? h($_GET['district']) : 'All Districts'; ?>
                </span>
            </div>
            <i data-lucide="chevron-down" id="district_arrow" class="w-4 h-4 text-[#677e6b] transition-transform duration-300"></i>
        </div>

        <div id="district_menu" class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-[#1A1E1B] rounded-2xl shadow-xl border border-gray-100 dark:border-white/5 overflow-hidden transition-all duration-300 opacity-0 invisible transform -translate-y-2 origin-top z-50">
            <div class="p-2 space-y-1 max-h-[300px] overflow-y-auto custom-scrollbar">
                <div onclick="selectOption('district', '')" class="px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer font-bold text-gray-400 text-sm">All Districts</div>
                <?php
                $districts = ['Ampara', 'Anuradhapura', 'Badulla', 'Batticaloa', 'Colombo', 'Galle', 'Gampaha', 'Hambantota', 'Jaffna', 'Kalutara', 'Kandy', 'Kegalle', 'Kilinochchi', 'Kurunegala', 'Mannar', 'Matale', 'Matara', 'Moneragala', 'Mullaitivu', 'Nuwara Eliya', 'Polonnaruwa', 'Puttalam', 'Ratnapura', 'Trincomalee', 'Vavuniya'];
                foreach ($districts as $d):
                ?>
                <div onclick="selectOption('district', '<?php echo $d; ?>')" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer transition-colors">
                     <span class="font-bold text-[#121613] dark:text-white text-sm"><?php echo $d; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
      </div>

      <!-- Category Filter -->
      <div class="md:col-span-3 relative group">
        <label class="block text-xs font-black text-[#121613] dark:text-gray-200 uppercase tracking-[0.2em] mb-2">Category</label>
        <input type="hidden" name="category" id="category_input" value="<?php echo h($_GET['category'] ?? ''); ?>">

        <?php
        $categoryIcons = [
            'Beach & Coastal Cleanups' => 'waves',
            'Waterway & Wetland Cleanups' => 'droplets',
            'Park & Forest Cleanups' => 'trees',
            'Urban & Street Cleanups' => 'building-2',
            'Underwater/Dive Cleanups' => 'anchor',
            'Tree Planting & Reforestation' => 'shrub',
            'General Cleanup' => 'sprout'
        ];
        $currentCat = $_GET['category'] ?? '';
        $currentIcon = $categoryIcons[$currentCat] ?? 'tag';
        ?>

        <div id="category_trigger" onclick="toggleDropdown('category_menu', 'category_arrow')" 
             class="w-full px-5 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 border-2 border-transparent hover:border-primary/10 cursor-pointer flex items-center justify-between transition-all">
            <div class="flex items-center gap-3 overflow-hidden">
                <div id="category_icon_display" class="w-8 h-8 rounded-full bg-[#2c4931]/10 dark:bg-[#4ade80]/10 flex-shrink-0 flex items-center justify-center text-[#2c4931] dark:text-[#4ade80]">
                    <i data-lucide="<?php echo $currentIcon; ?>" class="w-4 h-4"></i>
                </div>
                <span id="category_text" class="font-bold text-[#121613] dark:text-white truncate">
                     <?php echo !empty($currentCat) ? h($currentCat) : 'All Categories'; ?>
                </span>
            </div>
            <i data-lucide="chevron-down" id="category_arrow" class="w-4 h-4 text-[#677e6b] transition-transform duration-300"></i>
        </div>

        <div id="category_menu" class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-[#1A1E1B] rounded-2xl shadow-xl border border-gray-100 dark:border-white/5 overflow-hidden transition-all duration-300 opacity-0 invisible transform -translate-y-2 origin-top z-50">
            <div class="p-2 space-y-1 max-h-[300px] overflow-y-auto custom-scrollbar">
                <div onclick="selectCategoryOption('', 'tag')" class="px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer flex items-center gap-3 transition-colors">
                     <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-400">
                        <i data-lucide="tag" class="w-4 h-4"></i>
                    </div>
                    <span class="font-bold text-gray-400 text-sm">All Categories</span>
                </div>
                <?php foreach ($categoryIcons as $id => $icon): ?>
                <div onclick="selectCategoryOption('<?php echo $id; ?>', '<?php echo $icon; ?>')" 
                     class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer transition-colors group/item">
                    <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-400 group-hover/item:text-primary dark:group-hover/item:text-[#4ade80] transition-colors">
                        <i data-lucide="<?php echo $icon; ?>" class="w-4 h-4"></i>
                    </div>
                     <span class="font-bold text-[#121613] dark:text-white text-sm"><?php echo $id; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
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

<script>
function toggleDropdown(menuId, arrowId) {
    const menu = document.getElementById(menuId);
    const arrow = document.getElementById(arrowId);
    
    // Close other dropdowns first
    ['district_menu', 'category_menu'].forEach(id => {
        if (id !== menuId) {
            const el = document.getElementById(id);
            if (el) el.classList.add('invisible', 'opacity-0', '-translate-y-2');
        }
    });

    if (menu.classList.contains('invisible')) {
        menu.classList.remove('invisible', 'opacity-0', '-translate-y-2');
        if(arrow) arrow.classList.add('rotate-180');
    } else {
        menu.classList.add('invisible', 'opacity-0', '-translate-y-2');
        if(arrow) arrow.classList.remove('rotate-180');
    }
}

function selectOption(type, value) {
    // Update Hidden Input
    const input = document.getElementById(type + '_input');
    if(input) input.value = value;
    
    // Update Display Text
    const displayText = document.getElementById(type + '_text');
    let label = '';
    if(type === 'district') label = value ? value : 'All Districts';
    
    if(displayText) displayText.innerText = label;
    
    // Close Dropdown
    const menu = document.getElementById(type + '_menu');
    if(menu) menu.classList.add('invisible', 'opacity-0', '-translate-y-2');
    
    const arrow = document.getElementById(type + '_arrow');
    if(arrow) arrow.classList.remove('rotate-180');
}

function selectCategoryOption(value, icon) {
    // Update Hidden Input
    document.getElementById('category_input').value = value;
    
    // Update Display Text
    const label = value ? value : 'All Categories';
    document.getElementById('category_text').innerText = label;

    // Update Icon
    const iconContainer = document.getElementById('category_icon_display');
    iconContainer.innerHTML = `<i data-lucide="${icon}" class="w-4 h-4"></i>`;
    
    // Close Dropdown
    const menu = document.getElementById('category_menu');
    menu.classList.add('invisible', 'opacity-0', '-translate-y-2');
    
    const arrow = document.getElementById('category_arrow');
    arrow.classList.remove('rotate-180');

    // Refresh Icons
    lucide.createIcons();
}

// Global click to close
document.addEventListener('click', function(event) {
    const triggers = ['district_trigger', 'category_trigger'];
    const menus = ['district_menu', 'category_menu'];
    
    let isClickInside = false;
    triggers.forEach(id => {
        const el = document.getElementById(id);
        if(el && el.contains(event.target)) isClickInside = true;
    });
    menus.forEach(id => {
        const el = document.getElementById(id);
        if(el && el.contains(event.target)) isClickInside = true;
    });
    
    if (!isClickInside) {
        menus.forEach(id => {
            const el = document.getElementById(id);
            if(el) el.classList.add('invisible', 'opacity-0', '-translate-y-2');
        });
        ['district_arrow', 'category_arrow'].forEach(id => {
            const el = document.getElementById(id);
            if(el) el.classList.remove('rotate-180');
        });
    }
});
</script>
