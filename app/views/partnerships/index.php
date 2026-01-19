<?php
// app/views/partnerships/index.php
?>

<div class="max-w-7xl mx-auto px-6 py-12">
  <!-- Page Header -->
  <div class="text-center mb-16">
    <h1 class="text-[#121613] dark:text-white text-4xl md:text-5xl font-black tracking-tight mb-4">Our Eco-System</h1>
    <p class="text-lg text-[#677e6b] dark:text-gray-400 max-w-2xl mx-auto font-medium">
      Eco-Connect is powered by a network of dedicated NGOs and corporate partners working together for a cleaner Sri Lanka.
    </p>
  </div>

  <!-- Sections: NGOs -->
  <div class="mb-20">
    <div class="flex items-center gap-4 mb-10">
      <div class="h-px flex-1 bg-gray-100 dark:bg-white/5"></div>
      <h2 class="text-xs font-black text-primary dark:text-[#4ade80] uppercase tracking-[0.3em]">Verified Organizations</h2>
      <div class="h-px flex-1 bg-gray-100 dark:bg-white/5"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
      <?php if (!empty($ngos)): ?>
        <?php foreach ($ngos as $ngo): ?>
          <a href="index.php?route=partner_show&type=ngo&id=<?php echo $ngo['id']; ?>" class="group flex flex-col bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 p-8 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
            
            <div class="flex flex-col items-center mb-8">
              <div class="relative">
                <?php if (!empty($ngo['logo_path'])): ?>
                  <div class="h-40 w-40 bg-white dark:bg-darkBg rounded-[32px] flex items-center justify-center p-6 shadow-inner border border-gray-50 dark:border-white/5 overflow-hidden group-hover:scale-105 transition-transform duration-500">
                    <img src="<?php echo h($ngo['logo_path']); ?>" alt="<?php echo h($ngo['name']); ?>" class="max-h-full max-w-full object-contain">
                  </div>
                <?php else: ?>
                  <div class="h-40 w-40 bg-[#f0f5f1] dark:bg-white/5 rounded-[32px] flex items-center justify-center text-5xl font-black text-[#2c4931] dark:text-[#4ade80] shadow-inner">
                    <?php echo strtoupper(substr($ngo['name'], 0, 1)); ?>
                  </div>
                <?php endif; ?>
                
                <div class="absolute -bottom-2 -right-2 bg-[#2c4931] dark:bg-[#4ade80] text-white dark:text-primary p-2 rounded-2xl shadow-lg border-4 border-white dark:border-darkSurface">
                  <span class="text-[10px] font-black uppercase tracking-tighter">Verified NGO</span>
                </div>
              </div>
            </div>

            <div class="flex-1 text-center mb-8">
              <h3 class="text-2xl font-black text-[#121613] dark:text-white mb-4 group-hover:text-primary dark:group-hover:text-[#4ade80] transition-colors line-clamp-1">
                  <?php echo h($ngo['name']); ?>
              </h3>
              <p class="text-sm text-[#677e6b] dark:text-gray-400 leading-relaxed line-clamp-3 font-medium">
                <?php echo h($ngo['description'] ?? 'An environmental organization committed to organizing community-led cleanup projects across Sri Lanka.'); ?>
              </p>
            </div>

            <div class="mt-auto">
              <div class="flex items-center justify-center w-full py-4 rounded-2xl bg-primary/5 text-primary dark:text-[#4ade80] text-xs font-black uppercase tracking-widest group-hover:bg-primary group-hover:text-white transition-all gap-2">
                View Impact History <i data-lucide="chevron-right" class="w-4 h-4"></i>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="col-span-full text-center text-gray-400 py-10 italic">No registered NGOs yet.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Sections: Partners -->
  <?php if (!empty($partners)): ?>
  <div class="mb-20">
    <div class="flex items-center gap-4 mb-10">
      <div class="h-px flex-1 bg-gray-100 dark:bg-white/5"></div>
      <h2 class="text-xs font-black text-primary dark:text-[#4ade80] uppercase tracking-[0.3em]">Corporate & Brand Partners</h2>
      <div class="h-px flex-1 bg-gray-100 dark:bg-white/5"></div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <?php foreach ($partners as $partner): ?>
            <a href="index.php?route=partner_show&type=partner&id=<?php echo $partner['id']; ?>" class="bg-white dark:bg-darkSurface p-6 rounded-3xl border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-xl transition-all flex items-center justify-center group">
                <?php if (!empty($partner['logo'])): ?>
                    <img src="<?php echo h($partner['logo']); ?>" alt="<?php echo h($partner['name']); ?>" class="max-h-12 w-auto grayscale group-hover:grayscale-0 transition-all duration-500">
                <?php else: ?>
                    <span class="font-black text-gray-300 dark:text-gray-600 uppercase tracking-widest"><?php echo h($partner['name']); ?></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Call to Action -->
  <div class="bg-primary dark:bg-darkSurface rounded-[40px] p-12 text-center text-white relative overflow-hidden shadow-2xl mt-20">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
    <div class="relative z-10">
        <h2 class="text-3xl font-black mb-4">Want to partner with us?</h2>
        <p class="text-white/60 mb-8 max-w-xl mx-auto font-medium">Join our mission to create a sustainable and clean future for Sri Lanka. We're always looking for dedicated organizations to join our network.</p>
        <div class="flex flex-col md:flex-row gap-4 justify-center">
            <a href="index.php?route=register" class="px-8 py-4 bg-[#4ade80] text-primary rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-white transition-all shadow-lg shadow-[#4ade80]/20">Join as NGO</a>
            <a href="mailto:partners@ecoconnect.lk" class="px-8 py-4 bg-white/10 border border-white/20 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-white/20 transition-all">Contact Us</a>
        </div>
    </div>
  </div>
</div>
