<?php
// app/views/messages/inbox.php
?>
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="mb-10 flex justify-between items-center">
        <div>
            <h1 class="text-[#121613] dark:text-white text-4xl font-black tracking-tight mb-2">Inbox</h1>
            <p class="text-[15px] text-[#677e6b] dark:text-gray-400 font-medium">Manage your conversations with the community.</p>
        </div>
        <?php if ($_SESSION['role'] === 'user'): ?>
            <a href="index.php?route=contact_ngo" class="bg-primary text-white px-6 py-3 rounded-2xl font-bold hover:shadow-lg transition-all">New Message</a>
        <?php elseif ($_SESSION['role'] === 'ngo'): ?>
            <a href="index.php?route=contact_admin" class="bg-primary text-white px-6 py-3 rounded-2xl font-bold hover:shadow-lg transition-all">Contact Admin</a>
        <?php endif; ?>
    </div>

    <div class="bg-white dark:bg-darkSurface rounded-[40px] shadow-sm border border-gray-100 dark:border-white/5 overflow-hidden">
        <?php if (empty($conversations)): ?>
            <div class="p-20 text-center">
                <div class="text-6xl mb-6 flex justify-center text-gray-200">
                    <i data-lucide="message-square" class="w-16 h-16"></i>
                </div>
                <h3 class="text-xl font-bold text-[#121613] dark:text-white mb-2">No messages yet</h3>
                <p class="text-[#677e6b] dark:text-gray-400">When you start a conversation, it will appear here.</p>
            </div>
        <?php else: ?>
            <div class="divide-y divide-gray-100 dark:divide-white/5">
                <?php foreach ($conversations as $conv): ?>
                    <a href="index.php?route=message_chat&with=<?= $conv['id'] ?>" class="block p-6 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-xl font-bold text-primary">
                                <?= strtoupper(substr($conv['name'], 0, 1)) ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h4 class="text-lg font-bold text-[#121613] dark:text-white truncate"><?= h($conv['name']) ?></h4>
                                    <span class="text-xs text-gray-400"><?= date('M j, g:i a', strtotime($conv['last_date'])) ?></span>
                                </div>
                                <p class="text-sm text-[#677e6b] dark:text-gray-400 truncate"><?= h($conv['last_msg']) ?></p>
                            </div>
                            <div class="text-primary">
                                <i data-lucide="chevron-right" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
