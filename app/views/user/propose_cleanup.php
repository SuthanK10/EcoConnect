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
                    <input type="date" name="date" required 
                        class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-darkBg border-none focus:ring-2 focus:ring-[#2c4931] font-bold text-[#121613] dark:text-white">
                </div>
            </div>

            <!-- Right Side: Description & Coordinates -->
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-black text-[#121613] dark:text-gray-200 uppercase tracking-[0.2em] mb-3">Description</label>
                    <textarea name="description" rows="4" placeholder="Tell us about the area and why it needs cleaning..." 
                        class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-darkBg border-none focus:ring-2 focus:ring-[#2c4931] placeholder:text-gray-400 font-bold text-[#121613] dark:text-white"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-[#121613] dark:text-gray-200 uppercase tracking-widest mb-2">Latitude (Optional)</label>
                        <input type="number" step="any" name="latitude" id="lat" placeholder="6.1234" 
                            class="w-full px-4 py-3 rounded-xl bg-[#f0f5f1] dark:bg-darkBg border-none focus:ring-2 focus:ring-[#2c4931] text-xs font-bold text-[#121613] dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-[#121613] dark:text-gray-200 uppercase tracking-widest mb-2">Longitude (Optional)</label>
                        <input type="number" step="any" name="longitude" id="lng" placeholder="80.1234" 
                            class="w-full px-4 py-3 rounded-xl bg-[#f0f5f1] dark:bg-darkBg border-none focus:ring-2 focus:ring-[#2c4931] text-xs font-bold text-[#121613] dark:text-white">
                    </div>
                </div>

                <button type="button" onclick="getLocation()" 
                    class="w-full py-4 rounded-2xl bg-white dark:bg-white/5 border-2 border-dashed border-[#2c4931]/20 dark:border-white/10 text-[#2c4931] dark:text-[#4ade80] text-xs font-black uppercase tracking-widest hover:border-[#2c4931] dark:hover:border-[#4ade80] transition-all flex items-center justify-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4"></i> Use Current Location
                </button>
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

<script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('lng').value = position.coords.longitude;
            alert('Location captured successfully!');
        }, error => {
            alert('Error getting location: ' + error.message);
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}
</script>
