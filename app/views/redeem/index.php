<?php
// app/views/redeem/index.php
?>

<div class="px-4 pb-12 pt-6">
  <!-- HEADER SECTION -->
  <div class="mb-8 text-center text-primary dark:text-white">
    <h2 class="text-3xl font-extrabold tracking-tight mb-2">Eco Rewards Shop</h2>
    <p class="text-[#677e6b] dark:text-gray-400 mb-6 font-medium">Redeem your points for exclusive deals from top Sri Lankan brands.</p>
    
    <div class="inline-flex items-center gap-3 bg-[#2c4931] dark:bg-[#4ade80] text-white dark:text-primary px-8 py-4 rounded-2xl shadow-lg border-4 border-white dark:border-white/10">
      <i data-lucide="leaf" class="w-8 h-8"></i>
      <div class="text-left">
        <p class="text-xs opacity-80 font-bold uppercase tracking-widest">Available Balance</p>
        <p class="text-2xl font-black"><?php echo number_format($userPoints); ?> Points</p>
      </div>
    </div>
  </div>

  <!-- STATUS MESSAGES -->
  <?php if (isset($_SESSION['success'])): ?>
    <div class="max-w-4xl mx-auto mb-6 bg-green-100 dark:bg-green-500/10 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded shadow-sm" role="alert">
      <p class="font-bold">Success!</p>
      <p><?php echo $_SESSION['success']; ?></p>
      <?php unset($_SESSION['success']); ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="max-w-4xl mx-auto mb-6 bg-red-100 dark:bg-red-500/10 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded shadow-sm" role="alert">
      <p class="font-bold">Error</p>
      <p><?php echo $_SESSION['error']; ?></p>
      <?php unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>

  <!-- REWARDS GRID -->
  <div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-xl font-bold text-[#121613] dark:text-white">Available Vouchers</h3>
      <span class="text-sm font-semibold text-[#677e6b] dark:text-gray-500"><?php echo count($rewards); ?> items found</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($rewards as $reward): ?>
        <div class="bg-white dark:bg-darkSurface rounded-3xl overflow-hidden border border-[#d8dfd9] dark:border-white/5 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col h-full">
          <!-- IMAGE PLACEHOLDER WITH GRADIENT -->
          <div class="h-48 bg-gradient-to-br from-[#2c4931] to-[#677e6b] relative flex items-center justify-center p-6 grayscale-[0.3] group-hover:grayscale-0 transition-all">
            <div class="text-white text-center">
              <i data-lucide="gift" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
              <p class="text-sm font-bold opacity-80 uppercase tracking-tighter"><?php echo h($reward['partner_name']); ?></p>
            </div>
            
            <!-- CATEGORY BADGE -->
            <div class="absolute top-4 left-4 bg-white/90 dark:bg-black/40 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-[#2c4931] dark:text-[#4ade80] uppercase">
                <?php echo h($reward['category']); ?>
            </div>
          </div>

          <div class="p-6 flex flex-col flex-1">
            <h4 class="text-lg font-bold text-[#121613] dark:text-white mb-2 leading-tight"><?php echo h($reward['title']); ?></h4>
            <p class="text-sm text-[#677e6b] dark:text-gray-400 mb-6 flex-1"><?php echo h($reward['description']); ?></p>
            
            <div class="flex items-center justify-between mt-auto">
              <div class="flex flex-col">
                <span class="text-[10px] text-[#677e6b] dark:text-gray-500 font-bold uppercase tracking-widest">Price</span>
                <span class="text-xl font-black text-[#2c4931] dark:text-[#4ade80]"><?php echo number_format($reward['points_cost']); ?> <span class="text-xs font-normal">pts</span></span>
              </div>
              
              <form action="index.php?route=redeem_action" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="reward_id" value="<?php echo (int)$reward['id']; ?>">
                <?php if (!is_logged_in()): ?>
                  <a href="index.php?route=login" class="px-6 py-2.5 rounded-xl font-bold transition-all bg-[#ebefec] dark:bg-white/5 text-[#2c4931] dark:text-white hover:bg-[#d8dfd9] dark:hover:bg-white/10 block text-center text-sm shadow-sm">
                    Login to Redeem
                  </a>
                <?php else: ?>
                  <button 
                    type="submit"
                    <?php echo ($userPoints < $reward['points_cost']) ? 'disabled' : ''; ?>
                    class="px-6 py-2.5 rounded-xl font-bold transition-all <?php echo ($userPoints >= $reward['points_cost']) ? 'bg-[#2c4931] dark:bg-[#4ade80] text-white dark:text-primary hover:scale-105 shadow-md' : 'bg-[#ebefec] dark:bg-white/5 text-[#677e6b] dark:text-gray-500 cursor-not-allowed'; ?>"
                  >
                    <?php echo ($userPoints >= $reward['points_cost']) ? 'Redeem Now' : 'Not Enough Points'; ?>
                  </button>
                <?php endif; ?>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- MY REDEMPTIONS SECTION -->
  <?php if (!empty($myRedemptions)): ?>
    <div class="max-w-7xl mx-auto mt-16 pt-12 border-t border-[#d8dfd9] dark:border-white/5">
      <h3 class="text-2xl font-bold text-[#121613] dark:text-white mb-8">My Redeemed Rewards</h3>
      
      <div class="bg-[#ebefec] dark:bg-darkSurface rounded-3xl p-8 border border-[#d8dfd9] dark:border-white/5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php foreach ($myRedemptions as $red): ?>
            <div class="bg-white dark:bg-white/5 rounded-2xl p-6 flex items-center gap-4 border border-white dark:border-white/5 shadow-sm hover:shadow-md transition-all">
              <div class="h-16 w-16 bg-[#2c4931] dark:bg-[#4ade80] dark:text-primary rounded-2xl flex items-center justify-center text-white">
                <i data-lucide="ticket" class="w-8 h-8"></i>
              </div>
              <div class="flex-1">
                <p class="text-xs font-bold text-[#677e6b] dark:text-gray-500 uppercase tracking-widest mb-1"><?php echo h($red['partner_name']); ?></p>
                <h5 class="text-md font-bold text-[#121613] dark:text-white leading-tight mb-2"><?php echo h($red['title']); ?></h5>
                <div class="flex items-center gap-4">
                  <div class="bg-gray-100 dark:bg-black/20 px-3 py-1 rounded-lg">
                    <p class="text-[10px] text-gray-500 dark:text-gray-600 font-bold uppercase mb-0.5">Voucher Code</p>
                    <p class="text-sm font-mono font-bold text-[#2c4931] dark:text-[#4ade80] tracking-wider"><?php echo h($red['redemption_code']); ?></p>
                  </div>
                  <div class="text-[10px] text-gray-400 dark:text-gray-500">
                    Redeemed on<br>
                    <span class="font-bold text-gray-600 dark:text-gray-400"><?php echo date('M d, Y', strtotime($red['created_at'])); ?></span>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
