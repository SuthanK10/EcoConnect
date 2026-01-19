<?php
// app/views/user/scan_result.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Status - Eco-Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .success-accent { color: #10b981; }
        .error-accent { color: #ef4444; }
        .warning-accent { color: #f59e0b; }
        .info-accent { color: #3b82f6; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

<div class="max-w-md w-full glass rounded-[32px] shadow-2xl overflow-hidden border border-white p-8 text-center">
    
    <?php if ($status === 'error'): ?>
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600">
            <i data-lucide="shield-alert" class="w-10 h-10"></i>
        </div>
        <h1 class="text-2xl font-800 text-[#121613] mb-4"><?= h($title ?? 'Access Denied') ?></h1>
        <p class="text-[#677e6b] mb-8 font-500 leading-relaxed"><?= h($message) ?></p>
        <div class="space-y-3">
            <a href="index.php?route=events" class="block w-full py-4 bg-[#2c4931] text-white rounded-2xl text-sm font-800 tracking-wide hover:bg-[#1a2e1e] transition-all">
                Browse Events
            </a>
            <a href="index.php?route=user_dashboard" class="block w-full py-4 bg-gray-50 text-gray-500 rounded-2xl text-sm font-700 hover:bg-gray-100 transition-all">
                Go to Dashboard
            </a>
        </div>

    <?php elseif ($status === 'warning'): ?>
        <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6 text-amber-600">
            <i data-lucide="alert-triangle" class="w-10 h-10"></i>
        </div>
        <h1 class="text-2xl font-800 text-[#121613] mb-4"><?= h($title ?? 'Already Checked In') ?></h1>
        <p class="text-[#677e6b] mb-8 font-500 leading-relaxed"><?= h($message) ?></p>
        <a href="index.php?route=user_dashboard" class="block w-full py-4 bg-[#2c4931] text-white rounded-2xl text-sm font-800 tracking-wide hover:bg-[#1a2e1e] transition-all">
            Go to Dashboard
        </a>

    <?php elseif ($status === 'info'): ?>
        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6 text-blue-600">
            <i data-lucide="info" class="w-10 h-10"></i>
        </div>
        <h1 class="text-2xl font-800 text-[#121613] mb-4"><?= h($title ?? 'Attendance Recorded') ?></h1>
        <p class="text-[#677e6b] mb-2 font-500 leading-relaxed"><?= h($message) ?></p>
        <?php if (isset($points)): ?>
            <p class="text-lg font-700 text-[#2c4931] mb-8">Points earned: <?= (int)$points ?></p>
        <?php endif; ?>
        <a href="index.php?route=user_dashboard" class="block w-full py-4 bg-[#2c4931] text-white rounded-2xl text-sm font-800 tracking-wide hover:bg-[#1a2e1e] transition-all">
            Go to Dashboard
        </a>

    <?php elseif ($status === 'success_checkin'): ?>
        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600">
            <i data-lucide="check-circle-2" class="w-10 h-10"></i>
        </div>
        <h1 class="text-2xl font-800 text-[#121613] mb-4">Check-in Successful!</h1>
        <p class="text-[#677e6b] mb-4 font-500 leading-relaxed">You are now marked present at this event.</p>
        <p class="text-sm text-gray-400 mb-8 italic">Remember to scan the checkout QR when you leave!</p>
        <a href="index.php?route=user_dashboard" class="block w-full py-4 bg-[#2c4931] text-white rounded-2xl text-sm font-800 tracking-wide hover:bg-[#1a2e1e] transition-all">
            Go to Dashboard
        </a>

    <?php elseif ($status === 'success_checkout'): ?>
        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600">
            <i data-lucide="party-popper" class="w-10 h-10"></i>
        </div>
        <h1 class="text-2xl font-800 text-[#121613] mb-4">Check-out Successful!</h1>
        <div class="bg-emerald-50 rounded-2xl p-6 mb-8 border border-emerald-100">
            <p class="text-sm text-emerald-600 font-700 uppercase tracking-widest mb-2">Rewards Earned</p>
            <p class="text-3xl font-900 text-[#2c4931] mb-1"><?= (int)$points ?> Eco Points</p>
            <p class="text-xs text-emerald-500 font-600">Time spent: <?= (int)$hours ?> hour(s)</p>
        </div>
        
        <div class="space-y-3">
            <a href="index.php?route=feedback&project_id=<?= (int)$project_id ?>" class="block w-full py-4 bg-[#2c4931] text-white rounded-2xl text-sm font-800 tracking-wide hover:bg-[#1a2e1e] shadow-lg shadow-emerald-900/10 transition-all scale-[1.02] hover:scale-[1.05]">
                Share your Feedback
            </a>
            <a href="index.php?route=user_dashboard" class="block w-full py-4 bg-gray-50 text-gray-500 rounded-2xl text-sm font-700 hover:bg-gray-100 transition-all">
                Maybe later, go to Dashboard
            </a>
        </div>
    <?php endif; ?>

</div>

<script>
    window.addEventListener('load', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>

</body>
</html>
