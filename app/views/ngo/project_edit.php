<?php
// app/views/ngo/project_edit.php
?>

<div class="max-w-5xl mx-auto px-6 py-12">
    <!-- Header -->
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-[#121613] dark:text-white text-3xl font-black tracking-tight mb-2">Edit Cleanup Drive</h2>
            <p class="text-[#677e6b] dark:text-gray-400 font-medium italic">Update the details or status of your current mission.</p>
        </div>
        <div class="flex items-center gap-3 px-4 py-2 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-xs font-black text-primary dark:text-[#4ade80] uppercase tracking-widest">Event ID:</span>
            <span class="text-xs font-bold text-[#121613] dark:text-white">#<?php echo (int)$project['id']; ?></span>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="mb-8 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-red-700 dark:text-red-400 text-sm font-bold flex items-center gap-3 animate-fade-in">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i> <?php echo h($error); ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <?php echo csrf_field(); ?>
        <!-- Left: Location & Image -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Map Card -->
            <div class="bg-white dark:bg-darkSurface rounded-[40px] p-8 border border-gray-100 dark:border-white/5 shadow-sm">
                <p class="text-[10px] font-black text-[#677e6b] dark:text-gray-500 uppercase tracking-[0.2em] mb-6">Precise Location</p>
                <div id="projectMap" class="h-64 w-full rounded-3xl border border-gray-100 dark:border-white/5 shadow-inner mb-4 overflow-hidden"></div>
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" name="latitude" id="lat" value="<?php echo h($project['latitude'] ?? ''); ?>" readonly class="text-[10px] py-2 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border-none font-bold text-[#677e6b] text-center">
                    <input type="text" name="longitude" id="lng" value="<?php echo h($project['longitude'] ?? ''); ?>" readonly class="text-[10px] py-2 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border-none font-bold text-[#677e6b] text-center">
                </div>
                <p class="text-[10px] text-[#2c4931] dark:text-[#4ade80] font-bold mt-4 text-center">Click on the map to relocate</p>
            </div>

            <!-- Image Card -->
            <div class="bg-white dark:bg-darkSurface rounded-[40px] p-8 border border-gray-100 dark:border-white/5 shadow-sm text-center">
                <p class="text-[10px] font-black text-[#677e6b] dark:text-gray-500 uppercase tracking-[0.2em] mb-6">Promotional Image</p>
                <div class="relative group mx-auto w-full aspect-video mb-6 rounded-2xl overflow-hidden bg-gray-50 dark:bg-darkBg border border-gray-100 dark:border-white/5">
                    <?php if (!empty($project['image_path'])): ?>
                        <img id="imgPreview" src="<?php echo h($project['image_path']); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div id="imgPlaceholder" class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                           <i data-lucide="camera" class="w-10 h-10 mb-2"></i>
                           <span class="text-[10px] font-bold uppercase tracking-widest">No Image Set</span>
                        </div>
                    <?php endif; ?>
                </div>
                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" onchange="previewImage(this)">
                <label for="imageInput" class="cursor-pointer inline-flex items-center justify-center w-full py-4 rounded-2xl bg-primary/5 text-primary dark:text-[#4ade80] text-xs font-black uppercase tracking-widest hover:bg-primary hover:text-white transition-all">
                    Replace Image
                </label>
            </div>
        </div>

        <!-- Right: Content Fields -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white dark:bg-darkSurface rounded-[40px] p-8 md:p-10 border border-gray-100 dark:border-white/5 shadow-sm space-y-8">
                <!-- Title, Status, Category -->
                <!-- Title, Status, Category -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <!-- Title: Full Width -->
                    <div class="md:col-span-12 group">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Mission Title</label>
                        <input type="text" name="title" value="<?php echo h($project['title']); ?>" required class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium">
                    </div>
                    
                    <!-- Category: 8 Cols -->
                    <div class="md:col-span-8 group relative z-20">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Category</label>
                        <input type="hidden" name="category" id="category_input" value="<?php echo h($project['category']); ?>">
                        
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
                        $currentIcon = $categoryIcons[$project['category']] ?? 'sprout';
                        ?>

                        <div id="category_trigger" onclick="toggleCategoryDropdown()" 
                             class="w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg hover:border-primary/10 cursor-pointer flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div id="category_icon" class="w-8 h-8 rounded-full bg-[#2c4931]/10 dark:bg-[#4ade80]/10 flex-shrink-0 flex items-center justify-center text-[#2c4931] dark:text-[#4ade80]">
                                    <i data-lucide="<?php echo $currentIcon; ?>" class="w-4 h-4"></i>
                                </div>
                                <span id="category_text" class="font-medium text-[#121613] dark:text-white truncate"><?php echo h($project['category']); ?></span>
                            </div>
                            <i data-lucide="chevron-down" id="category_arrow" class="w-4 h-4 text-gray-400 transition-transform duration-300 flex-shrink-0"></i>
                        </div>

                        <div id="category_menu" class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-[#1A1E1B] rounded-2xl shadow-xl border border-gray-100 dark:border-white/5 overflow-hidden transition-all duration-300 opacity-0 invisible transform -translate-y-2 origin-top z-50">
                            <div class="p-2 space-y-1 max-h-[300px] overflow-y-auto custom-scrollbar">
                                <?php foreach ($categoryIcons as $cat => $icon): ?>
                                <div onclick="selectCategory('<?php echo $cat; ?>', '<?php echo $icon; ?>')" 
                                     class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer transition-colors group/item">
                                    <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-400 group-hover/item:text-[#2c4931] dark:group-hover/item:text-[#4ade80] transition-colors">
                                        <i data-lucide="<?php echo $icon; ?>" class="w-4 h-4"></i>
                                    </div>
                                    <span class="font-medium text-[#121613] dark:text-white text-sm"><?php echo $cat; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status: 4 Cols -->
                    <div class="md:col-span-4 group relative z-10">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Status</label>
                        <input type="hidden" name="status" id="status_input" value="<?php echo h($project['status']); ?>">
                        
                        <div id="status_trigger" onclick="toggleStatusDropdown()" 
                             class="w-full px-4 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg hover:border-primary/10 cursor-pointer flex items-center justify-between transition-all">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div id="status_indicator" class="w-2 h-2 rounded-full flex-shrink-0 <?php echo $project['status'] === 'open' ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.4)]' : 'bg-gray-400'; ?>"></div>
                                <span id="status_text" class="font-medium text-[#121613] dark:text-white capitalize whitespace-nowrap overflow-hidden text-ellipsis"><?php echo h($project['status']); ?></span>
                            </div>
                            <i data-lucide="chevron-down" id="status_arrow" class="w-4 h-4 text-gray-400 transition-transform duration-300 flex-shrink-0"></i>
                        </div>

                        <div id="status_menu" class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-[#1A1E1B] rounded-2xl shadow-xl border border-gray-100 dark:border-white/5 overflow-hidden transition-all duration-300 opacity-0 invisible transform -translate-y-2 origin-top z-50">
                            <div class="p-2 space-y-1">
                                <div onclick="selectStatus('open')" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer transition-colors">
                                    <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.4)]"></div>
                                    <span class="font-medium text-[#121613] dark:text-white">Open</span>
                                </div>
                                <div onclick="selectStatus('closed')" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer transition-colors">
                                    <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                                    <span class="font-medium text-[#121613] dark:text-white">Closed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timing & Rewards -->
                <!-- Timing & Rewards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="group md:col-span-2">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Event Date</label>
                        <div class="relative">
                            <input type="text" name="event_date" id="event_date_picker" value="<?php echo h($project['event_date']); ?>" required 
                                class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium">
                             <i data-lucide="calendar" class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="group">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Start</label>
                         <div class="relative">
                            <input type="text" name="start_time" id="start_time_picker" value="<?php echo date('H:i', strtotime($project['start_time'])); ?>" required 
                                class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium text-sm">
                        </div>
                    </div>
                    <div class="group">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">End</label>
                        <div class="relative">
                            <input type="text" name="end_time" id="end_time_picker" value="<?php echo date('H:i', strtotime($project['end_time'])); ?>" required 
                                class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium text-sm">
                        </div>
                    </div>
                </div>

                <!-- Flatpickr -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                
                <style>
                    /* Custom Flatpickr Theme */
                    .flatpickr-calendar {
                        border: none !important;
                        border-radius: 24px !important;
                        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1) !important;
                        font-family: inherit !important;
                        padding: 16px !important;
                        background: #ffffff !important;
                    }
                    .dark .flatpickr-calendar {
                        background: #1A1E1B !important;
                        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.5) !important;
                    }

                    /* Header */
                    .flatpickr-month { margin-bottom: 12px !important; }
                    .flatpickr-current-month {
                        font-weight: 800 !important;
                        text-transform: uppercase !important;
                        letter-spacing: 0.1em !important;
                        font-size: 14px !important;
                        color: #121613 !important;
                    }
                    .dark .flatpickr-current-month { color: #ffffff !important; }
                    .flatpickr-prev-month, .flatpickr-next-month { fill: #677e6b !important; }
                    .flatpickr-prev-month:hover svg, .flatpickr-next-month:hover svg { fill: #2c4931 !important; }
                    .dark .flatpickr-prev-month:hover svg, .dark .flatpickr-next-month:hover svg { fill: #4ade80 !important; }

                    /* Weekdays */
                    .flatpickr-weekday {
                        font-weight: 700 !important;
                        color: #a3a3a3 !important;
                        font-size: 10px !important;
                        text-transform: uppercase !important;
                        letter-spacing: 0.1em !important;
                    }

                    /* Days */
                    .flatpickr-day {
                        border-radius: 12px !important;
                        font-weight: 600 !important;
                        color: #121613 !important;
                        border: 1px solid transparent !important;
                    }
                    .dark .flatpickr-day { color: #e5e5e5 !important; }
                    .flatpickr-day:hover { background: #f0f5f1 !important; border-color: #f0f5f1 !important; }
                    .dark .flatpickr-day:hover { background: rgba(255,255,255,0.05) !important; border-color: transparent !important; }

                    .flatpickr-day.selected, .flatpickr-day.selected:hover {
                        background: #2c4931 !important;
                        border-color: #2c4931 !important;
                        color: #ffffff !important;
                        box-shadow: 0 4px 12px rgba(44, 73, 49, 0.3) !important;
                    }
                    .dark .flatpickr-day.selected, .dark .flatpickr-day.selected:hover {
                        background: #4ade80 !important;
                        border-color: #4ade80 !important;
                        color: #121613 !important;
                        box-shadow: 0 4px 12px rgba(74, 222, 128, 0.3) !important;
                    }
                    .flatpickr-day.today { border-color: #2c4931 !important; }
                    .dark .flatpickr-day.today { border-color: #4ade80 !important; }
                </style>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const config = {
                        disableMobile: "true",
                        animate: true,
                        prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>',
                        nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>'
                    };

                    flatpickr("#event_date_picker", {
                        ...config,
                         dateFormat: "Y-m-d",
                        minDate: "today",
                    });
                    
                    // Manual Time Inputs
                    const timeInputs = ['start_time_picker', 'end_time_picker'];
                    
                    timeInputs.forEach(id => {
                        const el = document.getElementById(id);
                        if (!el) return;
                        
                        // ... existing time input logic ...
                        el.addEventListener('input', function(e) {
                            let val = e.target.value.replace(/[^0-9:]/g, '');
                            if(val.length === 2 && !val.includes(':')) val += ':';
                            if(val.length > 5) val = val.substring(0, 5);
                            e.target.value = val;
                        });
                        
                        el.addEventListener('blur', function(e) {
                             const val = e.target.value;
                             const timeRegex = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
                             if(val && !timeRegex.test(val)) {
                                 alert('Please enter a valid 24h time format (HH:MM)');
                                 e.target.value = '';
                             }
                        });
                    });
                });

                function toggleStatusDropdown() {
                    const menu = document.getElementById('status_menu');
                    const arrow = document.getElementById('status_arrow');
                    
                    if (menu.classList.contains('invisible')) {
                        // Close other dropdowns if open
                        const categoryMenu = document.getElementById('category_menu');
                        if (categoryMenu && !categoryMenu.classList.contains('invisible')) {
                            toggleCategoryDropdown();
                        }

                        // Open
                        menu.classList.remove('invisible', 'opacity-0', '-translate-y-2');
                        arrow.classList.add('rotate-180');
                    } else {
                        // Close
                        menu.classList.add('invisible', 'opacity-0', '-translate-y-2');
                        arrow.classList.remove('rotate-180');
                    }
                }

                function selectStatus(status) {
                    document.getElementById('status_input').value = status;
                    document.getElementById('status_text').innerText = status.charAt(0).toUpperCase() + status.slice(1);
                    
                    const indicator = document.getElementById('status_indicator');
                    if (status === 'open') {
                        indicator.className = 'w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.4)]';
                    } else {
                        indicator.className = 'w-2 h-2 rounded-full bg-gray-400';
                    }
                    
                    toggleStatusDropdown();
                }

                function toggleCategoryDropdown() {
                    const menu = document.getElementById('category_menu');
                    const arrow = document.getElementById('category_arrow');
                    
                    if (menu.classList.contains('invisible')) {
                        // Close other dropdowns if open
                        const statusMenu = document.getElementById('status_menu');
                        if (statusMenu && !statusMenu.classList.contains('invisible')) {
                            toggleStatusDropdown();
                        }

                        // Open
                        menu.classList.remove('invisible', 'opacity-0', '-translate-y-2');
                        arrow.classList.add('rotate-180');
                    } else {
                        // Close
                        menu.classList.add('invisible', 'opacity-0', '-translate-y-2');
                        arrow.classList.remove('rotate-180');
                    }
                }

                function selectCategory(category, icon) {
                    document.getElementById('category_input').value = category;
                    document.getElementById('category_text').innerText = category;
                    
                    // Update main icon
                    const iconContainer = document.getElementById('category_icon');
                    // Remove old icon
                    iconContainer.innerHTML = '';
                    // Create new icon element
                    const newIcon = document.createElement('i');
                    newIcon.setAttribute('data-lucide', icon);
                    newIcon.classList.add('w-4', 'h-4');
                    iconContainer.appendChild(newIcon);
                    
                    // Re-initialize icons
                    lucide.createIcons();
                    
                    toggleCategoryDropdown();
                }

                // Close dropdown on outside click
                document.addEventListener('click', function(event) {
                    const triggers = ['status_trigger', 'category_trigger'];
                    const menus = ['status_menu', 'category_menu'];
                    
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
                        ['status_arrow', 'category_arrow'].forEach(id => {
                            const el = document.getElementById(id);
                            if(el) el.classList.remove('rotate-180');
                        });
                    }
                });
                </script>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 group">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Display Location</label>
                        <input type="text" name="location" value="<?php echo h($project['location']); ?>" placeholder="e.g. Mount Lavinia Beach" required class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium">
                    </div>
                    <div class="group">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Calculated Rewards (10 pts/hr)</label>
                        <div class="flex items-center gap-4 bg-gray-50 dark:bg-darkBg rounded-2xl px-5 py-4 border-2 border-gray-50 dark:border-white/5">
                            <span id="display_points" class="font-black text-2xl text-primary dark:text-[#4ade80]">
                                <?php echo (int)$project['points_reward']; ?>
                            </span>
                            <span class="text-[10px] font-bold text-[#677e6b] dark:text-gray-500 uppercase tracking-widest">PTS</span>
                        </div>
                        <input type="hidden" name="points_reward" id="points_reward" value="<?php echo (int)$project['points_reward']; ?>">
                    </div>
                </div>

                <div class="group">
                    <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Mission Plan & Details</label>
                    <textarea name="description" rows="8" placeholder="Outline the cleanup scope, meeting point, and required gear..." class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium"><?php echo h($project['description']); ?></textarea>
                </div>

                <div class="pt-6 flex flex-col md:flex-row items-center gap-4 border-t border-gray-50 dark:border-white/5">
                    <button type="submit" class="w-full md:flex-1 py-5 rounded-2xl bg-primary text-white text-sm font-black uppercase tracking-[0.2em] shadow-xl hover:bg-[#121613] transition-all transform hover:scale-[1.02] active:scale-95 shadow-primary/20">
                        Confirm Updates
                    </button>
                    <a href="index.php?route=ngo_dashboard" class="w-full md:w-auto px-10 py-5 rounded-2xl bg-gray-50 dark:bg-white/5 text-[#677e6b] dark:text-gray-400 text-sm font-black uppercase tracking-widest hover:bg-gray-100 transition-all text-center">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Scripts for Map and Preview -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map;
    let marker;
    const initialLat = <?php echo !empty($project['latitude']) ? (float)$project['latitude'] : 7.8731; ?>;
    const initialLng = <?php echo !empty($project['longitude']) ? (float)$project['longitude'] : 80.7718; ?>;

    function initMap() {
        map = L.map('projectMap', {
            scrollWheelZoom: false
        }).setView([initialLat, initialLng], <?php echo !empty($project['latitude']) ? 15 : 7; ?>);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        <?php if (!empty($project['latitude'])): ?>
            marker = L.marker([initialLat, initialLng]).addTo(map);
        <?php endif; ?>

        map.on('click', function(e) {
            const { lat, lng } = e.latlng;
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
            document.getElementById('lat').value = lat.toFixed(6);
            document.getElementById('lng').value = lng.toFixed(6);
        });

        map.on('focus', () => { map.scrollWheelZoom.enable(); });
        map.on('blur', () => { map.scrollWheelZoom.disable(); });
    }

    function previewImage(input) {
        const preview = document.getElementById('imgPreview');
        const placeholder = document.getElementById('imgPlaceholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview) {
                    preview.src = e.target.result;
                } else {
                   const container = placeholder.parentElement;
                   container.innerHTML = `<img id="imgPreview" src="${e.target.result}" class="w-full h-full object-cover">`;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function calculateNGOPoints() {
        const startTime = document.querySelector('input[name="start_time"]').value;
        const endTime = document.querySelector('input[name="end_time"]').value;
        const displaySpan = document.getElementById('display_points');
        const hiddenInput = document.getElementById('points_reward');

        if (startTime && endTime) {
            const [h1, m1] = startTime.split(':').map(Number);
            const [h2, m2] = endTime.split(':').map(Number);
            
            let diffMinutes = (h2 * 60 + m2) - (h1 * 60 + m1);
            if (diffMinutes < 0) diffMinutes += 24 * 60;
            
            const hours = diffMinutes / 60;
            const totalPoints = Math.round(hours * 10);
            
            displaySpan.innerText = totalPoints;
            hiddenInput.value = totalPoints;
        }
    }

    document.querySelector('input[name="start_time"]').addEventListener('change', calculateNGOPoints);
    document.querySelector('input[name="end_time"]').addEventListener('change', calculateNGOPoints);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMap);
    } else {
        initMap();
        calculateNGOPoints(); // Initial calculation
    }
</script>
