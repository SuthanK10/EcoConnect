<?php
// app/views/donations/success.php
?>

<div class="flex flex-col items-center justify-center gap-6 px-4 py-16 text-center max-w-[600px] mx-auto">
    <div class="w-24 h-24 bg-[#ebfef0] text-[#12b76a] rounded-full flex items-center justify-center animate-bounce">
        <i data-lucide="check-circle-2" class="w-12 h-12"></i>
    </div>

    <div class="space-y-2">
        <h2 class="text-3xl font-bold text-[#121613]">Donation Successful!</h2>
        <p class="text-[#677e6b] text-lg">Thank you for your generous contribution. You are now a part of the change we wish to see in the world.</p>
    </div>

    <div class="p-6 bg-white rounded-2xl border border-[#d8dfd9] w-full shadow-sm">
        <p class="text-sm text-[#677e6b] mb-4 italic">"Nature does not hurry, yet everything is accomplished."</p>
        <div class="h-[1px] bg-[#ebefec] w-full mb-4"></div>
        <p class="text-xs text-[#677e6b]">A receipt has been sent to your registered email address (in a real production environment).</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 w-full">
        <a href="index.php?route=home" class="flex-1 bg-[#2c4931] text-white font-bold py-3 rounded-xl transition-all hover:bg-[#1a2e1e]">
            Return Home
        </a>
        <a href="index.php?route=events" class="flex-1 bg-[#ebefec] text-[#121613] font-bold py-3 rounded-xl transition-all hover:bg-[#d8dfd9]">
            Explore Events
        </a>
    </div>
</div>
