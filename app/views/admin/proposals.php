<?php
// app/views/admin/proposals.php
?>

<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="mb-12 flex items-center justify-between">
        <div>
            <h1 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-2">Community Proposals</h1>
            <p class="text-[15px] text-[#677e6b] dark:text-gray-400 font-medium leading-relaxed">
                Review local cleanup initiatives suggested by the volunteer community.
            </p>
        </div>
        <a href="index.php?route=admin_dashboard" class="text-sm font-bold text-[#677e6b] hover:text-[#2c4931] dark:hover:text-[#4ade80] transition-colors flex items-center gap-2">
            <i data-lucide="chevron-left" class="w-4 h-4"></i> Back to Command Center
        </a>
    </div>

    <?php if (!empty($proposals)): ?>
        <div class="space-y-6">
            <?php foreach ($proposals as $prop): ?>
                <?php 
                    $status = strtolower($prop['status']);
                    $isPending = ($status === 'pending');
                    $statusConfig = [
                        'pending' => ['bg' => 'bg-orange-50 dark:bg-orange-500/10', 'text' => 'text-orange-600 dark:text-orange-400', 'icon' => '<i data-lucide="clock" class="w-3 h-3"></i>'],
                        'approved' => ['bg' => 'bg-green-50 dark:bg-green-500/10', 'text' => 'text-green-600 dark:text-green-400', 'icon' => '<i data-lucide="check-circle-2" class="w-3 h-3"></i>'],
                        'rejected' => ['bg' => 'bg-red-50 dark:bg-red-500/10', 'text' => 'text-red-600 dark:text-red-400', 'icon' => '<i data-lucide="x-circle" class="w-3 h-3"></i>'],
                        'converted' => ['bg' => 'bg-blue-50 dark:bg-blue-500/10', 'text' => 'text-blue-600 dark:text-blue-400', 'icon' => '<i data-lucide="shield-check" class="w-3 h-3"></i>']
                    ][$status] ?? ['bg' => 'bg-gray-50 dark:bg-white/5', 'text' => 'text-gray-600 dark:text-gray-400', 'icon' => '<i data-lucide="help-circle" class="w-3 h-3"></i>'];
                ?>
                <div class="group bg-white dark:bg-darkSurface rounded-[40px] border border-gray-100 dark:border-white/5 p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="flex flex-col lg:flex-row justify-between gap-8">
                        
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-6">
                                <span class="px-3 py-1 rounded-full <?php echo $statusConfig['bg']; ?> <?php echo $statusConfig['text']; ?> text-[10px] font-black uppercase tracking-widest border border-black/5 flex items-center gap-1.5">
                                    <?php echo $statusConfig['icon']; ?> <?php echo $prop['status']; ?>
                                </span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    Submitted by <?php echo h($prop['user_name']); ?> • <?php echo date('M d, Y', strtotime($prop['created_at'])); ?>
                                </span>
                            </div>

                            <h3 class="text-2xl font-black text-[#121613] dark:text-white mb-4 group-hover:text-[#2c4931] dark:group-hover:text-[#4ade80] transition-colors leading-tight">
                                <?php echo h($prop['title']); ?>
                            </h3>

                            <div class="flex flex-wrap gap-6 mb-8 text-sm font-bold text-[#677e6b] dark:text-gray-400">
                                <div class="flex items-center gap-2">
                                    <span class="w-10 h-10 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80]">
                                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                                    </span>
                                    <?php echo h($prop['location']); ?>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-10 h-10 rounded-2xl bg-[#f0f5f1] dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80]">
                                        <i data-lucide="calendar" class="w-5 h-5"></i>
                                    </span>
                                    Suggested: <?php echo date('M d, Y', strtotime($prop['proposed_date'])); ?>
                                </div>
                            </div>

                            <div class="bg-[#f0f5f1] dark:bg-white/5 rounded-[32px] p-8 border border-[#2c4931]/5 dark:border-white/5 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-[#2c4931]/5 dark:bg-[#4ade80]/5 -mr-10 -mt-10 rounded-full"></div>
                                <p class="text-[10px] font-black text-[#2c4931] dark:text-[#4ade80] uppercase tracking-[0.2em] mb-3">Community Proposal Note</p>
                                <p class="text-[15px] text-[#2c4931] dark:text-gray-300 leading-relaxed italic font-medium">
                                    "<?php echo h($prop['description']); ?>"
                                </p>
                            </div>
                        </div>

                        <?php if ($isPending): ?>
                            <div class="flex flex-row lg:flex-col gap-3 min-w-[200px]">
                                <form action="index.php?route=admin_handle_proposal" method="post" class="w-full">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="proposal_id" value="<?php echo $prop['id']; ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="w-full py-5 px-8 rounded-2xl bg-[#2c4931] dark:bg-[#4ade80] text-white dark:text-primary text-xs font-black uppercase tracking-widest hover:shadow-lg active:scale-95 transition-all">
                                        Approve & List
                                    </button>
                                </form>
                                <form action="index.php?route=admin_handle_proposal" method="post" class="w-full">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="proposal_id" value="<?php echo $prop['id']; ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="w-full py-5 px-8 rounded-2xl bg-white dark:bg-white/5 border border-red-100 dark:border-red-500/20 text-red-600 dark:text-red-400 text-xs font-black uppercase tracking-widest hover:bg-red-50 dark:hover:bg-red-500/10 transition-all">
                                        Decline Suggestion
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-32 bg-white dark:bg-darkSurface rounded-[40px] border border-dashed border-gray-200 dark:border-white/10">
            <div class="text-7xl mb-6 flex justify-center text-gray-300">
                <i data-lucide="search-x" class="w-20 h-20"></i>
            </div>
            <h3 class="text-2xl font-black text-[#121613] dark:text-white mb-2">Quiet on the community front</h3>
            <p class="text-[#677e6b] dark:text-gray-400 font-medium">No community proposals have been submitted yet.</p>
        </div>
    <?php endif; ?>
</div>
