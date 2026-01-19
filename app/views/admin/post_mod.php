<?php
// app/views/admin/post_mod.php
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<style>
    .swiper-pagination-bullet-active { background: #f59e0b !important; }
    .swiper-button-next:after, .swiper-button-prev:after { font-size: 14px !important; font-weight: 900; }
</style>

<div class="max-w-5xl mx-auto px-4 py-12">
    <div class="mb-12">
        <h2 class="text-[#121613] dark:text-white text-3xl font-black flex items-center gap-3">
            <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
            Content Moderation
        </h2>
        <p class="text-[17px] text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed mt-2">
            Review community moments before they appear in the public Eco-Action Feed.
        </p>
    </div>

    <?php if (empty($pendingPosts)): ?>
        <div class="bg-white dark:bg-darkSurface rounded-[40px] p-20 text-center border border-gray-100 dark:border-white/5 shadow-sm">
            <div class="text-6xl mb-6 text-emerald-500 font-bold flex justify-center">
                <i data-lucide="check-circle-2" class="w-20 h-20"></i>
            </div>
            <h3 class="text-2xl font-black text-[#121613] dark:text-white mb-2">Queue is Clear!</h3>
            <p class="text-[#677e6b] dark:text-gray-400 font-medium">There are no pending posts requiring review at this time.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($pendingPosts as $post): ?>
                <div class="bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 overflow-hidden shadow-sm flex flex-col">
                    <!-- Media Preview -->
                    <?php if (!empty($post['media'])): ?>
                        <div id="admin-swiper-<?php echo $post['id']; ?>" class="swiper relative aspect-video bg-slate-100 dark:bg-black/20 overflow-hidden">
                            <div class="swiper-wrapper">
                                <?php foreach ($post['media'] as $item): ?>
                                    <div class="swiper-slide h-full w-full">
                                        <?php if ($item['media_type'] === 'video'): ?>
                                            <video src="<?= h($item['media_path']) ?>" controls class="w-full h-full object-cover"></video>
                                        <?php else: ?>
                                            <img src="<?= h($item['media_path']) ?>" alt="Post media" class="w-full h-full object-cover">
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
                                const initAdminSwiper = () => {
                                    if (typeof Swiper !== 'undefined') {
                                        new Swiper('#admin-swiper-<?php echo $post['id']; ?>', {
                                            pagination: { 
                                                el: '.swiper-pagination-<?php echo $post['id']; ?>', 
                                                clickable: true 
                                            },
                                            navigation: { 
                                                nextEl: '.swiper-button-next-<?php echo $post['id']; ?>', 
                                                prevEl: '.swiper-button-prev-<?php echo $post['id']; ?>' 
                                            },
                                            observer: true,
                                            observeParents: true
                                        });
                                    } else {
                                        setTimeout(initAdminSwiper, 100);
                                    }
                                };
                                initAdminSwiper();
                            })();
                        </script>
                    <?php endif; ?>

                    <div class="p-8 flex-1 flex flex-col">
                        <!-- HeaderInfo -->
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-white/5 flex items-center justify-center font-black text-primary dark:text-[#4ade80]">
                                <?= strtoupper(substr($post['user_name'] ?? $post['ngo_name'] ?? '?', 0, 1)) ?>
                            </div>
                            <div>
                                <h4 class="font-black text-[#121613] dark:text-white leading-none mb-1"><?= h($post['user_name'] ?? $post['ngo_name'] ?? 'Anonymous') ?></h4>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest"><?= date('M d, Y', strtotime($post['created_at'])) ?></p>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="mb-8 flex-1">
                            <?php if ($post['project_title']): ?>
                                <p class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                                    <i data-lucide="leaf" class="w-3 h-3 text-[#4ade80]"></i> <?= h($post['project_title']) ?>
                                </p>
                            <?php endif; ?>
                            <p class="text-sm text-[#121613] dark:text-gray-200 leading-relaxed font-medium">
                                <?= nl2br(h($post['content'])) ?>
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="grid grid-cols-2 gap-3 pt-6 border-t border-gray-50 dark:border-white/5">
                            <form action="index.php?route=admin_handle_post" method="post">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="w-full py-4 rounded-2xl bg-[#2c4931] text-white text-[10px] font-black uppercase tracking-widest hover:bg-[#121613] transition-all transform active:scale-95 shadow-lg shadow-primary/20">
                                    Approve
                                </button>
                            </form>
                            <form action="index.php?route=admin_handle_post" method="post">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="w-full py-4 rounded-2xl bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all transform active:scale-95 border border-red-100">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
