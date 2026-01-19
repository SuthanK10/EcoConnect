<?php
// app/views/user/my_proposals.php
?>

<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[#121613] text-4xl font-black tracking-tight mb-2 flex items-center gap-3">
                My Community Proposals <i data-lucide="mail" class="w-8 h-8 text-primary"></i>
            </h1>
            <p class="text-[15px] text-[#677e6b] font-medium leading-relaxed">
                Track the status of your suggested cleanup initiatives.
            </p>
        </div>
        <a href="index.php?route=user_propose_cleanup" class="h-12 px-8 rounded-2xl bg-[#2c4931] flex items-center justify-center text-sm font-black text-white hover:bg-[#121613] transition-all shadow-xl shadow-[#2c4931]/20 active:scale-95 gap-2">
            Propose New Drive <i data-lucide="plus" class="w-4 h-4"></i>
        </a>
    </div>

    <?php if (!empty($proposals)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($proposals as $prop): ?>
                <?php 
                    $status = strtolower($prop['status']);
                    $statusConfig = [
                        'pending' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'icon' => '<i data-lucide="clock" class="w-3 h-3"></i>'],
                        'approved' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'icon' => '<i data-lucide="check-circle-2" class="w-3 h-3"></i>'],
                        'rejected' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'icon' => '<i data-lucide="x-circle" class="w-3 h-3"></i>'],
                        'converted' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'icon' => '<i data-lucide="shield-check" class="w-3 h-3"></i>']
                    ][$status] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'icon' => '<i data-lucide="help-circle" class="w-3 h-3"></i>'];
                ?>
                <div class="group bg-white rounded-[32px] border border-gray-100 p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-3 py-1 rounded-full <?php echo $statusConfig['bg']; ?> <?php echo $statusConfig['text']; ?> text-[10px] font-black uppercase tracking-widest border border-black/5 flex items-center gap-1.5">
                            <?php echo $statusConfig['icon']; ?> <?php echo $prop['status']; ?>
                        </span>
                        <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">
                            Submitted <?php echo date('M d, Y', strtotime($prop['created_at'])); ?>
                        </span>
                    </div>

                    <h3 class="text-xl font-black text-[#121613] mb-4 group-hover:text-[#2c4931] transition-colors leading-tight">
                        <?php echo h($prop['title']); ?>
                    </h3>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-2 text-xs font-bold text-[#677e6b]">
                            <span class="w-8 h-8 rounded-xl bg-[#f0f5f1] flex items-center justify-center text-primary">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </span>
                            <?php echo h($prop['location']); ?>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-[#677e6b]">
                            <span class="w-8 h-8 rounded-xl bg-[#f0f5f1] flex items-center justify-center text-primary">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </span>
                            Suggested for <?php echo date('M d, Y', strtotime($prop['proposed_date'])); ?>
                        </div>
                    </div>

                    <div class="bg-[#f0f5f1] rounded-2xl p-6 border border-[#2c4931]/5">
                        <p class="text-[10px] font-black text-[#2c4931] uppercase tracking-[0.2em] mb-2">Original Proposal</p>
                        <p class="text-sm text-[#2c4931] leading-relaxed italic line-clamp-2">
                            "<?php echo h($prop['description']); ?>"
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-24 bg-white rounded-[40px] border border-dashed border-gray-200">
            <div class="text-6xl mb-6 flex justify-center text-gray-200">
                <i data-lucide="map" class="w-16 h-16"></i>
            </div>
            <h3 class="text-xl font-black text-[#121613] mb-2">No proposals yet</h3>
            <p class="text-[#677e6b] mb-8 max-w-md mx-auto">Start leading the change in your community! Propose a location that needs attention.</p>
            <a href="index.php?route=user_propose_cleanup" class="inline-flex rounded-2xl bg-[#2c4931] px-8 py-4 text-sm font-black text-white hover:scale-105 transition-all shadow-lg shadow-[#2c4931]/20">
                Submit Your First Proposal
            </a>
        </div>
    <?php endif; ?>
</div>
