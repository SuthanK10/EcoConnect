<?php
// app/views/user/feedback.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Feedback - Eco-Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .star-rating input { display: none; }
        .star-rating label { cursor: pointer; color: #cbd5e1; transition: all 0.2s; display: flex; align-items: center; justify-content: center; }
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; }
        .star-rating input:checked ~ label { color: #f59e0b; }
        .star-rating label:hover, .star-rating label:hover ~ label { color: #fbbf24; }
        .star-rating label i { fill: none; stroke-width: 2px; }
        .star-rating input:checked ~ label i,
        .star-rating label:hover ~ label i,
        .star-rating label:hover i { fill: currentColor; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

<div class="max-w-md w-full glass rounded-[32px] shadow-2xl overflow-hidden border border-white p-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-[#2c4931]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-[#2c4931]">
            <i data-lucide="leaf" class="w-8 h-8"></i>
        </div>
        <h1 class="text-2xl font-800 text-[#121613]">Event Feedback</h1>
        <p class="text-[#677e6b] mt-2 font-500">How was your experience at <span class="text-[#2c4931] font-700"><?= h($project['title']) ?></span>?</p>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 text-sm rounded-2xl flex items-center gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
            <?= h($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <form action="index.php" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="route" value="feedback_submit">
        <input type="hidden" name="project_id" value="<?= (int)$project_id ?>">

        <!-- Event Rating -->
        <div>
            <label class="block text-sm font-700 text-[#121613] mb-3">How would you rate the event?</label>
            <div class="star-rating gap-1">
                <input type="radio" id="e5" name="event_rating" value="5" required /><label for="e5"><i data-lucide="star" class="w-8 h-8"></i></label>
                <input type="radio" id="e4" name="event_rating" value="4" /><label for="e4"><i data-lucide="star" class="w-8 h-8"></i></label>
                <input type="radio" id="e3" name="event_rating" value="3" /><label for="e3"><i data-lucide="star" class="w-8 h-8"></i></label>
                <input type="radio" id="e2" name="event_rating" value="2" /><label for="e2"><i data-lucide="star" class="w-8 h-8"></i></label>
                <input type="radio" id="e1" name="event_rating" value="1" /><label for="e1"><i data-lucide="star" class="w-8 h-8"></i></label>
            </div>
        </div>



        <!-- Comments -->
        <div>
            <label for="comments" class="block text-sm font-700 text-[#121613] mb-2">Any additional comments?</label>
            <textarea id="comments" name="comments" rows="4" 
                class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-gray-50/50 focus:ring-2 focus:ring-[#2c4931]/20 focus:border-[#2c4931] outline-none transition-all resize-none text-[#121613]"
                placeholder="Share your thoughts..."></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
            class="w-full py-4 bg-[#2c4931] text-white rounded-2xl text-sm font-800 tracking-wide hover:bg-[#1a2e1e] hover:shadow-lg active:scale-[0.98] transition-all">
            Submit Feedback
        </button>

        <a href="index.php?route=user_dashboard" class="block text-center text-xs font-600 text-gray-400 hover:text-[#2c4931] transition-colors">
            Skip for now
        </a>
    </form>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

</body>
</html>
