<?php
// app/views/user/propose_cleanup.php
?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="mb-10">
        <h1 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-2 flex items-center gap-3">
            Propose a Local Cleanup <i data-lucide="leaf" class="w-8 h-8 text-[#4ade80]"></i>
        </h1>
        <p class="text-[17px] text-[#677e6b] dark:text-gray-300 font-medium leading-relaxed">
            Spot a place that needs some love? Propose a cleanup drive and we'll help connect you with NGOs and volunteers to make it happen.
        </p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="mb-8 rounded-3xl bg-red-50 border border-red-100 p-6 flex items-center gap-4 text-red-700 font-bold text-sm">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i> <?php echo h($error); ?>
        </div>
    <?php endif; ?>

    <form method="post" class="space-y-8 bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 p-8 md:p-12 shadow-sm">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Side: Basic Info -->
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-black text-[#121613] dark:text-gray-200 uppercase tracking-[0.2em] mb-3">Drive Title *</label>
                    <input type="text" name="title" required placeholder="e.g., Mount Lavinia Beach Sanctuary Clean" 
                        class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-darkBg border-none focus:ring-2 focus:ring-[#2c4931] placeholder:text-gray-400 font-bold text-[#121613] dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-black text-[#121613] dark:text-gray-200 uppercase tracking-[0.2em] mb-3">Location Name *</label>
                    <input type="text" name="location" required placeholder="e.g., Near the main rail station" 
                        class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-darkBg border-none focus:ring-2 focus:ring-[#2c4931] placeholder:text-gray-400 font-bold text-[#121613] dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-black text-[#121613] dark:text-gray-200 uppercase tracking-[0.2em] mb-3">Preferred Date *</label>
                    <div class="relative">
                        <input type="text" name="date" id="date_picker" required 
                            class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-darkBg border-none focus:ring-2 focus:ring-[#2c4931] font-bold text-[#121613] dark:text-white"
                            placeholder="Select Date">
                         <i data-lucide="calendar" class="absolute right-6 top-1/2 -translate-y-1/2 w-5 h-5 text-[#677e6b] pointer-events-none"></i>
                    </div>
                </div>

                <!-- Flatpickr Resources & Custom Styles -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                
                <style>
                    /* Premium Custom Flatpickr Theme */
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
                    flatpickr("#date_picker", {
                        dateFormat: "Y-m-d",
                        minDate: "today",
                        disableMobile: "true",
                        animate: true,
                        prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>',
                        nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>'
                    });
                });
                </script>
                
                 <div>
                    <label class="block text-xs font-black text-[#121613] dark:text-gray-200 uppercase tracking-[0.2em] mb-3">Description</label>
                    <textarea name="description" rows="4" placeholder="Tell us about the area and why it needs cleaning..." 
                        class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-darkBg border-none focus:ring-2 focus:ring-[#2c4931] placeholder:text-gray-400 font-bold text-[#121613] dark:text-white"></textarea>
                </div>
            </div>

            <!-- Right Side: Map -->
            <div class="space-y-6">
                <div>
                     <label class="block text-xs font-black text-[#121613] dark:text-gray-200 uppercase tracking-[0.2em] mb-3">Pin Location on Map *</label>
                     <p class="text-[10px] text-[#677e6b] dark:text-gray-400 mb-2">Search for a place or click anywhere on the map to set the cleanup location.</p>
                     
                     <!-- Search Bar -->
                     <div class="flex gap-2 mb-3">
                        <input type="text" id="mapSearchInput" placeholder="Search for a location..." 
                            class="flex-1 px-4 py-2 rounded-xl bg-[#f0f5f1] dark:bg-darkBg border-none focus:ring-2 focus:ring-[#2c4931] text-xs font-bold text-[#121613] dark:text-white">
                        <button type="button" onclick="searchLocation()" class="px-4 py-2 bg-[#2c4931] text-white rounded-xl text-xs font-black">Search</button>
                     </div>

                     <!-- Leaflet Map Container -->
                     <div id="map" class="w-full h-[300px] rounded-2xl border border-gray-200 dark:border-white/10 z-0"></div>
                     
                     <!-- Hidden fields for coordinates -->
                     <input type="hidden" name="latitude" id="lat">
                     <input type="hidden" name="longitude" id="lng">
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-gray-50 flex items-center justify-between gap-6">
            <p class="text-xs text-[#677e6b] font-medium max-w-sm">
                * Your proposal will be reviewed by administrators before becoming visible to NGOs.
            </p>
            <button type="submit" class="h-14 px-12 rounded-2xl bg-[#2c4931] text-white text-sm font-black uppercase tracking-widest hover:bg-[#121613] transition-all shadow-xl shadow-[#2c4931]/20 active:scale-95 flex items-center gap-2">
                Submit Proposal <i data-lucide="check" class="w-4 h-4"></i>
            </button>
        </div>
    </form>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    // Initialize map centered on Sri Lanka
    var map = L.map('map').setView([7.8731, 80.7718], 7);
    var marker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Click handler to set marker
    map.on('click', function(e) {
        setMarker(e.latlng.lat, e.latlng.lng);
    });

    function setMarker(lat, lng) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng]).addTo(map);
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
    }

    // Simple search functionality using Nominatim
    function searchLocation() {
        var query = document.getElementById('mapSearchInput').value;
        if (!query) return;

        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query + ', Sri Lanka'))
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    var lat = data[0].lat;
                    var lon = data[0].lon;
                    map.setView([lat, lon], 13);
                    setMarker(lat, lon);
                } else {
                    alert('Location not found within Sri Lanka.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while searching.');
            });
    }
</script>
