<?php
// app/views/ngo/profile_edit.php
?>

<div class="max-w-5xl mx-auto px-6 py-12">
    <!-- Header -->
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-[#121613] dark:text-white text-3xl font-black tracking-tight mb-2">Organization Profile</h2>
            <p class="text-[#677e6b] dark:text-gray-400 font-medium italic">General settings and identity of your NGO.</p>
        </div>
        <div class="flex items-center gap-3 px-4 py-2 bg-primary/5 rounded-2xl border border-primary/10">
            <span class="text-xs font-black text-primary dark:text-[#4ade80] uppercase tracking-widest">Status:</span>
            <span class="text-xs font-bold text-[#121613] dark:text-white uppercase"><?php echo h($ngo['status']); ?></span>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="mb-8 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-red-700 dark:text-red-400 text-sm font-bold flex items-center gap-3 animate-fade-in">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i> <?php echo h($error); ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <?php echo csrf_field(); ?>
        <!-- Left Column: Media & Quick Tips -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white dark:bg-darkSurface rounded-[40px] p-8 border border-gray-100 dark:border-white/5 shadow-sm text-center relative overflow-hidden group">
                <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <p class="text-[10px] font-black text-[#677e6b] dark:text-gray-500 uppercase tracking-[0.2em] mb-8 relative z-10">Brand Identity</p>
                
                <div class="relative mx-auto w-40 h-40 mb-8 relative z-10">
                    <div id="logoPreview" class="w-full h-full rounded-[32px] overflow-hidden border-4 border-gray-50 dark:border-darkBg shadow-2xl bg-white dark:bg-darkBg flex items-center justify-center p-4">
                        <?php if (!empty($ngo['logo_path'])): ?>
                            <img src="<?php echo h($ngo['logo_path']); ?>" alt="NGO Logo" class="max-w-full max-h-full object-contain">
                        <?php else: ?>
                            <div class="text-5xl font-black text-primary dark:text-[#4ade80]">
                                <?php echo strtoupper(substr($ngo['name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <label for="logoUpload" class="absolute -bottom-2 -right-2 w-12 h-12 bg-primary text-white rounded-2xl flex items-center justify-center cursor-pointer shadow-xl hover:scale-110 transition-transform border-4 border-white dark:border-darkSurface">
                        <i data-lucide="camera" class="w-5 h-5"></i>
                    </label>
                    <input type="file" name="ngo_logo" id="logoUpload" accept="image/*" class="hidden" onchange="previewLogo(this)">
                </div>
                
                <p id="fileName" class="text-[10px] text-primary dark:text-[#4ade80] font-black uppercase tracking-widest truncate max-w-full relative z-10 hidden"></p>
                <p class="text-[10px] text-[#677e6b] dark:text-gray-500 font-bold mt-2 relative z-10">Square JPG or PNG, max 2MB</p>
            </div>

            <div class="bg-primary/5 dark:bg-white/5 rounded-[32px] p-6 border border-primary/10">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary dark:text-[#4ade80]">
                        <i data-lucide="shield-check"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-primary dark:text-[#4ade80] uppercase tracking-wider mb-1">Verification</p>
                        <p class="text-[11px] text-[#677e6b] dark:text-gray-400 leading-relaxed font-medium">Your verification link is essential for maintain trust. Admins use this to re-verify your status during periodic audits.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Form Fields -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white dark:bg-darkSurface rounded-[40px] p-8 md:p-10 border border-gray-100 dark:border-white/5 shadow-sm space-y-8">
                <!-- Basic Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">NGO Name</label>
                        <input type="text" name="name" value="<?php echo h($ngo['name'] ?? ''); ?>" required class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium">
                    </div>

                    <div class="group">
                        <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Official Website</label>
                        <input type="url" name="website" value="<?php echo h($ngo['website'] ?? ''); ?>" placeholder="https://..." class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium">
                    </div>
                </div>

                <div class="group">
                    <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Verification Registry Link</label>
                    <input type="url" name="verification_link" value="<?php echo h($ngo['verification_link'] ?? ''); ?>" required placeholder="https://registry.gov/ngo/..." class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium">
                </div>

                <div class="group">
                    <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">WhatsApp Group Link</label>
                    <input type="url" name="whatsapp_link" value="<?php echo h($ngo['whatsapp_link'] ?? ''); ?>" placeholder="https://chat.whatsapp.com/..." class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium">
                    <p class="mt-2 text-[10px] text-[#677e6b] italic font-medium">Volunteers who register for your events will be invited to this group for coordination.</p>
                </div>

                <div class="group">
                    <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Public Description</label>
                    <textarea name="description" rows="6" placeholder="Describe your mission and impact..." class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none font-medium"><?php echo h($ngo['description'] ?? ''); ?></textarea>
                </div>

                <div class="pt-6 flex flex-col md:flex-row items-center gap-4 border-t border-gray-50 dark:border-white/5">
                    <button type="submit" class="w-full md:flex-1 py-5 rounded-2xl bg-primary text-white text-sm font-black uppercase tracking-[0.2em] shadow-xl hover:bg-[#121613] transition-all transform hover:scale-[1.02] active:scale-95 shadow-primary/20 flex items-center justify-center gap-2">
                        Update NGO Profile <i data-lucide="check" class="w-5 h-5"></i>
                    </button>
                    <a href="index.php?route=ngo_dashboard" class="w-full md:w-auto px-10 py-5 rounded-2xl bg-gray-50 dark:bg-white/5 text-[#677e6b] dark:text-gray-400 text-sm font-black uppercase tracking-widest hover:bg-gray-100 transition-all text-center">
                        Cancel
                    </a>
                </div>
            </div>
            
            <div class="p-8 rounded-[40px] bg-[#f0fdf4] dark:bg-green-500/5 border border-[#dcfce7] dark:border-green-500/10 flex items-center gap-6">
                <div class="text-primary dark:text-[#4ade80]">
                    <i data-lucide="leaf" class="w-10 h-10"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black text-[#2c4931] dark:text-[#4ade80] uppercase tracking-widest mb-1">Impact Transparency</h4>
                    <p class="text-[11px] text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed">Completing your profile allows volunteers to trust your organization more and increases participation in your events.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewLogo(input) {
    const preview = document.getElementById('logoPreview');
    const fileName = document.getElementById('fileName');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="max-w-full max-h-full object-contain">`;
            fileName.textContent = 'New selected: ' + input.files[0].name;
            fileName.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
