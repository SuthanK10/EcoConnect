<?php
// app/views/user/edit_profile.php
?>

<div class="max-w-4xl mx-auto px-6 py-12">
    <!-- Header Area -->
    <div class="mb-12">
        <h2 class="text-[#121613] dark:text-white text-3xl font-black tracking-tight mb-2">Edit Your Profile</h2>
        <p class="text-[#677e6b] dark:text-gray-400 font-medium italic">Keep your information up to date to get the best experience on Eco-Connect.</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="mb-8 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-red-700 dark:text-red-400 text-sm font-bold flex items-center gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i> <?php echo h($error); ?>
        </div>
    <?php endif; ?>

    <form method="post" class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <?php echo csrf_field(); ?>
        <!-- Left Side: Basic Info -->
        <div class="lg:col-span-7 bg-white dark:bg-darkSurface rounded-[40px] p-8 md:p-10 border border-gray-100 dark:border-white/5 shadow-sm space-y-8">
            <div class="space-y-6">
                <div class="group">
                    <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Full Name</label>
                    <input type="text" name="name" value="<?php echo h($user['name'] ?? ''); ?>" required class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
                </div>

                <div class="group">
                    <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Email Address (Locked)</label>
                    <input type="email" value="<?php echo h($user['email'] ?? ''); ?>" disabled class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-100 dark:bg-white/5 text-gray-400 cursor-not-allowed font-medium">
                </div>

                <div class="group">
                    <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="<?php echo h($user['phone'] ?? ''); ?>" placeholder="07x xxxxxxx" class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
                </div>

                <div class="group">
                    <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">City / Area</label>
                    <input type="text" name="city" value="<?php echo h($user['city'] ?? ''); ?>" required class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-5 rounded-2xl bg-primary text-white text-sm font-black uppercase tracking-[0.2em] shadow-xl hover:bg-[#121613] transition-all transform hover:scale-[1.02] active:scale-95 shadow-primary/20 flex items-center justify-center gap-2">
                    Save Profile Changes <i data-lucide="check" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <!-- Right Side: Map Location -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white dark:bg-darkSurface rounded-[40px] p-8 border border-gray-100 dark:border-white/5 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xs font-black uppercase tracking-widest text-[#121613] dark:text-white">Your Location</h3>
                    <span class="px-3 py-1 bg-primary/5 text-primary dark:text-[#4ade80] text-[9px] font-black uppercase rounded-full">Map View</span>
                </div>
                
                <div id="editMap" class="h-64 w-full rounded-3xl border border-gray-100 dark:border-white/5 overflow-hidden mb-4 shadow-inner"></div>
                
                <p class="text-[10px] text-[#677e6b] dark:text-gray-400 font-bold leading-relaxed mb-4">
                    Click on the map to update your precise location. This helps us suggest cleanup drives near you.
                </p>

                <input type="hidden" name="latitude" id="lat" value="<?php echo h($user['latitude'] ?? ''); ?>">
                <input type="hidden" name="longitude" id="lng" value="<?php echo h($user['longitude'] ?? ''); ?>">
            </div>

            <div class="p-6 bg-[#f0fdf4] dark:bg-green-500/5 rounded-3xl border border-[#dcfce7] dark:border-green-500/10">
                <div class="flex items-start gap-4">
                    <span class="text-primary dark:text-[#4ade80]">
                        <i data-lucide="shield-check"></i>
                    </span>
                    <div>
                        <p class="text-xs font-black text-[#2c4931] dark:text-[#4ade80] uppercase tracking-wider mb-1">Privacy Guard</p>
                        <p class="text-[11px] text-[#677e6b] dark:text-gray-400 leading-relaxed font-medium">Your exact location is only used to calculate proximity to events and is never shared with third parties.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Map Scripts -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map;
    let marker;
    const initialLat = <?php echo !empty($user['latitude']) ? (float)$user['latitude'] : 7.8731; ?>;
    const initialLng = <?php echo !empty($user['longitude']) ? (float)$user['longitude'] : 80.7718; ?>;

    function initMap() {
        map = L.map('editMap', {
            scrollWheelZoom: false
        }).setView([initialLat, initialLng], <?php echo !empty($user['latitude']) ? 15 : 7; ?>);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        <?php if (!empty($user['latitude'])): ?>
            marker = L.marker([initialLat, initialLng]).addTo(map);
        <?php endif; ?>

        map.on('click', function(e) {
            const { lat, lng } = e.latlng;
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
        });

        // Enable scroll on click
        map.on('focus', () => { map.scrollWheelZoom.enable(); });
        map.on('blur', () => { map.scrollWheelZoom.disable(); });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMap);
    } else {
        initMap();
    }
</script>
