<?php
// app/views/ngo/qr_view.php
?>

<div class="max-w-xl mx-auto px-4 py-12">
  <div class="bg-white rounded-[40px] border border-gray-100 p-10 shadow-2xl text-center">
    <div class="mb-8">
      <div class="inline-flex items-center gap-2 bg-[#f0f5f1] text-[#2c4931] px-4 py-1.5 rounded-full mb-4">
        <span class="w-2 h-2 rounded-full bg-[#4ade80] shadow-[0_0_8px_#4ade80]"></span>
        <span class="text-[11px] font-black uppercase tracking-widest"><?= ucfirst($type) ?> Active</span>
      </div>
      <h2 class="text-3xl font-black text-[#121613] tracking-tight">Scan for Attendance</h2>
      <p class="text-sm text-[#677e6b] mt-2">Display this to volunteers to record their participation.</p>
    </div>

    <!-- QR CODE -->
    <div class="flex flex-col items-center justify-center p-8 bg-[#f9fafb] rounded-[32px] border-2 border-dashed border-gray-100 mb-8">
      <div class="bg-white p-6 rounded-3xl shadow-lg mb-4">
        <img
          src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?= urlencode($qrUrl) ?>"
          alt="Attendance QR Code"
          class="w-64 h-64"
        >
      </div>
      <p class="text-[10px] font-black text-[#2c4931]/60 uppercase tracking-[0.2em]">Valid for 5 Minutes</p>
    </div>
    <div class="flex flex-col gap-3">
      <a href="index.php?route=ngo_dashboard"
         class="w-full py-4 rounded-2xl bg-[#2c4931] text-white text-sm font-black tracking-wide hover:bg-[#121613] transition-all shadow-lg active:scale-95">
        Return to Dashboard
      </a>
      <button onclick="window.print()" class="text-xs font-bold text-[#677e6b] hover:underline">
        Print QR Sheet
      </button>
    </div>
  </div>
</div>