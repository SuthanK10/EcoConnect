<h2 class="text-[22px] font-bold text-[#121613] dark:text-white mb-3 px-4">
  Explore Cleanup Drives
</h2>

<p class="text-sm text-[#677e6b] dark:text-gray-400 px-4 mb-4">
  Find and join cleanup drives happening across Sri Lanka.
</p>

<div id="map" class="mx-4 mb-6 h-[600px] rounded-3xl shadow-lg border border-gray-200 dark:border-white/10 z-0"></div>

<style>
  /* Remove default Leaflet popup styling */
  .leaflet-popup-content-wrapper {
    padding: 0 !important;
    background: transparent !important;
    box-shadow: none !important;
    border-radius: 16px !important;
  }
  .leaflet-popup-content {
    margin: 0 !important;
    padding: 0 !important;
    width: auto !important;
  }
  .leaflet-popup-tip-container {
      display: none !important;
  }
  .leaflet-container a.leaflet-popup-close-button {
      color: white !important;
      font-size: 20px !important;
      padding: 12px 12px 0 0 !important;
      z-index: 20;
  }
</style>

<!-- Leaflet CSS -->
<link
  rel="stylesheet"
  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
  // Initialize map (Sri Lanka center) with scrollWheelZoom disabled
  const map = L.map('map', {
    scrollWheelZoom: false,
    dragging: !L.Browser.mobile, // optional: disable dragging on mobile unless specified
    tap: !L.Browser.mobile
  }).setView([7.8731, 80.7718], 7);

  // Enable scroll on click
  map.on('click', function() {
    if (!map.scrollWheelZoom.enabled()) {
      map.scrollWheelZoom.enable();
    }
  });

  // OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // Initialize Lucide icons on popup open
  map.on('popupopen', function() {
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  });

  // Drives data from backend
  const drives = <?php echo json_encode($projects); ?>;

  // Custom Green Marker Icon
  const greenIcon = L.divIcon({
    html: `<div class="w-8 h-8 bg-[#2c4931] rounded-full border-4 border-white shadow-lg flex items-center justify-center text-white ring-4 ring-[#2c4931]/20"><i data-lucide="map-pin" class="w-4 h-4"></i></div>`,
    className: 'bg-transparent',
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -32]
  });

  drives.forEach(drive => {
    const marker = L.marker([
      parseFloat(drive.latitude),
      parseFloat(drive.longitude)
    ], { icon: greenIcon }).addTo(map);

    const imageUrl = drive.image_path ? drive.image_path : 'assets/default-event.jpg';

    // ✨ ULTRA-PREMIUM DISCOVERY CARD
    const popupContent = `
      <div class="rounded-[32px] bg-white overflow-hidden shadow-[0_20px_60px_rgba(0,0,0,0.2)] w-[320px] border border-gray-100">
        <!-- Image Header -->
        <div class="relative h-44 overflow-hidden">
          <img src="${imageUrl}" class="w-full h-full object-cover" alt="${drive.title}">
          <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
          
          <!-- NGO Badge -->
          <div class="absolute bottom-5 left-5 right-5">
             <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1 rounded-full border border-white/20 mb-2">
                <span class="w-2 h-2 rounded-full bg-[#4ade80] shadow-[0_0_8px_#4ade80]"></span>
                <span class="text-[10px] font-black text-white uppercase tracking-[0.1em]">${drive.ngo_name}</span>
             </div>
             <h3 class="text-white text-xl font-black leading-tight tracking-tight">${drive.title}</h3>
          </div>
        </div>

        <!-- Info Grid -->
        <div class="p-7">
          <div class="space-y-6">
            <!-- Location -->
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 min-w-[40px] rounded-2xl bg-[#f0f5f1] flex items-center justify-center text-primary shadow-sm">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
              </div>
              <div class="flex-1">
                <p class="text-[10px] font-black text-[#2c4931]/60 uppercase tracking-[0.2em] mb-1">Location</p>
                <p class="text-[15px] font-bold text-[#121613] leading-snug">${drive.location}</p>
              </div>
            </div>

            <!-- Double Row for Time & Date -->
            <div class="grid grid-cols-2 gap-4">
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 min-w-[40px] rounded-2xl bg-[#f0f5f1] flex items-center justify-center text-primary shadow-sm">
                  <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <div>
                  <p class="text-[10px] font-black text-[#2c4931]/60 uppercase tracking-[0.2em] mb-1">Date</p>
                  <p class="text-[14px] font-bold text-[#121613]">${drive.event_date.split('-').slice(1).join('/')}</p>
                </div>
              </div>
              
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 min-w-[40px] rounded-2xl bg-[#f0f5f1] flex items-center justify-center text-primary shadow-sm">
                  <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
                <div>
                  <p class="text-[10px] font-black text-[#2c4931]/60 uppercase tracking-[0.2em] mb-1">Time</p>
                  <p class="text-[14px] font-bold text-[#121613]">${drive.start_time.substring(0, 5)} AM</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Button -->
          <div class="mt-8">
            <a
              href="index.php?route=event_show&id=${drive.id}"
              style="color: white !important; text-decoration: none;"
              class="flex items-center justify-center w-full py-4 rounded-[22px] bg-[#2c4931] text-white text-sm font-black tracking-wide hover:bg-[#121613] transition-all duration-300 shadow-[0_8px_20px_rgba(44,73,49,0.25)] hover:shadow-[0_12px_25px_rgba(18,22,19,0.35)] transform hover:-translate-y-1 whitespace-nowrap"
            >
              Join the Movement
            </a>
          </div>
        </div>
      </div>
    `;

    marker.bindPopup(popupContent, {
        maxWidth: 320,
        minWidth: 320,
        className: 'eco-popup'
    });
  });
</script>
