<?php
// app/views/auth/forgot.php
?>

<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
  <!-- Background Decorations -->
  <div class="absolute top-0 left-0 w-96 h-96 bg-[#2c4931]/10 rounded-full blur-3xl -ml-48 -mt-48"></div>
  <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#4ade80]/10 rounded-full blur-3xl -mr-48 -mb-48"></div>

  <div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 bg-white dark:bg-darkSurface rounded-[40px] shadow-2xl overflow-hidden border border-gray-100 dark:border-white/5 relative z-10">
    
    <!-- Left Side: Visual/Branding -->
    <div class="hidden md:flex flex-col justify-between p-12 bg-[#2c4931] relative overflow-hidden">
      <div class="absolute inset-0 bg-[url('assets/hero1.webp')] bg-cover bg-center opacity-30 mix-blend-overlay"></div>
      <div class="relative z-10">
        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-lg mb-8">
          <i data-lucide="leaf" class="w-6 h-6 text-primary"></i>
        </div>
        <h2 class="text-4xl font-black text-white leading-tight mb-4">Reset your password</h2>
        <p class="text-white/70 text-lg">Enter your email and we'll send you a link to get back into your account.</p>
      </div>
      <div class="relative z-10">
        <p class="text-white/50 text-sm font-medium italic">"Small acts, when multiplied by millions of people, can transform the world."</p>
      </div>
    </div>

    <!-- Right Side: Form -->
    <div class="p-8 md:p-12 flex flex-col justify-center">
      <div class="mb-10 text-center md:text-left">
        <h1 class="text-3xl font-black text-[#121613] dark:text-white mb-2">Forgot Password</h1>
        <p class="text-[#677e6b] dark:text-gray-400 font-medium lowercase tracking-wide">Enter your registered email address</p>
      </div>

      <?php if (!empty($message)): ?>
        <div class="mb-8 p-4 rounded-2xl bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-900/30 flex items-start gap-3">
          <span class="text-green-500 text-lg">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
          </span>
          <div class="flex-1">
            <p class="text-sm text-green-700 dark:text-green-400 font-bold"><?php echo h($message); ?></p>
          </div>
        </div>
      <?php endif; ?>

      <form method="post" class="space-y-6">
        <?php echo csrf_field(); ?>
        <div class="group">
          <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2 group-focus-within:text-[#2c4931] transition-colors">Email Address</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#2c4931]">
              <i data-lucide="mail" class="w-4 h-4"></i>
            </div>
            <input 
              type="email" 
              name="email" 
              required 
              placeholder="name@example.com"
              class="block w-full pl-11 pr-4 py-4 rounded-2xl border-2 border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white placeholder-gray-400 focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] focus:bg-white dark:focus:bg-darkSurface transition-all outline-none font-medium" 
            />
          </div>
        </div>

        <button
          type="submit"
          class="w-full py-4 rounded-2xl bg-[#2c4931] text-white text-base font-black shadow-xl hover:bg-[#121613] focus:ring-4 focus:ring-[#2c4931]/20 transition-all transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2"
        >
          Send Reset Link <i data-lucide="send" class="w-4 h-4"></i>
        </button>

        <div class="pt-6 text-center border-t border-gray-50 dark:border-white/5">
          <p class="text-sm font-medium text-[#677e6b] dark:text-gray-400">
            Remembered your password? 
            <a href="index.php?route=login" class="text-[#2c4931] dark:text-[#4ade80] font-black hover:underline ml-1">Login here</a>
          </p>
        </div>
      </form>
    </div>
  </div>
</div>
