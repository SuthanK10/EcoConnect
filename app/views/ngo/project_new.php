<?php
// app/views/ngo/project_new.php
$proposalData = $_SESSION['adopting_proposal_data'] ?? null;
?>
<!-- Leaflet Resources -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="max-w-4xl mx-auto px-4 py-12">
  <div class="mb-10 flex items-center justify-between">
    <div>
      <h1 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-2 flex items-center gap-3">
        Create Impact Drive <i data-lucide="leaf" class="w-8 h-8 text-[#4ade80]"></i>
      </h1>
      <p class="text-[17px] text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed">Launch a new environmental initiative and mobilize the community.</p>
    </div>
    <a href="index.php?route=ngo_dashboard" class="text-sm font-bold text-[#677e6b] dark:text-gray-500 hover:text-[#2c4931] dark:hover:text-[#4ade80] transition-colors flex items-center gap-1">
      <i data-lucide="chevron-left" class="w-4 h-4"></i> Back to Control
    </a>
  </div>

  <?php if (!empty($proposalData)): ?>
    <div class="mb-8 rounded-3xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 p-6 flex items-start gap-4 text-blue-600">
      <i data-lucide="lightbulb" class="w-6 h-6"></i>
      <div>
        <h4 class="text-sm font-black text-blue-900 dark:text-blue-400 uppercase tracking-widest mb-1">Impact Adoption Mode</h4>
        <p class="text-xs text-blue-700/80 dark:text-blue-300 leading-relaxed font-bold">
          We've pre-filled the form with details from the community proposal by <strong><?php echo h($proposalData['user_name'] ?? 'a volunteer'); ?></strong>. 
          Modify the details below to finalize the official drive.
        </p>
      </div>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="mb-8 rounded-3xl bg-red-50 border border-red-100 p-6 flex items-center gap-4 text-red-700 font-bold text-sm">
      <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i> <?php echo h($error); ?>
    </div>
  <?php elseif (!empty($success)): ?>
    <div class="mb-8 rounded-3xl bg-green-50 border border-green-100 p-6 flex items-center gap-4 text-green-700 font-bold text-sm text-emerald-600">
      <i data-lucide="check-circle-2" class="w-5 h-5"></i> <?php echo h($success); ?>
    </div>
  <?php endif; ?>

  <form enctype="multipart/form-data" method="post" class="space-y-8 bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 p-8 md:p-12 shadow-sm">
    <?php echo csrf_field(); ?>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Left Side: Basic Info -->
      <div class="space-y-6">
        <div>
          <label class="block text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em] mb-3">Event Title</label>
          <input type="text" name="title" required placeholder="e.g. Galle Face Beach Cleanup"
            value="<?php echo h($proposalData['title'] ?? ''); ?>"
            class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] placeholder:text-gray-400 font-bold text-[#121613] dark:text-white transition-all">
        </div>

        <div>
            <label class="block text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em] mb-3">Description</label>
            <textarea name="description" rows="5" required placeholder="Describe the mission goals..."
                class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] placeholder:text-gray-400 font-bold text-[#121613] dark:text-white transition-all"><?php echo h($proposalData['description'] ?? ''); ?></textarea>
        </div>

        <div class="relative group z-50">
            <label class="block text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em] mb-3">Drive Category</label>
            
            <!-- Hidden Input -->
            <input type="hidden" name="category" id="category_input" value="General Cleanup">
            
            <!-- Custom Dropdown Trigger -->
            <div id="dropdown_trigger" onclick="toggleDropdown()" 
                class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 border border-transparent hover:border-[#2c4931]/20 dark:hover:border-[#4ade80]/20 cursor-pointer flex items-center justify-between transition-all group-hover:scale-[1.01]">
                <div class="flex items-center gap-3">
                    <div id="category_icon" class="w-8 h-8 rounded-full bg-[#2c4931]/10 dark:bg-[#4ade80]/10 flex items-center justify-center text-[#2c4931] dark:text-[#4ade80]">
                        <i data-lucide="sprout" class="w-4 h-4"></i>
                    </div>
                    <span id="selected_text" class="font-bold text-[#121613] dark:text-white">General Cleanup</span>
                </div>
                <i data-lucide="chevron-down" id="dropdown_arrow" class="w-5 h-5 text-[#677e6b] transition-transform duration-300"></i>
            </div>
            
            <!-- Dropdown Options -->
            <div id="dropdown_menu" class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-[#1A1E1B] rounded-2xl shadow-xl border border-gray-100 dark:border-white/5 overflow-hidden transition-all duration-300 opacity-0 invisible transform -translate-y-2 origin-top z-50">
                <div class="p-2 space-y-1 max-h-[300px] overflow-y-auto custom-scrollbar">
                    <?php 
                    $categories = [
                        ['id' => 'Beach & Coastal Cleanups', 'icon' => 'waves'],
                        ['id' => 'Waterway & Wetland Cleanups', 'icon' => 'droplets'],
                        ['id' => 'Park & Forest Cleanups', 'icon' => 'trees'],
                        ['id' => 'Urban & Street Cleanups', 'icon' => 'building-2'],
                        ['id' => 'Underwater/Dive Cleanups', 'icon' => 'anchor'],
                        ['id' => 'Tree Planting & Reforestation', 'icon' => 'shrub'],
                        ['id' => 'General Cleanup', 'icon' => 'sprout']
                    ];
                    foreach($categories as $cat): ?>
                    <div onclick="selectCategory('<?= $cat['id'] ?>', '<?= $cat['icon'] ?>')" 
                         class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-[#f0f5f1] dark:hover:bg-white/5 cursor-pointer transition-colors group/item">
                        <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-400 group-hover/item:text-[#2c4931] dark:group-hover/item:text-[#4ade80] transition-colors">
                            <i data-lucide="<?= $cat['icon'] ?>" class="w-4 h-4"></i>
                        </div>
                        <span class="font-bold text-gray-600 dark:text-gray-300 group-hover/item:text-[#121613] dark:group-hover/item:text-white"><?= h($cat['id']) ?></span>
                        <i data-lucide="check" class="w-4 h-4 text-[#2c4931] dark:text-[#4ade80] ml-auto opacity-0 group-hover/item:opacity-100 transition-opacity"></i>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <script>
        function toggleDropdown() {
            const menu = document.getElementById('dropdown_menu');
            const arrow = document.getElementById('dropdown_arrow');
            
            if (menu.classList.contains('invisible')) {
                // Open
                menu.classList.remove('invisible', 'opacity-0', '-translate-y-2');
                arrow.classList.add('rotate-180');
            } else {
                // Close
                menu.classList.add('invisible', 'opacity-0', '-translate-y-2');
                arrow.classList.remove('rotate-180');
            }
        }

        function selectCategory(value, icon) {
            document.getElementById('category_input').value = value;
            document.getElementById('selected_text').innerText = value;
            
            // Update Icon
            const iconContainer = document.getElementById('category_icon');
            iconContainer.innerHTML = `<i data-lucide="${icon}" class="w-4 h-4"></i>`;
            
            // Close dropdown
            toggleDropdown();
            
            // Re-initialize icons for the new one
            lucide.createIcons();
        }

        // Close when clicking outside
        document.addEventListener('click', function(event) {
            const trigger = document.getElementById('dropdown_trigger');
            const menu = document.getElementById('dropdown_menu');
            
            if (!trigger.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('invisible', 'opacity-0', '-translate-y-2');
                document.getElementById('dropdown_arrow').classList.remove('rotate-180');
            }
        });
        </script>

        <div>
          <label class="block text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em] mb-3">Cover Image</label>
          <div class="relative group">
            <input type="file" name="image" accept="image/*" 
              class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 border-2 border-dashed border-gray-200 dark:border-white/10 text-xs font-bold text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-[#2c4931] dark:file:bg-[#4ade80] file:text-white dark:file:text-primary hover:border-[#2c4931] dark:hover:border-[#4ade80] transition-all">
          </div>
        </div>
      </div>

      <!-- Right Side: Logistics -->
      <div class="space-y-6">
        <div>
          <label class="block text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em] mb-3">Location HQ</label>
          <input type="text" name="location" required placeholder="Meeting point name..."
            value="<?php echo h($proposalData['location'] ?? ''); ?>"
            class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] placeholder:text-gray-400 font-bold text-[#121613] dark:text-white transition-all">
        </div>

        <!-- Map Location Picker -->
        <div>
            <label class="block text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em] mb-3">Pin Location on Map</label>
            
            <!-- Search -->
            <div class="flex gap-2 mb-3">
                <input type="text" id="location_search" placeholder="Search area (e.g., Colombo)..." 
                    class="flex-1 px-4 py-2 rounded-xl bg-[#f0f5f1] dark:bg-white/5 border-none text-xs font-bold text-[#121613] dark:text-white focus:ring-2 focus:ring-[#2c4931]">
                <button type="button" onclick="searchLocation()" class="px-4 py-2 bg-[#2c4931] dark:bg-[#4ade80] rounded-xl text-white dark:text-primary text-xs font-bold hover:bg-[#1a2e1e] transition-all">Find</button>
            </div>

            <div id="map_picker" class="w-full h-[300px] rounded-2xl border border-gray-200 dark:border-white/10 z-0 relative overflow-hidden"></div>
            
            <input type="hidden" name="latitude" id="latitude" required value="<?php echo h($proposalData['latitude'] ?? ''); ?>">
            <input type="hidden" name="longitude" id="longitude" required value="<?php echo h($proposalData['longitude'] ?? ''); ?>">
            
            <div class="mt-3 flex items-center justify-between">
                <p class="text-[10px] bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-3 py-2 rounded-lg font-bold border border-blue-100 dark:border-blue-800 flex items-center gap-2">
                    <i data-lucide="mouse-pointer-click" class="w-3 h-3"></i> Click map to pin location
                </p>
                <p class="text-[10px] font-mono text-gray-400" id="coords_display"></p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div>
              <label class="block text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em] mb-3">Drive Date</label>
              <div class="relative">
                  <input type="text" name="event_date" id="event_date_picker" required 
                    value="<?php echo h($proposalData['proposed_date'] ?? ''); ?>"
                    class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] font-bold text-[#121613] dark:text-white transition-all cursor-pointer"
                    placeholder="Select Date">
                  <i data-lucide="calendar" class="absolute right-6 top-1/2 -translate-y-1/2 w-5 h-5 text-[#677e6b] pointer-events-none"></i>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-[10px] font-black text-[#121613] dark:text-white uppercase tracking-widest mb-2">Start Time</label>
                  <div class="relative">
                      <input type="text" name="start_time" id="start_time_picker" required 
                        class="w-full px-4 py-3 rounded-xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] font-bold text-[#121613] dark:text-white transition-all cursor-pointer"
                        placeholder="09:00">
                      <i data-lucide="clock" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#677e6b] pointer-events-none"></i>
                  </div>
                </div>
                <div>
                  <label class="block text-[10px] font-black text-[#121613] dark:text-white uppercase tracking-widest mb-2">End Time</label>
                  <div class="relative">
                      <input type="text" name="end_time" id="end_time_picker" required 
                        class="w-full px-4 py-3 rounded-xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] font-bold text-[#121613] dark:text-white transition-all cursor-pointer"
                        placeholder="12:00">
                      <i data-lucide="clock" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#677e6b] pointer-events-none"></i>
                  </div>
                </div>
            </div>
        </div>

        <!-- Flatpickr CSS & JS -->
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
            .flatpickr-month {
                margin-bottom: 12px !important;
            }
            .flatpickr-current-month {
                font-weight: 800 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.1em !important;
                font-size: 14px !important;
                color: #121613 !important;
            }
            .dark .flatpickr-current-month {
                color: #ffffff !important;
            }
            .flatpickr-prev-month, .flatpickr-next-month {
                fill: #677e6b !important;
            }
            .flatpickr-prev-month:hover svg, .flatpickr-next-month:hover svg {
                fill: #2c4931 !important;
            }
            .dark .flatpickr-prev-month:hover svg, .dark .flatpickr-next-month:hover svg {
                fill: #4ade80 !important;
            }

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
            .dark .flatpickr-day {
                color: #e5e5e5 !important;
            }
            
            .flatpickr-day:hover {
                background: #f0f5f1 !important;
                border-color: #f0f5f1 !important;
            }
            .dark .flatpickr-day:hover {
                background: rgba(255,255,255,0.05) !important;
                border-color: transparent !important;
            }

            .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
                background: #2c4931 !important;
                border-color: #2c4931 !important;
                color: #ffffff !important;
                box-shadow: 0 4px 12px rgba(44, 73, 49, 0.3) !important;
            }
            .dark .flatpickr-day.selected {
                background: #4ade80 !important;
                border-color: #4ade80 !important;
                color: #121613 !important;
                box-shadow: 0 4px 12px rgba(74, 222, 128, 0.3) !important;
            }

            .flatpickr-day.today {
                border-color: #2c4931 !important;
            }
            .dark .flatpickr-day.today {
                border-color: #4ade80 !important;
            }

            /* Time Picker */
            .flatpickr-time {
                border-top: 1px solid #f0f5f1 !important;
                margin-top: 12px !important;
                padding-top: 12px !important;
            }
            .dark .flatpickr-time {
                border-top: 1px solid rgba(255,255,255,0.05) !important;
            }
            
            .flatpickr-time input {
                font-weight: 700 !important;
                color: #121613 !important;
                font-size: 16px !important;
            }
            .dark .flatpickr-time input {
                color: #ffffff !important;
            }
            .flatpickr-time .flatpickr-am-pm {
                font-weight: 800 !important;
                color: #2c4931 !important;
            }
            .dark .flatpickr-time .flatpickr-am-pm {
                color: #4ade80 !important;
            }
            
            .flatpickr-time input:hover, .flatpickr-time .flatpickr-am-pm:hover, .flatpickr-time input:focus, .flatpickr-time .flatpickr-am-pm:focus {
                background: #f0f5f1 !important;
            }
            .dark .flatpickr-time input:hover, .dark .flatpickr-time .flatpickr-am-pm:hover, .dark .flatpickr-time input:focus, .dark .flatpickr-time .flatpickr-am-pm:focus {
                background: rgba(255,255,255,0.05) !important;
            }

            /* Hide arrows on time input hover */
            .numInputWrapper span {
                visibility: hidden !important;
            }
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
                
                // Allow only numbers and colon
                el.addEventListener('input', function(e) {
                    let val = e.target.value.replace(/[^0-9:]/g, '');
                    
                    // Auto-insert colon after 2 digits
                    if(val.length === 2 && !val.includes(':')) {
                        val += ':';
                    }
                    
                    // Limit length
                    if(val.length > 5) val = val.substring(0, 5);
                    
                    e.target.value = val;
                });
                
                // Validate on blur
                el.addEventListener('blur', function(e) {
                     const val = e.target.value;
                     const timeRegex = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
                     
                     if(val && !timeRegex.test(val)) {
                         alert('Please enter a valid 24h time format (HH:MM)');
                         e.target.value = '';
                     }
                     calculateNGOPoints();
                });
            });
        });
        </script>

        <div>
            <label class="block text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em] mb-3">Points Reward (10 pts/hr)</label>
            <div class="flex items-center gap-4 bg-[#f0f5f1] dark:bg-white/5 rounded-2xl px-6 py-4">
                <span id="display_points" class="font-black text-2xl text-primary dark:text-[#4ade80]">0</span>
                <span class="text-xs font-bold text-[#677e6b] dark:text-gray-400 uppercase tracking-widest">Calculated Points</span>
            </div>
            <input type="hidden" name="points_reward" id="points_reward" value="0">
        </div>
      </div>
    </div>

    <div class="pt-8 border-t border-gray-50 dark:border-white/5 flex items-center justify-between gap-6">
        <p class="text-xs text-[#677e6b] dark:text-gray-400 font-medium max-w-sm">
            Once created, this drive will appear on the global map and volunteer feed instantly.
        </p>
        <button type="submit" 
          class="h-16 px-12 rounded-2xl bg-[#2c4931] dark:bg-[#4ade80] text-white dark:text-primary text-sm font-black uppercase tracking-widest hover:bg-[#121613] dark:hover:bg-[#22c55e] transition-all shadow-xl shadow-[#2c4931]/20 dark:shadow-[#4ade80]/20 active:scale-95 flex items-center justify-center gap-2">
          Publicize Impact Drive <i data-lucide="send" class="w-5 h-5"></i>
        </button>
    </div>
  </form>
</div>

<script>
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
    } else {
        displaySpan.innerText = '0';
        hiddenInput.value = '0';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const startInput = document.querySelector('input[name="start_time"]');
    const endInput = document.querySelector('input[name="end_time"]');
    
    if (startInput && endInput) {
        startInput.addEventListener('change', calculateNGOPoints);
        endInput.addEventListener('change', calculateNGOPoints);
        calculateNGOPoints();
    }

    // --- MAP LOGIC ---
    let map, marker;
    
    // Default: Sri Lanka Center
    const defaultLat = 7.8731;
    const defaultLng = 80.7718;
    
    // Check for existing values
    let currentLat = parseFloat(document.getElementById('latitude').value) || defaultLat;
    let currentLng = parseFloat(document.getElementById('longitude').value) || defaultLng;
    let initialZoom = document.getElementById('latitude').value ? 13 : 7;

    map = L.map('map_picker', { scrollWheelZoom: false }).setView([currentLat, currentLng], initialZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Helper to create the custom icon
    function getGreenIcon() {
        return L.divIcon({
            html: `<div class="w-8 h-8 bg-[#2c4931] rounded-full border-4 border-white shadow-lg flex items-center justify-center text-white ring-4 ring-[#2c4931]/20"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></div>`,
            className: 'bg-transparent',
            iconSize: [32, 32],
            iconAnchor: [16, 32]
        });
    }

    function updateMarker(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { icon: getGreenIcon() }).addTo(map);
        }
        
        // Update fields
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        document.getElementById('coords_display').innerText = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
    }

    // Place initial marker if we have data
    if (document.getElementById('latitude').value) {
        updateMarker(currentLat, currentLng);
    }

    // Click to pin
    map.on('click', function(e) {
        updateMarker(e.latlng.lat, e.latlng.lng);
    });
    
    // Search function exposure
    window.searchLocation = async function() {
        const query = document.getElementById('location_search').value;
        if (!query) return;
        
        const btn = document.querySelector('button[onclick="searchLocation()"]');
        const originalText = btn.innerText;
        btn.innerText = '...';
        btn.disabled = true;

        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=lk`);
            const data = await response.json();

            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                
                map.flyTo([lat, lon], 14); // Zoom in closer
                updateMarker(lat, lon);
            } else {
                alert('Location not found. Try a broader search term.');
            }
        } catch (e) {
            alert('Error searching location.');
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    };
});
</script>