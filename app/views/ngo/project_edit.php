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
                <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                    <div class="md:col-span-3 group">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Mission Title</label>
                        <input type="text" name="title" value="<?php echo h($project['title']); ?>" required class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium">
                    </div>
                    <div class="md:col-span-2 group">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Category</label>
                        <select name="category" required class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium">
                            <option value="Beach & Coastal Cleanups" <?php echo $project['category'] === 'Beach & Coastal Cleanups' ? 'selected' : ''; ?>>Beach & Coastal Cleanups</option>
                            <option value="Waterway & Wetland Cleanups" <?php echo $project['category'] === 'Waterway & Wetland Cleanups' ? 'selected' : ''; ?>>Waterway & Wetland Cleanups</option>
                            <option value="Park & Forest Cleanups" <?php echo $project['category'] === 'Park & Forest Cleanups' ? 'selected' : ''; ?>>Park & Forest Cleanups</option>
                            <option value="Urban & Street Cleanups" <?php echo $project['category'] === 'Urban & Street Cleanups' ? 'selected' : ''; ?>>Urban & Street Cleanups</option>
                            <option value="Underwater/Dive Cleanups" <?php echo $project['category'] === 'Underwater/Dive Cleanups' ? 'selected' : ''; ?>>Underwater/Dive Cleanups</option>
                            <option value="Tree Planting & Reforestation" <?php echo $project['category'] === 'Tree Planting & Reforestation' ? 'selected' : ''; ?>>Tree Planting & Reforestation</option>
                            <option value="General Cleanup" <?php echo $project['category'] === 'General Cleanup' ? 'selected' : ''; ?>>General Cleanup</option>
                        </select>
                    </div>
                    <div class="group">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Status</label>
                        <select name="status" class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium">
                            <option value="open" <?php echo $project['status'] === 'open' ? 'selected' : ''; ?>>Open</option>
                            <option value="closed" <?php echo $project['status'] === 'closed' ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>
                </div>

                <!-- Timing & Rewards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="group md:col-span-2">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Event Date</label>
                        <input type="date" name="event_date" value="<?php echo h($project['event_date']); ?>" required class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium">
                    </div>
                    <div class="group">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Start</label>
                        <input type="time" name="start_time" value="<?php echo h($project['start_time']); ?>" required class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium text-sm">
                    </div>
                    <div class="group">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">End</label>
                        <input type="time" name="end_time" value="<?php echo h($project['end_time']); ?>" required class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium text-sm">
                    </div>
                </div>

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
