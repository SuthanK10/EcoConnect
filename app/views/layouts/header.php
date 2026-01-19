<?php
// app/views/layouts/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// determine current route safely
$currentRoute = $_GET['route'] ?? 'home';

// routes where back button must NOT appear
$hideBackOnRoutes = [
    'home',
    'events',
    'leaderboard',
    'donations',
    'rewards',
    'gallery',
    'partnerships',
    'explore_drives',
    'login',
    'register',
    'logout',
    'admin_dashboard',
    'ngo_dashboard',
    'user_dashboard'
];
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($pageTitle) ? h($pageTitle) . ' | EcoConnect' : 'EcoConnect'; ?></title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?display=swap&family=Noto+Sans:wght@400;500;700;900&family=Public+Sans:wght@400;500;700;900"
    />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              primary: '#2c4931',
              darkBg: '#0f172a',
              darkSurface: '#1e293b'
            }
          }
        }
      }

      // Sync theme with localStorage
      if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    </script>
    <style>
      .mobile-nav-transition { transition: transform 0.3s ease-in-out; }
      [x-cloak] { display: none !important; }
    </style>
  </head>

  <body class="bg-gray-50 dark:bg-darkBg transition-colors duration-300 h-full">
    <div
      class="relative flex min-h-screen w-full flex-col overflow-x-hidden"
      style='font-family: "Public Sans", "Noto Sans", sans-serif;'
    >
      <div class="layout-container flex grow flex-col">

        <!-- NAVBAR -->
        <header class="sticky top-0 z-50 flex items-center justify-between border-b border-gray-100 dark:border-white/5 px-6 md:px-10 py-4 bg-primary dark:bg-darkSurface text-white shadow-lg">
          <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-xl bg-white/10 border border-white/10 overflow-hidden">
              <img
                src="<?php echo BASE_URL; ?>/public/assets/mainlogo.png?v=<?php echo time(); ?>"
                alt="EcoConnect logo"
                class="h-full w-full object-cover"
              />
            </div>
            <a
              href="index.php?route=home"
              class="text-white text-xl font-black tracking-tighter"
            >
              EcoConnect
            </a>
          </div>

          <nav class="hidden lg:flex items-center gap-8">
            <a class="text-sm font-black hover:text-[#4ade80] transition-colors" href="index.php?route=home">Home</a>
            <a class="text-sm font-black hover:text-[#4ade80] transition-colors" href="index.php?route=events">Events</a>
            <a class="text-sm font-black hover:text-[#4ade80] transition-colors" href="index.php?route=leaderboard">Leaderboard</a>
            <a class="text-sm font-black hover:text-[#4ade80] transition-colors" href="index.php?route=donations">Donate</a>
            <a class="text-sm font-black hover:text-[#4ade80] transition-colors" href="index.php?route=rewards">Rewards</a>
            <a class="text-sm font-black hover:text-[#4ade80] transition-colors" href="index.php?route=gallery">Eco-Action Feed</a>
            <a class="text-sm font-black hover:text-[#4ade80] transition-colors" href="index.php?route=partnerships">NGOs & Partners</a>
            
            <?php if (isset($_SESSION['role'])): ?>
              <div class="h-6 w-px bg-white/20"></div>
              <?php if ($_SESSION['role'] === 'admin'): ?>
                <a class="text-sm font-black text-[#4ade80]" href="index.php?route=admin_dashboard">Admin Console</a>
              <?php elseif ($_SESSION['role'] === 'ngo'): ?>
                <a class="text-sm font-black text-[#4ade80]" href="index.php?route=ngo_dashboard">NGO Portal</a>
              <?php elseif ($_SESSION['role'] === 'user'): ?>
                <a class="text-sm font-black text-[#4ade80]" href="index.php?route=user_dashboard">Dashboard</a>
              <?php endif; ?>
            <?php endif; ?>
          </nav>

          <div class="flex items-center gap-4">
            <!-- Theme Toggle -->
            <button id="themeToggle" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all border border-white/10">
              <span id="sunIcon" class="hidden"><i data-lucide="sun" class="w-5 h-5 text-amber-400"></i></span>
              <span id="moonIcon" class=""><i data-lucide="moon" class="w-5 h-5 text-blue-200"></i></span>
            </button>

            <!-- Desktop Auth -->
            <div class="hidden md:flex items-center gap-3">
              <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="index.php?route=login" class="text-sm font-black px-6 py-2.5 rounded-xl border border-white/20 hover:bg-white/10 transition-all">Login</a>
                <a href="index.php?route=register" class="bg-[#4ade80] text-primary px-6 py-2.5 rounded-xl text-sm font-black hover:bg-[#22c55e] transition-all shadow-lg shadow-[#4ade80]/20">Get Started</a>
              <?php else: ?>
                <?php 
                  $dashboardRoute = 'user_dashboard';
                  if ($_SESSION['role'] === 'admin') $dashboardRoute = 'admin_dashboard';
                  if ($_SESSION['role'] === 'ngo') $dashboardRoute = 'ngo_dashboard';
                ?>
                <a href="index.php?route=<?php echo $dashboardRoute; ?>" class="flex items-center gap-2 bg-white/10 border border-white/20 px-5 py-2.5 rounded-xl text-sm font-black hover:bg-white/20 transition-all">
                   <span class="w-2 h-2 rounded-full bg-[#4ade80] animate-pulse"></span>
                   My Account
                </a>
                <form action="index.php?route=logout" method="post" class="inline">
                  <?php echo csrf_field(); ?>
                  <button class="p-2.5 rounded-xl bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition-all flex items-center justify-center" title="Logout">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                  </button>
                </form>
              <?php endif; ?>
            </div>

            <!-- Mobile Menu Toggle -->
            <button id="menuOpen" class="lg:hidden p-2 text-white">
              <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
          </div>
        </header>

        <!-- Mobile Drawer Overlay -->
        <div id="navOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9990] hidden transition-opacity duration-300 opacity-0"></div>
        
        <!-- Mobile Drawer Content -->
        <div id="mobileNav" class="fixed top-0 right-0 h-full w-[280px] bg-primary dark:bg-darkSurface z-[9999] mobile-nav-transition translate-x-full shadow-2xl p-8">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-between mb-12">
                    <span class="text-xl font-black text-white italic">Navigation</span>
                    <button id="menuClose" class="p-2 text-white/60 hover:text-white">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <nav class="flex flex-col gap-6 mb-auto">
                    <a class="text-lg font-black text-white hover:text-[#4ade80]" href="index.php?route=home">Home</a>
                    <a class="text-lg font-black text-white hover:text-[#4ade80]" href="index.php?route=events">Drives</a>
                    <a class="text-lg font-black text-white hover:text-[#4ade80]" href="index.php?route=leaderboard">Leaderboard</a>
                    <a class="text-lg font-black text-white hover:text-[#4ade80]" href="index.php?route=donations">Donate</a>
                    <a class="text-lg font-black text-white hover:text-[#4ade80]" href="index.php?route=rewards">Rewards</a>
                    <a class="text-lg font-black text-white hover:text-[#4ade80]" href="index.php?route=gallery">Eco-Action Feed</a>
                    <a class="text-lg font-black text-white hover:text-[#4ade80]" href="index.php?route=partnerships">NGOs & Partners</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                      <div class="h-px bg-white/10 my-2"></div>
                      <a class="text-lg font-black text-[#4ade80]" href="index.php?route=<?php echo $_SESSION['role'] === 'user' ? 'user_dashboard' : ($_SESSION['role'] === 'ngo' ? 'ngo_dashboard' : 'admin_dashboard'); ?>">My Account</a>
                    <?php endif; ?>
                </nav>

                <div class="pt-8 border-t border-white/10 space-y-4">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                      <a href="index.php?route=login" class="block w-full text-center py-4 rounded-2xl bg-white/10 text-white font-black">Login</a>
                      <a href="index.php?route=register" class="block w-full text-center py-4 rounded-2xl bg-[#4ade80] text-primary font-black">Register</a>
                    <?php else: ?>
                      <form action="index.php?route=logout" method="post">
                        <?php echo csrf_field(); ?>
                        <button class="w-full py-4 text-center rounded-2xl bg-white/10 text-white font-black italic">Sign Out</button>
                      </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <script>
        function safeBack() {
            const ref = document.referrer;
            const currentUrl = window.location.href;
            
            // Logic: If we are on an admin management sub-page (like Users list) 
            // and we just came from a detail view, "Previous Page" should skip the detail 
            // view and go to the Admin Dashboard (the logical parent).
            if (currentUrl.includes('route=admin_users') && ref.includes('route=admin_user_view')) {
                window.location.href = 'index.php?route=admin_dashboard';
                return;
            }

            // General profile edit safety
            if (ref.includes('route=user_edit_profile') || ref.includes('route=ngo_profile_edit')) {
                window.location.href = 'index.php?route=home';
            } else {
                window.history.back();
            }
        }
        </script>
        <?php
        if (
            isset($_SESSION['user_id']) &&
            !in_array($currentRoute, $hideBackOnRoutes, true) &&
            !empty($_SERVER['HTTP_REFERER'])
        ):
        ?>
          <div class="max-w-7xl mx-auto w-full px-6 mt-6">
            <button
              onclick="safeBack();"
              class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-[#677e6b] hover:text-primary transition dark:text-gray-400 dark:hover:text-[#4ade80]"
            >
              <i data-lucide="chevron-left" class="w-4 h-4"></i> Previous Page
            </button>
          </div>
        <?php endif; ?>
        
        <!-- FLASH MESSAGES -->
        <div class="w-full max-w-7xl mx-auto px-6 mt-6">
          <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="bg-green-50 dark:bg-green-500/10 border border-green-100 dark:border-green-500/20 text-green-700 dark:text-green-400 px-6 py-4 rounded-2xl text-sm font-bold animate-fade-in flex items-center justify-between gap-3">
              <span class="flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                <?php echo h($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
              </span>
              <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600">
                <i data-lucide="x" class="w-4 h-4"></i>
              </button>
            </div>
          <?php endif; ?>

          <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 text-red-700 dark:text-red-400 px-6 py-4 rounded-2xl text-sm font-bold animate-fade-in flex items-center justify-between gap-3">
              <span class="flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                <?php echo h($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
              </span>
              <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">
                <i data-lucide="x" class="w-4 h-4"></i>
              </button>
            </div>
          <?php endif; ?>
        </div>

        <!-- PAGE CONTENT CONTAINER -->
        <main class="flex flex-1 flex-col py-8 pb-24">
