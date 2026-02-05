<?php
// app/views/events/show.php
?>

<?php
  // Event main image (uploaded by NGO)
  $eventImage = $project['image_path'] ?? 'assets/default-event.jpg';
?>

<div class="max-w-5xl mx-auto px-4 py-8">
  <!-- Hero Section -->
  <div class="relative rounded-[40px] overflow-hidden shadow-2xl mb-12 group">
    <div class="aspect-[21/9] w-full">
      <img
        src="<?php echo h($eventImage); ?>"
        alt="<?php echo h($project['title']); ?>"
        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
      />
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
    <div class="absolute bottom-10 left-10 right-10">
      <div class="flex flex-wrap gap-2 mb-4">
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20">
          <span class="w-2 h-2 rounded-full bg-[#4ade80] shadow-[0_0_8px_#4ade80]"></span>
          <span class="text-[11px] font-black text-white uppercase tracking-widest"><?php echo h($project['ngo_name']); ?></span>
        </div>
        <div class="inline-flex items-center gap-2 bg-blue-500/20 backdrop-blur-md px-4 py-1.5 rounded-full border border-blue-400/30">
          <span class="text-[11px] font-black text-blue-100 uppercase tracking-widest flex items-center gap-2">
            <i data-lucide="tag" class="w-3 h-3"></i> <?php echo h($project['category'] ?? 'General'); ?>
          </span>
        </div>
      </div>
      <h1 class="text-white text-4xl md:text-5xl font-black leading-tight tracking-tight drop-shadow-md">
        <?php echo h($project['title']); ?>
      </h1>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <!-- Left Column: Details -->
    <div class="lg:col-span-2 space-y-10">
      <!-- Quick Info Bar -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-[#f0f5f1] flex items-center justify-center text-primary">
            <i data-lucide="map-pin"></i>
          </div>
          <div>
            <p class="text-[10px] font-black text-[#677e6b] uppercase tracking-widest mb-0.5">Location</p>
            <p class="text-sm font-bold text-[#121613]"><?php echo h($project['location']); ?></p>
          </div>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-[#f0f5f1] flex items-center justify-center text-primary">
            <i data-lucide="calendar"></i>
          </div>
          <div>
            <p class="text-[10px] font-black text-[#677e6b] uppercase tracking-widest mb-0.5">Date</p>
            <p class="text-sm font-bold text-[#121613]"><?php echo h($project['event_date']); ?></p>
          </div>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-[#fef9c3] flex items-center justify-center text-[#854d0e]">
            <i data-lucide="trophy"></i>
          </div>
          <div>
            <p class="text-[10px] font-black text-[#854d0e] uppercase tracking-widest mb-0.5">Rewards</p>
            <p class="text-sm font-bold text-[#121613]"><?php echo (int)$project['points_reward']; ?> Points</p>
          </div>
        </div>
      </div>

      <!-- About this cleanup -->
      <section class="bg-white dark:bg-darkSurface rounded-3xl p-8 border border-gray-100 dark:border-white/5 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-[#2c4931]/5 rounded-full -mr-16 -mt-16"></div>
        <h3 class="text-xl font-black text-[#121613] dark:text-white mb-6 flex items-center gap-3">
          <span class="w-1.5 h-6 bg-[#2c4931] rounded-full"></span>
          About this cleanup
        </h3>
        <div class="text-[#677e6b] dark:text-gray-400 leading-relaxed space-y-4 font-medium italic">
          <?php echo nl2br(h($project['description'])); ?>
        </div>
      </section>

      <!-- Live Location Map -->
      <section class="bg-white dark:bg-darkSurface rounded-[40px] p-2 border border-gray-100 dark:border-white/5 shadow-lg overflow-hidden relative group">
        <div id="event-map" class="w-full h-96 rounded-[36px] z-0"></div>
        
        <!-- Map Overlay Info -->
        <div class="absolute bottom-6 left-6 right-6 z-10 pointer-events-none">
          <div class="bg-white/90 dark:bg-darkSurface/90 backdrop-blur-xl p-4 rounded-2xl shadow-xl border border-white/20 dark:border-white/5 inline-flex items-center gap-3">
             <div class="w-8 h-8 rounded-xl bg-[#2c4931] flex items-center justify-center text-white">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
             </div>
             <div>
               <p class="text-[10px] font-black text-[#677e6b] dark:text-gray-400 uppercase tracking-widest leading-none mb-1">Precise Location</p>
               <p class="text-xs font-bold text-[#121613] dark:text-white">Tap marker for directions</p>
             </div>
          </div>
        </div>

        <!-- Leaflet Integration -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
              const lat = <?php echo (float)($project['latitude'] ?? 0); ?>;
              const lng = <?php echo (float)($project['longitude'] ?? 0); ?>;
              
              if (lat && lng) {
                  const map = L.map('event-map', {
                      zoomControl: false,
                      attributionControl: false,
                      scrollWheelZoom: false
                  }).setView([lat, lng], 15);

                  // Enable scroll on click
                  map.on('click', function() {
                    if (!map.scrollWheelZoom.enabled()) {
                      map.scrollWheelZoom.enable();
                    }
                  });

                  const isDark = document.documentElement.classList.contains('dark');
                  const tileUrl = isDark 
                    ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
                    : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

                  L.tileLayer(tileUrl).addTo(map);

                  const leafIcon = L.divIcon({
                      html: `<div class="w-8 h-8 bg-[#2c4931] rounded-full border-4 border-white shadow-lg flex items-center justify-center text-white ring-4 ring-[#2c4931]/10 animate-pulse"><i data-lucide="leaf" class="w-4 h-4"></i></div>`,
                      className: 'bg-transparent',
                      iconSize: [32, 32],
                      iconAnchor: [16, 16]
                  });

                  L.marker([lat, lng], { icon: leafIcon })
                   .addTo(map)
                   .bindPopup(`<div class="p-2 font-bold text-xs text-[#2c4931]">${<?php echo json_encode($project['location']); ?>}</div>`);
                  
                  L.control.zoom({ position: 'topright' }).addTo(map);
              } else {
                  document.getElementById('event-map').innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-center p-8 bg-gray-50 dark:bg-darkBg">
                        <i data-lucide="map-pin-off" class="w-10 h-10 text-gray-300"></i>
                        <p class="text-sm font-bold text-gray-400 mt-4">Coordinates not provided.</p>
                    </div>
                  `;
              }
          });
        </script>
      </section>

      <!-- Volunteer Discussion -->
      <section class="bg-white dark:bg-darkSurface rounded-3xl p-8 border border-gray-100 dark:border-white/5 shadow-sm">
        <h3 class="text-xl font-black text-[#121613] dark:text-white mb-8 flex items-center justify-between">
          <span class="flex items-center gap-3">
            <span class="w-1.5 h-6 bg-[#2c4931] rounded-full"></span>
            Volunteer Discussion
          </span>
          <span class="text-xs font-bold bg-[#f0f5f1] dark:bg-darkBg text-[#2c4931] dark:text-[#4ade80] px-3 py-1 rounded-full uppercase tracking-tighter">
            <?php echo count($comments ?? []); ?> Comments
          </span>
        </h3>

        <?php if (!empty($comments_error)): ?>
          <div class="mb-6 rounded-2xl bg-red-50 dark:bg-red-900/10 px-6 py-4 text-sm text-red-600 dark:text-red-400 border border-red-100">
            <?php echo h($comments_error); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($comments)): ?>
          <div class="space-y-6 mb-8">
            <?php foreach ($comments as $comment): ?>
              <div class="flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-darkBg flex-shrink-0 flex items-center justify-center font-bold text-[#2c4931] dark:text-[#4ade80]">
                  <?php echo strtoupper(substr($comment['user_name'], 0, 1)); ?>
                </div>
                <div class="flex-1 bg-gray-50 dark:bg-darkBg rounded-2xl p-4">
                  <div class="flex justify-between items-center mb-2">
                    <p class="text-sm font-bold text-[#121613] dark:text-white"><?php echo h($comment['user_name']); ?></p>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                      <?php echo date('M d, Y', strtotime($comment['created_at'])); ?>
                    </span>
                  </div>
                  <p class="text-sm text-[#677e6b] dark:text-gray-400 leading-relaxed font-medium">
                    <?php echo nl2br(h($comment['comment_text'])); ?>
                  </p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-12 bg-gray-50 dark:bg-darkBg rounded-3xl border border-dashed border-gray-200 dark:border-white/10 mb-8">
            <p class="text-sm text-gray-400 italic">No comments yet. Be the first to start the conversation!</p>
          </div>
        <?php endif; ?>

        <?php if (is_logged_in()): ?>
          <form method="post" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div class="relative">
              <textarea
                name="comment_text"
                rows="4"
                class="w-full rounded-2xl border border-gray-100 dark:border-white/10 bg-gray-50 dark:bg-darkBg px-5 py-4 text-sm dark:text-white focus:ring-2 focus:ring-[#2c4931] focus:bg-white transition-all outline-none"
                placeholder="Share your thoughts..."
                required
              ><?php echo h($_POST['comment_text'] ?? ''); ?></textarea>
            </div>
            <div class="flex justify-end">
              <button
                type="submit"
                name="add_comment"
                class="inline-flex items-center justify-center rounded-xl bg-[#2c4931] px-8 py-3 text-sm font-bold text-white shadow-lg hover:bg-[#121613] transition-all transform hover:scale-[1.02]"
              >
                Post Comment
              </button>
            </div>
          </form>
        <?php else: ?>
          <div class="bg-[#f0f5f1] dark:bg-white/5 rounded-2xl p-6 text-center">
            <p class="text-sm font-bold text-[#2c4931] dark:text-[#4ade80]">
              Want to join the discussion? <a href="index.php?route=login" class="underline">Log in</a> to add a comment.
            </p>
          </div>
        <?php endif; ?>
      </section>
    </div>

    <!-- Right Column: NGO & Action -->
    <div class="space-y-8">
      <!-- Organizer Card -->
      <section class="bg-white dark:bg-darkSurface rounded-3xl p-8 border border-gray-100 dark:border-white/5 shadow-sm relative overflow-hidden ring-1 ring-gray-50 dark:ring-white/5">
        <h3 class="text-[10px] font-black text-[#121613] dark:text-gray-500 uppercase tracking-[0.15em] mb-6">
          Organized By
        </h3>
        
        <div class="flex items-center gap-4 mb-6">
          <div class="w-16 h-16 rounded-2xl bg-[#ebefec] dark:bg-darkBg flex items-center justify-center text-2xl shadow-inner font-black text-[#2c4931] dark:text-[#4ade80]">
            <?php echo strtoupper(substr($project['ngo_name'], 0, 1)); ?>
          </div>
          <div>
            <p class="text-lg font-black text-[#121613] dark:text-white leading-tight"><?php echo h($project['ngo_name']); ?></p>
            <p class="text-xs font-bold text-[#4ade80] uppercase tracking-wider mt-1">Verified NGO</p>
          </div>
        </div>

        <p class="text-sm text-[#677e6b] dark:text-gray-400 leading-relaxed mb-6 font-medium">
          <?php 
            $ngo_desc = $project['ngo_description'] ?? '';
            echo !empty($ngo_desc) ? nl2br(h($ngo_desc)) : 'This NGO is dedicated to keeping our environment clean and safe for everyone.';
          ?>
        </p>

        <a href="index.php?route=partnerships" class="inline-flex items-center gap-2 text-xs font-black text-[#2c4931] dark:text-[#4ade80] hover:underline uppercase tracking-widest">
          View all our partners <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </a>
      </section>

      <!-- Apply Card -->
      <section class="bg-[#2c4931] rounded-[32px] p-8 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
        
        <h3 class="text-xl font-bold text-white mb-4 relative z-10">Join the Impact</h3>
        <p class="text-white/70 text-sm mb-8 leading-relaxed relative z-10 font-medium">
          Join this cleanup drive and help us restore the beauty of <?php echo h($project['location']); ?>. Every hand counts!
        </p>

        <?php 
          $hasApplied = false;
          if (is_logged_in() && current_user_role() === 'user') {
              $hasApplied = va_user_has_applied($pdo, (int)$_SESSION['user_id'], (int)$project['id']);
          }
        ?>

        <?php if (!empty($apply_message)): ?>
          <div class="mb-6 rounded-2xl bg-white/10 backdrop-blur-md px-5 py-3 text-xs font-bold text-[#4ade80] border border-white/20 relative z-10 flex items-center gap-2">
            <i data-lucide="sparkles" class="w-4 h-4"></i> <?php echo h($apply_message); ?>
          </div>
        <?php endif; ?>

        <?php if ($hasApplied): ?>
          <div class="space-y-4 relative z-10">
            <div class="w-full py-4 rounded-2xl bg-[#4ade80]/20 border border-[#4ade80]/30 text-[#4ade80] text-sm font-black tracking-wide text-center flex items-center justify-center gap-2">
              <i data-lucide="check-circle-2" class="w-4 h-4"></i> You have already applied
            </div>

            <?php if (!empty($project['whatsapp_link'])): ?>
              <div class="bg-white/5 border border-white/10 rounded-2xl p-5 mb-4 group hover:border-[#25D366]/50 transition-all">
                <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-3">Community Coordination</p>
                <h4 class="text-white font-bold text-sm mb-4 leading-relaxed">Join the official WhatsApp group for real-time updates:</h4>
                <a href="<?php echo h($project['whatsapp_link']); ?>" target="_blank" class="flex items-center justify-center gap-3 w-full py-4 rounded-xl bg-[#25D366] text-white text-xs font-black uppercase tracking-widest hover:scale-[1.02] transition-all shadow-lg shadow-[#25D366]/20">
                  <i data-lucide="message-circle" class="w-5 h-5 text-white"></i>
                  Join Group Chat
                </a>
              </div>
            <?php endif; ?>
            
            <?php
              // Prepare Google Calendar Link
              $title = urlencode("Cleanup Drive: " . $project['title']);
              $location = urlencode($project['location']);
              $details = urlencode("Organized by: " . $project['ngo_name'] . "\n\n" . $project['description']);
              
              // Formatting dates (Google expects YYYYMMDDTHHMMSS)
              $dateStr = $project['event_date'];
              $startStr = $project['start_time'] ?: '08:00:00';
              $endStr = $project['end_time'] ?: '10:00:00';
              
              $startFull = str_replace('-', '', $dateStr) . 'T' . str_replace(':', '', $startStr);
              $endFull = str_replace('-', '', $dateStr) . 'T' . str_replace(':', '', $endStr);
              
              $gCalUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$startFull}/{$endFull}&details={$details}&location={$location}";
            ?>
            
            <a href="<?php echo $gCalUrl; ?>" target="_blank" class="flex items-center justify-center gap-3 w-full py-4 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-black uppercase tracking-widest transition-all">
              <i data-lucide="calendar-days" class="w-5 h-5 text-white"></i>
              Add to Google Calendar
            </a>

            <form method="post" id="cancelForm" class="mt-4 text-center">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="cancel_apply" value="1">
                <button type="button" onclick="showCancelModal()" class="text-xs font-bold text-red-300 hover:text-red-200 hover:underline transition-colors uppercase tracking-widest">
                    Cancel Registration
                </button>
            </form>
          </div>
        <?php else: ?>
          <form method="post" class="relative z-10">
            <?php echo csrf_field(); ?>
            <button
              type="submit"
              name="apply"
              class="w-full py-4 rounded-2xl bg-white text-[#2c4931] text-sm font-black tracking-wide hover:bg-gray-100 transition-all shadow-[0_10px_30px_rgba(0,0,0,0.2)] transform hover:-translate-y-1 flex items-center justify-center gap-2"
            >
              Apply to Volunteer <i data-lucide="user-plus" class="w-5 h-5"></i>
            </button>
          </form>
        <?php endif; ?>
      </section>
    </div>
  </div>
</div>

<!-- Custom Confirmation Modal -->
<div id="cancelModal" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="hideCancelModal()"></div>
    
    <!-- Modal Content -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-md bg-white dark:bg-darkSurface rounded-[32px] p-8 shadow-2xl border border-gray-100 dark:border-white/10 scale-95 transition-transform duration-300" id="modalContent">
        <div class="text-center">
            <div class="w-16 h-16 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center mx-auto mb-6 text-red-500">
                <i data-lucide="alert-circle" class="w-8 h-8"></i>
            </div>
            
            <h3 class="text-xl font-black text-[#121613] dark:text-white mb-2">Withdraw Application?</h3>
            <p class="text-sm text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed mb-8">
                Are you sure you want to cancel your registration for this cleanup drive? You can always re-apply later if spots are available.
            </p>
            
            <div class="flex items-center gap-3">
                <button onclick="hideCancelModal()" class="flex-1 py-3.5 rounded-xl bg-gray-100 dark:bg-white/5 text-[#121613] dark:text-gray-300 text-xs font-black uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                    Keep It
                </button>
                <button onclick="confirmCancel()" class="flex-1 py-3.5 rounded-xl bg-red-500 text-white text-xs font-black uppercase tracking-widest hover:bg-red-600 transition-colors shadow-lg shadow-red-500/30">
                    Yes, Withdraw
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showCancelModal() {
        const modal = document.getElementById('cancelModal');
        const content = document.getElementById('modalContent');
        modal.classList.remove('hidden');
        // Small delay to allow display:block to apply before opacity transition
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function hideCancelModal() {
        const modal = document.getElementById('cancelModal');
        const content = document.getElementById('modalContent');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function confirmCancel() {
        document.getElementById('cancelForm').submit();
    }
</script>
