<?php
// app/views/gallery/index.php
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<style>
    .swiper-pagination-bullet-active { background: #4ade80 !important; }
    .swiper-button-next, .swiper-button-prev {
        background: rgba(0,0,0,0.3);
        width: 40px !important;
        height: 40px !important;
        border-radius: 50%;
        backdrop-filter: blur(4px);
    }
    .swiper-button-next:after, .swiper-button-prev:after { font-size: 14px !important; font-weight: 900; }
</style>

<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- Header -->
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-2 flex items-center gap-3">
                Eco-Action Feed <i data-lucide="camera" class="w-8 h-8 text-primary dark:text-[#4ade80]"></i>
            </h1>
            <p class="text-[17px] text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed">
                Celebrating real impact. Witness the collective power of our community as we clean and protect Sri Lanka.
            </p>
        </div>
        <?php if (is_logged_in()): ?>
            <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" 
                class="h-14 px-8 rounded-2xl bg-primary dark:bg-[#4ade80] dark:text-primary flex items-center justify-center text-sm font-black text-white hover:scale-95 transition-all shadow-xl shadow-primary/20 active:scale-90">
                Share Progress +
            </button>
        <?php endif; ?>
    </div>

    <!-- Feed -->
    <div class="space-y-10">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <!-- Post Header -->
                    <div class="p-6 md:p-8 flex items-center justify-between border-b border-gray-50 dark:border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-xl font-black text-primary dark:text-[#4ade80]">
                                <?php echo strtoupper(substr($post['ngo_name'] ?? $post['user_name'] ?? '?', 0, 1)); ?>
                            </div>
                            <div>
                                <h4 class="font-black text-[#121613] dark:text-white leading-none mb-1">
                                    <?php echo h($post['ngo_name'] ?? $post['user_name'] ?? 'Anonymous'); ?>
                                </h4>
                                <div class="flex items-center gap-2">
                                    <?php if ($post['ngo_name']): ?>
                                        <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20">NGO Partner</span>
                                    <?php else: ?>
                                        <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded bg-[#f0fdf4] dark:bg-green-500/10 text-primary dark:text-[#4ade80] border border-[#dcfce7] dark:border-green-500/20">Volunteer</span>
                                    <?php endif; ?>
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php if ($post['project_title']): ?>
                            <div class="hidden sm:block">
                                <span class="text-[10px] font-black text-[#677e6b] dark:text-gray-400 uppercase tracking-widest bg-gray-50 dark:bg-white/5 px-3 py-1.5 rounded-xl border border-gray-100 dark:border-white/5 flex items-center gap-2">
                                    <i data-lucide="leaf" class="w-3 h-3 text-primary dark:text-[#4ade80]"></i> <?php echo h($post['project_title']); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Post Body -->
                    <?php if (!empty($post['media'])): ?>
                        <div id="swiper-<?php echo $post['id']; ?>" class="swiper relative aspect-video bg-gray-100 dark:bg-black/20 overflow-hidden">
                            <div class="swiper-wrapper">
                                <?php foreach ($post['media'] as $item): ?>
                                    <div class="swiper-slide h-full w-full">
                                        <?php if ($item['media_type'] === 'video'): ?>
                                            <video src="<?php echo h($item['media_path']); ?>" controls class="w-full h-full object-cover"></video>
                                        <?php else: ?>
                                            <img src="<?php echo h($item['media_path']); ?>" alt="Impact photo" class="w-full h-full object-cover">
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($post['media']) > 1): ?>
                                <div class="swiper-pagination swiper-pagination-<?php echo $post['id']; ?>"></div>
                                <div class="swiper-button-next swiper-button-next-<?php echo $post['id']; ?> !text-white"></div>
                                <div class="swiper-button-prev swiper-button-prev-<?php echo $post['id']; ?> !text-white"></div>
                            <?php endif; ?>
                        </div>
                        <script>
                            (function() {
                                const initSwiper = () => {
                                    if (typeof Swiper !== 'undefined') {
                                        new Swiper('#swiper-<?php echo $post['id']; ?>', {
                                            pagination: { 
                                                el: '.swiper-pagination-<?php echo $post['id']; ?>', 
                                                clickable: true 
                                            },
                                            navigation: { 
                                                nextEl: '.swiper-button-next-<?php echo $post['id']; ?>', 
                                                prevEl: '.swiper-button-prev-<?php echo $post['id']; ?>' 
                                            },
                                            loop: <?php echo count($post['media']) > 1 ? 'true' : 'false'; ?>,
                                            observer: true,
                                            observeParents: true
                                        });
                                    } else {
                                        setTimeout(initSwiper, 100);
                                    }
                                };
                                initSwiper();
                            })();
                        </script>
                    <?php endif; ?>

                    <div class="p-8">
                        <p class="text-[17px] text-[#121613] dark:text-gray-200 leading-relaxed font-medium">
                            <?php echo nl2br(h($post['content'])); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-24 bg-white dark:bg-darkSurface rounded-[40px] border border-dashed border-gray-200 dark:border-white/10">
                <div class="text-6xl mb-6 font-bold flex justify-center">
                    <i data-lucide="image-off" class="w-16 h-16 text-gray-300"></i>
                </div>
                <h3 class="text-xl font-black text-[#121613] dark:text-white mb-2">No progress shared yet</h3>
                <p class="text-[#677e6b] dark:text-gray-400 mb-8 max-w-md mx-auto font-medium">Be the first to inspire others! Share a photo or video from your recent cleanup.</p>
                <?php if (is_logged_in()): ?>
                    <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" 
                        class="inline-flex rounded-2xl bg-primary dark:bg-[#4ade80] dark:text-primary px-8 py-4 text-sm font-black text-white hover:scale-105 transition-all shadow-lg shadow-primary/20">
                        Start the Trend
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 z-[100] hidden bg-[#121613]/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-darkSurface w-full max-w-xl rounded-[40px] overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300">
        <div class="p-8 border-b border-gray-50 dark:border-white/5 flex items-center justify-between">
            <h3 class="text-2xl font-black text-[#121613] dark:text-white">Share Progress</h3>
            <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-gray-400 hover:text-black dark:hover:text-white transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <form action="index.php?route=gallery_store" method="post" enctype="multipart/form-data" class="p-8 space-y-6">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
            
            <?php if ($project_title): ?>
                <div class="px-4 py-2 rounded-xl bg-primary/10 border border-primary/20 text-primary text-[10px] font-black uppercase tracking-widest flex items-center gap-2 mb-4">
                    <i data-lucide="leaf" class="w-3.5 h-3.5"></i> Posting for Event: <?php echo h($project_title); ?>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-xs font-black text-[#121613] dark:text-white/60 uppercase tracking-[0.2em] mb-3">What's the update?</label>
                <textarea name="content" rows="4" required placeholder="Tell the community about what you achieved..." 
                    class="w-full px-6 py-4 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 border-none focus:ring-2 focus:ring-primary dark:focus:ring-[#4ade80] placeholder:text-gray-400 font-bold text-[#121613] dark:text-white"></textarea>
            </div>

            <div>
                <label class="block text-xs font-black text-[#121613] dark:text-white/60 uppercase tracking-[0.2em] mb-3">Photos or Videos (Select Multiple)</label>
                <div class="relative group">
                    <input type="file" name="media[]" accept="image/*,video/*" multiple required
                        class="w-full px-6 py-8 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 border-2 border-dashed border-gray-200 dark:border-white/10 text-xs font-bold text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-primary dark:file:bg-[#4ade80] dark:file:text-primary file:text-white transition-all">
                </div>
                <p class="mt-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-relaxed flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-3 h-3 text-amber-400"></i> You can select up to 5 images/videos to create a slideshow.
                </p>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full h-16 rounded-2xl bg-primary dark:bg-[#4ade80] dark:text-primary text-white text-sm font-black uppercase tracking-widest hover:scale-[0.98] transition-all shadow-xl shadow-primary/20 active:scale-95 flex items-center justify-center gap-2">
                    Post to Feed <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<?php if ($project_id): ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('uploadModal').classList.remove('hidden');
    });
</script>
<?php endif; ?>
