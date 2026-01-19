<?php
// app/views/ngo/project_new.php
$proposalData = $_SESSION['adopting_proposal_data'] ?? null;
?>

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
            <select name="category" required class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] font-bold text-[#121613] dark:text-white transition-all cursor-pointer">
                <option value="Beach & Coastal Cleanups">Beach & Coastal Cleanups</option>
                <option value="Waterway & Wetland Cleanups">Waterway & Wetland Cleanups</option>
                <option value="Park & Forest Cleanups">Park & Forest Cleanups</option>
                <option value="Urban & Street Cleanups">Urban & Street Cleanups</option>
                <option value="Underwater/Dive Cleanups">Underwater/Dive Cleanups</option>
                <option value="Tree Planting & Reforestation">Tree Planting & Reforestation</option>
                <option value="General Cleanup" selected>General Cleanup</option>
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

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-black text-[#121613] dark:text-white uppercase tracking-widest mb-2">Latitude</label>
                <input type="text" name="latitude" required placeholder="6.1234"
                    value="<?php echo h($proposalData['latitude'] ?? ''); ?>"
                    class="w-full px-4 py-3 rounded-xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] text-xs font-bold text-[#121613] dark:text-white transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-black text-[#121613] dark:text-white uppercase tracking-widest mb-2">Longitude</label>
                <input type="text" name="longitude" required placeholder="79.1234"
                    value="<?php echo h($proposalData['longitude'] ?? ''); ?>"
                    class="w-full px-4 py-3 rounded-xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-[#2c4931] dark:focus:ring-[#4ade80] text-xs font-bold text-[#121613] dark:text-white transition-all">
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
});
</script>