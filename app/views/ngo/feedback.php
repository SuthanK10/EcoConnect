<?php
// app/views/ngo/feedback.php
?>

<div class="max-w-6xl mx-auto px-4 py-12">
    <!-- Back Header -->
    <div class="mb-10 flex items-center justify-between">
        <div>
            <h1 class="text-[#121613] dark:text-white text-3xl font-black tracking-tight mb-2">
                <?php echo $view_details ? 'Drive Feedback: ' . h($project_title) : 'Impact Feedback'; ?>
            </h1>
            <p class="text-sm text-[#677e6b] dark:text-gray-400">
                <?php echo $view_details ? 'Review specific volunteer experiences for this event.' : 'See what volunteers are saying about your cleanup drives.'; ?>
            </p>
        </div>
        <?php if ($view_details): ?>
            <a href="index.php?route=ngo_feedback" class="flex items-center gap-2 text-sm font-bold text-[#2c4931] dark:text-[#4ade80] hover:underline">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to all drives
            </a>
        <?php endif; ?>
    </div>

    <?php if ($view_details): ?>
        <!-- Specific Project Details -->
        <?php if (empty($feedbacks)): ?>
            <div class="text-center py-20 bg-white dark:bg-darkSurface rounded-[40px] border border-dashed border-gray-200 dark:border-white/10">
                <i data-lucide="info" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
                <h3 class="text-xl font-black text-[#121613] dark:text-white">No feedback yet</h3>
                <p class="text-[#677e6b] dark:text-gray-400">Encourage your volunteers to share their experience!</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 gap-6">
                <?php foreach ($feedbacks as $f): ?>
                    <div class="bg-white dark:bg-darkSurface rounded-[32px] border border-gray-100 dark:border-white/10 p-8 shadow-sm">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h4 class="text-lg font-black text-[#121613] dark:text-white"><?php echo h($f['user_name']); ?></h4>
                                        <p class="text-xs text-[#677e6b] dark:text-gray-400"><?php echo h($f['user_email']); ?></p>
                                    </div>
                                    <div class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">
                                        <?php echo date('M d, Y', strtotime($f['created_at'])); ?>
                                    </div>
                                </div>
                                
                                <div class="flex gap-6 mb-6">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[10px] font-black text-primary uppercase tracking-widest">Event Rating</span>
                                        <div class="flex text-yellow-400">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i data-lucide="star" class="w-4 h-4 <?php echo $i <= $f['event_rating'] ? 'fill-current' : ''; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>

                                </div>

                                <div class="bg-gray-50 dark:bg-white/5 rounded-2xl p-6 italic text-[#121613] dark:text-gray-200">
                                    "<?php echo nl2br(h($f['comments'])); ?>"
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- List of all projects with feedback -->
        <?php if (empty($projects_feedback)): ?>
            <div class="text-center py-24 bg-white dark:bg-darkSurface rounded-[40px] border border-dashed border-gray-200 dark:border-white/10">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center rounded-full bg-gray-50 dark:bg-white/5 text-gray-300">
                    <i data-lucide="message-square-off" class="w-10 h-10"></i>
                </div>
                <h3 class="text-xl font-black text-[#121613] dark:text-white mb-2">No feedback records found</h3>
                <p class="text-[#677e6b] dark:text-gray-400">Feedback will appear here once volunteers complete your drives.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($projects_feedback as $p): ?>
                    <div class="group bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/10 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 flex flex-col">
                        <!-- Drive Image Header -->
                        <div class="relative h-48 overflow-hidden">
                            <img src="<?php echo $p['image_path'] ? BASE_URL . '/' . $p['image_path'] : 'https://images.unsplash.com/photo-1618477388954-7852f32655ec?auto=format&fit=crop&q=80&w=800'; ?>" 
                                 alt="<?php echo h($p['title']); ?>" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            
                            <!-- Rating Overlay -->
                            <div class="absolute top-4 right-4 bg-white/95 dark:bg-darkSurface/95 backdrop-blur-sm px-3 py-1.5 rounded-2xl flex items-center gap-1.5 shadow-xl">
                                <i data-lucide="star" class="w-4 h-4 text-yellow-400 fill-current"></i>
                                <span class="text-sm font-black text-[#121613] dark:text-white"><?php echo number_format($p['avg_rating'], 1); ?></span>
                            </div>

                            <!-- Feedback Count Overlay -->
                            <div class="absolute bottom-4 left-4 flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full bg-primary/90 text-white text-[10px] font-black uppercase tracking-widest backdrop-blur-sm">
                                    <?php echo $p['feedback_count']; ?> Responses
                                </span>
                            </div>
                        </div>

                        <div class="p-8 flex-1 flex flex-col">
                            <h3 class="text-xl font-black text-[#121613] dark:text-white mb-4 group-hover:text-primary transition-colors leading-tight">
                                <?php echo h($p['title']); ?>
                            </h3>
                            
                            <div class="flex flex-col gap-3 mb-8">
                                <span class="text-xs text-[#677e6b] dark:text-gray-400 font-bold flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80]">
                                        <i data-lucide="calendar" class="w-4 h-4"></i>
                                    </span>
                                    <?php echo date('M d, Y', strtotime($p['event_date'])); ?>
                                </span>
                                <span class="text-xs text-[#677e6b] dark:text-gray-400 font-bold flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80]">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                                    </span>
                                    <?php echo h($p['location']); ?>
                                </span>
                            </div>

                            <div class="mt-auto">
                                <a href="index.php?route=ngo_feedback&project_id=<?php echo $p['id']; ?>" class="w-full py-4 rounded-2xl bg-[#ebefec] dark:bg-white/5 text-[#2c4931] dark:text-white text-xs font-black uppercase text-center hover:bg-primary hover:text-white transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                                    Analyze Impact
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
