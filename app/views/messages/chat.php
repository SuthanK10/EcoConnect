<?php
// app/views/messages/chat.php
?>
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="mb-6 flex items-center gap-4">
        <a href="index.php?route=messages" class="w-10 h-10 rounded-xl bg-white dark:bg-darkSurface border border-gray-100 dark:border-white/10 flex items-center justify-center text-[#121613] dark:text-white hover:shadow-md transition-all">
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
        </a>
        <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-xl font-bold text-primary">
            <?= strtoupper(substr($other_user['name'], 0, 1)) ?>
        </div>
        <div>
            <h1 class="text-2xl font-black text-[#121613] dark:text-white"><?= h($other_user['name']) ?></h1>
            <p class="text-xs font-bold text-primary uppercase tracking-widest"><?= h($other_user['role']) ?></p>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="bg-white dark:bg-darkSurface rounded-[40px] shadow-sm border border-gray-100 dark:border-white/5 flex flex-col h-[600px]">
        
        <!-- Messages Area -->
        <div id="message-area" class="flex-1 overflow-y-auto p-8 space-y-6">
            <?php foreach ($messages as $msg): ?>
                <?php $is_mine = ((int)$msg['sender_id'] === $my_id); ?>
                <div class="flex <?= $is_mine ? 'justify-end' : 'justify-start' ?>">
                    <div class="max-w-[70%]">
                        <div class="p-4 rounded-3xl <?= $is_mine ? 'bg-primary text-white rounded-br-none' : 'bg-gray-100 dark:bg-white/5 text-[#121613] dark:text-white rounded-bl-none' ?>">
                            <p class="text-[15px] font-medium leading-relaxed"><?= h($msg['message']) ?></p>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 <?= $is_mine ? 'text-right' : 'text-left' ?>">
                            <?= date('g:i a', strtotime($msg['created_at'])) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Input Area -->
        <div class="p-6 border-t border-gray-100 dark:border-white/5">
            <form id="chat-form" action="index.php?route=message_send" method="POST" class="flex gap-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="receiver_id" value="<?= $other_id ?>">
                <input type="text" name="message" id="msg-input" autocomplete="off" placeholder="Write a message..." 
                    class="flex-1 px-6 py-4 rounded-2xl border-none bg-gray-50 dark:bg-white/5 text-[#121613] dark:text-white focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                <button type="submit" class="bg-primary text-white px-8 py-4 rounded-2xl font-black hover:scale-105 transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                    Send <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const messageArea = document.getElementById('message-area');
    const msgInput = document.getElementById('msg-input');
    const otherId = <?= $other_id ?>;
    const myId = <?= $my_id ?>;

    // Scroll to bottom
    messageArea.scrollTop = messageArea.scrollHeight;

    function appendMessage(msg, isMine) {
        const date = new Date(msg.created_at);
        const timeStr = date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        
        const html = `
            <div class="flex ${isMine ? 'justify-end' : 'justify-start'} animate-fade-in">
                <div class="max-w-[70%]">
                    <div class="p-4 rounded-3xl ${isMine ? 'bg-primary text-white rounded-br-none' : 'bg-gray-100 dark:bg-white/5 text-[#121613] dark:text-white rounded-bl-none'}">
                        <p class="text-[15px] font-medium leading-relaxed">${msg.message}</p>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 ${isMine ? 'text-right' : 'text-left'}">${timeStr}</p>
                </div>
            </div>
        `;
        messageArea.insertAdjacentHTML('beforeend', html);
        messageArea.scrollTop = messageArea.scrollHeight;
    }

    // Polling for new messages
    setInterval(() => {
        fetch(`index.php?route=message_ajax_poll&with=${otherId}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.length > 0) {
                    data.forEach(msg => appendMessage(msg, false));
                }
            });
    }, 3000);

    // Simple AJAX send
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const content = msgInput.value.trim();
        if (!content) return;

        const formData = new FormData(this);
        msgInput.value = '';

        fetch('index.php?route=message_send', {
            method: 'POST',
            body: formData
        }).then(() => {
            appendMessage({ message: content, created_at: new Date().toISOString() }, true);
        });
    });
</script>
