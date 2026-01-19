<?php
// app/views/donations/index.php
?>

<div class="flex flex-col gap-6 px-4 py-8 max-w-[800px] mx-auto">
    <div class="text-center">
        <h2 class="text-[#121613] dark:text-white text-3xl font-bold leading-tight tracking-tight">Support Our Mission</h2>
        <p class="text-[#677e6b] dark:text-gray-400 text-base mt-2">Every contribution helps us clean our oceans and protect our environment.</p>
    </div>

    <!-- DONATION OPTIONS SELECTOR -->
    <div class="flex p-1 bg-[#ebefec] dark:bg-white/5 rounded-xl self-center mb-4">
        <button id="tab-card" onclick="switchTab('card')" class="px-6 py-2 text-sm font-bold rounded-lg transition-all bg-white dark:bg-darkSurface text-[#121613] dark:text-white shadow-sm">
            Credit / Debit Card
        </button>
        <button id="tab-bank" onclick="switchTab('bank')" class="px-6 py-2 text-sm font-bold rounded-lg transition-all text-[#677e6b] dark:text-gray-400 hover:text-[#121613] dark:hover:text-white">
            Bank Transfer
        </button>
    </div>

    <!-- CARD DONATION FORM -->
    <div id="section-card" class="bg-white dark:bg-darkSurface rounded-2xl border border-[#d8dfd9] dark:border-white/10 p-8 shadow-sm">
        <h3 class="text-xl font-bold text-[#121613] dark:text-white mb-6 flex items-center gap-3">
            <i data-lucide="credit-card" class="w-6 h-6"></i>
            Card Payment
        </h3>

        <div id="card-error" class="<?php echo empty($error) ? 'hidden' : ''; ?> mb-6 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 rounded-xl text-sm font-bold flex items-center gap-3">
            <?php if (!empty($error)) echo '<i data-lucide="alert-triangle" class="w-4 h-4"></i> ' . h($error); ?>
        </div>

        <form id="donation-form" action="index.php?route=donations" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-bold text-[#121613] dark:text-white mb-2 text-left">Donation Amount (LKR)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-bold">Rs.</span>
                    <input type="number" name="amount" required min="100" placeholder="100" 
                           class="w-full pl-12 pr-4 py-3 rounded-xl border-[#d8dfd9] dark:border-white/10 dark:bg-white/5 dark:text-white focus:border-[#2c4931] focus:ring-[#2c4931]"
                           value="<?php echo h($_POST['amount'] ?? '100'); ?>">
                </div>
                <p class="text-xs text-gray-500 mt-2 text-left">Minimum donation is Rs. 100</p>
            </div>

            <div class="p-4 bg-gray-50 dark:bg-white/5 rounded-xl border border-[#d8dfd9] dark:border-white/10 flex items-center gap-3">
                <div class="bg-[#635bff] p-2 rounded-lg text-white">
                    <svg viewBox="0 0 60 25" width="40" height="16" fill="currentColor"><path d="M59.64 14.28c0 3.32-1.74 5.38-4.7 5.38-1.52 0-2.6-.54-3.26-1.2l-.08 3.96h-3.32v-16.1h3.32l.08 1.18c.68-.78 1.8-1.4 3.3-1.4 2.8 0 4.66 2.06 4.66 5.38v2.8zm-3.32-2.38c0-1.88-.94-2.82-2.22-2.82-.6 0-1.04.18-1.38.54V16c.34.36.78.54 1.38.54 1.28 0 2.22-.94 2.22-2.82v-1.82zM45.62 19.38c-2.48 0-4.34-1.32-4.34-3.52 0-2.84 2.82-3.32 5.2-3.32h.56v-.54c0-1.02-.64-1.52-1.74-1.52-.96 0-1.88.24-2.7.7l-.68-2.42c1.12-.6 2.44-.94 3.78-.94 2.84 0 4.68 1.3 4.68 4.3v6.8h-3.32l-.12-1.1c-.68.84-1.84 1.26-3.32 1.26v.1zm2.14-4.22h-.5c-.82 0-1.76.12-1.76.98 0 .42.3.72.84.72.6 0 1.08-.24 1.42-.64v-1.06zM37.3 19.38c-1.52 0-2.62-.56-3.26-1.22l-.08 1h-3.32V2h3.32v5.66c.66-.74 1.76-1.3 3.26-1.3 2.76 0 4.66 2.06 4.66 5.38v2.26c0 3.32-1.9 5.38-4.66 5.38zM37 9.08c-.6 0-1.04.18-1.38.54v6.12c.34.36.78.54 1.38.54 1.28 0 2.24-.94 2.24-2.82v-1.56c0-1.88-.96-2.82-2.24-2.82zM24.8 19.38c-2.84 0-4.7-2.06-4.7-5.38v-2.26c0-3.32 1.86-5.38 4.7-5.38 1.38 0 2.5.54 3.16 1.14l.08-.94H31.4v12.82h-3.32l-.08-1.22c-.66.86-1.82 1.22-3.2 1.22zm2.18-2.32c1.28 0 2.24-.94 2.24-2.82v-1.56c0-1.88-.96-2.82-2.24-2.82-.6 0-1.04.18-1.38.54v6.12c.34.36.78.54 1.38.54zM16.96 19h-3.46v-1.72c-.66.86-1.82 1.26-3.18 1.26-2.86 0-4.72-2.06-4.72-5.38v-2.26c0-3.32 1.86-5.38 4.72-5.38 1.38 0 2.52.54 3.18 1.14l.08-.94h3.38V19zm-2.18-2.32c1.26 0 2.22-.94 2.22-2.82v-1.56c0-1.88-.96-2.82-2.22-2.82-.6 0-1.04.18-1.38.54v6.12c.34.36.78.54 1.38.54zM5.3 19.38c-1.52 0-2.6-.56-3.24-1.22l-.08 1H0V2h3.32v5.66C4 6.92 5 6.36 6.5 6.36c2.76 0 4.66 2.06 4.66 5.38v2.26c0 3.32-1.9 5.38-4.66 5.38zM5 9.08c-.6 0-1.04.18-1.38.54v6.12c.34.36.78.54 1.38.54 1.28 0 2.24-.94 2.24-2.82v-1.56c0-1.88-.96-2.82-2.24-2.82z"></path></svg>
                </div>
                <div class="text-left">
                    <p class="text-xs font-bold text-[#121613] dark:text-white leading-tight">Secure Payment via Stripe</p>
                    <p class="text-[10px] text-gray-500 leading-tight">You will be redirected to Stripe's secure checkout</p>
                </div>
                <i data-lucide="lock" class="ml-auto w-4 h-4 text-gray-400"></i>
            </div>

            <button type="submit" 
                    class="w-full bg-[#2c4931] hover:bg-[#1a2e1e] text-white font-bold py-4 rounded-xl transition-all shadow-lg active:scale-[0.98] mt-4 flex items-center justify-center gap-2">
                Continue to Secure Payment
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </button>
        </form>
    </div>

    <!-- BANK TRANSFER INFO -->
    <div id="section-bank" class="hidden bg-white dark:bg-darkSurface rounded-2xl border border-[#d8dfd9] dark:border-white/10 p-8 shadow-sm">
        <h3 class="text-xl font-bold text-[#121613] dark:text-white mb-6 flex items-center gap-3 text-left">
            <i data-lucide="landmark" class="w-6 h-6"></i>
            Bank Transfer Details
        </h3>
        
        <div class="p-6 bg-[#f9fafa] dark:bg-white/5 rounded-2xl border border-[#ebefec] dark:border-white/10 space-y-4">
            <div class="flex justify-between items-center border-b border-[#ebefec] dark:border-white/10 pb-3 text-left">
                <span class="text-[#677e6b] dark:text-gray-400 text-sm">Bank Name</span>
                <span class="text-[#121613] dark:text-white font-bold">Commercial Bank</span>
            </div>
            <div class="flex justify-between items-center border-b border-[#ebefec] dark:border-white/10 pb-3 text-left">
                <span class="text-[#677e6b] dark:text-gray-400 text-sm">Account Name</span>
                <span class="text-[#121613] dark:text-white font-bold">K H Suthan</span>
            </div>
            <div class="flex justify-between items-center text-left">
                <span class="text-[#677e6b] dark:text-gray-400 text-sm">Account Number</span>
                <span class="text-[#121613] dark:text-white font-bold text-lg tracking-wider">8001154500</span>
            </div>
        </div>

        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl flex gap-3 text-left">
            <i data-lucide="info" class="w-5 h-5 text-blue-500 shrink-0"></i>
            <p class="text-xs text-blue-700 dark:text-blue-400 leading-normal">
                After transfer, please keep your receipt. You may be selected for our donor rewards program where we send Eco-merchandise!
            </p>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    const cardTab = document.getElementById('tab-card');
    const bankTab = document.getElementById('tab-bank');
    const cardSection = document.getElementById('section-card');
    const bankSection = document.getElementById('section-bank');

    if (tab === 'card') {
        cardTab.classList.add('bg-white', 'dark:bg-darkSurface', 'text-[#121613]', 'dark:text-white', 'shadow-sm');
        cardTab.classList.remove('text-[#677e6b]', 'dark:text-gray-400');
        
        bankTab.classList.remove('bg-white', 'dark:bg-darkSurface', 'text-[#121613]', 'dark:text-white', 'shadow-sm');
        bankTab.classList.add('text-[#677e6b]', 'dark:text-gray-400');

        cardSection.classList.remove('hidden');
        bankSection.classList.add('hidden');
    } else {
        bankTab.classList.add('bg-white', 'dark:bg-darkSurface', 'text-[#121613]', 'dark:text-white', 'shadow-sm');
        bankTab.classList.remove('text-[#677e6b]', 'dark:text-gray-400');
        
        cardTab.classList.remove('bg-white', 'dark:bg-darkSurface', 'text-[#121613]', 'dark:text-white', 'shadow-sm');
        cardTab.classList.add('text-[#677e6b]', 'dark:text-gray-400');

        bankSection.classList.remove('hidden');
        cardSection.classList.add('hidden');
    }
}
</script>

