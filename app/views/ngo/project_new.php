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

        <div>
            <label class="block text-xs font-black text-[#121613] dark:text-white uppercase tracking-[0.2em] mb-3">Drive Category</label>
            <select name="category" required class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-[#1e293b] border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] font-bold text-[#121613] dark:text-white transition-all cursor-pointer">
                <option value="Beach & Coastal Cleanups" class="dark:bg-[#1e293b] dark:text-white">Beach & Coastal Cleanups</option>
                <option value="Waterway & Wetland Cleanups" class="dark:bg-[#1e293b] dark:text-white">Waterway & Wetland Cleanups</option>
                <option value="Park & Forest Cleanups" class="dark:bg-[#1e293b] dark:text-white">Park & Forest Cleanups</option>
                <option value="Urban & Street Cleanups" class="dark:bg-[#1e293b] dark:text-white">Urban & Street Cleanups</option>
                <option value="Underwater/Dive Cleanups" class="dark:bg-[#1e293b] dark:text-white">Underwater/Dive Cleanups</option>
                <option value="Tree Planting & Reforestation" class="dark:bg-[#1e293b] dark:text-white">Tree Planting & Reforestation</option>
                <option value="General Cleanup" selected class="dark:bg-[#1e293b] dark:text-white">General Cleanup</option>
            </select>
        </div>

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
              <input type="date" name="event_date" required 
                value="<?php echo h($proposalData['proposed_date'] ?? ''); ?>"
                class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] font-bold text-[#121613] dark:text-white transition-all">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-[10px] font-black text-[#121613] dark:text-white uppercase tracking-widest mb-2">Start Time</label>
                  <input type="time" name="start_time" required class="w-full px-4 py-3 rounded-xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] font-bold text-[#121613] dark:text-white transition-all">
                </div>
                <div>
                  <label class="block text-[10px] font-black text-[#121613] dark:text-white uppercase tracking-widest mb-2">End Time</label>
                  <input type="time" name="end_time" required class="w-full px-4 py-3 rounded-xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] font-bold text-[#121613] dark:text-white transition-all">
                </div>
            </div>
        </div>

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