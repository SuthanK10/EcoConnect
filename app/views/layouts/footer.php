        </main>

        <!-- FOOTER -->
        <footer class="bg-primary dark:bg-darkSurface border-t border-white/5 py-12 md:py-20">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12 text-center md:text-left">
                <div class="space-y-4">
                    <h3 class="text-xl font-black text-[#4ade80]">EcoConnect</h3>
                    <p class="text-sm text-white/70 dark:text-gray-400 leading-relaxed">Connecting communities for a cleaner, greener Sri Lanka. Join the movement today.</p>
                </div>
                <div>
                    <h4 class="text-xs font-black text-white dark:text-white uppercase tracking-widest mb-6">Explore</h4>
                    <ul class="space-y-4 text-sm font-bold text-white/60 dark:text-gray-400">
                        <li><a href="index.php?route=events" class="hover:text-[#4ade80] transition-colors">Cleanup Drives</a></li>
                        <li><a href="index.php?route=gallery" class="hover:text-[#4ade80] transition-colors">Eco-Action Feed</a></li>
                        <li><a href="index.php?route=rewards" class="hover:text-[#4ade80] transition-colors">Earn Rewards</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-black text-white dark:text-white uppercase tracking-widest mb-6">Company</h4>
                    <ul class="space-y-4 text-sm font-bold text-white/60 dark:text-gray-400">
                        <li><a href="#" class="hover:text-[#4ade80] transition-colors">Our Mission</a></li>
                        <li><a href="#" class="hover:text-[#4ade80] transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-[#4ade80] transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-black text-white dark:text-white uppercase tracking-widest mb-6">Follow Impact</h4>
                    <div class="flex justify-center md:justify-start gap-4">
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/10 dark:bg-darkBg border border-white/10 dark:border-white/10 flex items-center justify-center hover:bg-[#1DA1F2]/10 hover:border-[#1DA1F2]/30 hover:scale-110 transition-all shadow-sm group">
                            <i data-lucide="twitter" class="w-5 h-5 text-white/70 dark:text-gray-400 group-hover:text-[#1DA1F2] transition-colors"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/10 dark:bg-darkBg border border-white/10 dark:border-white/10 flex items-center justify-center hover:bg-[#1877F2]/10 hover:border-[#1877F2]/30 hover:scale-110 transition-all shadow-sm group">
                            <i data-lucide="facebook" class="w-5 h-5 text-white/70 dark:text-gray-400 group-hover:text-[#1877F2] transition-colors"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/10 dark:bg-darkBg border border-white/10 dark:border-white/10 flex items-center justify-center hover:bg-[#E4405F]/10 hover:border-[#E4405F]/30 hover:scale-110 transition-all shadow-sm group">
                            <i data-lucide="instagram" class="w-5 h-5 text-white/70 dark:text-gray-400 group-hover:text-[#E4405F] transition-colors"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-6 mt-12 pt-8 border-t border-white/10 text-center">
                <p class="text-[10px] font-black text-white/40 dark:text-gray-500 uppercase tracking-widest">&copy; 2025 ECO-CONNECT. ADVANCING ENVIRONMENTAL RESTORATION.</p>
            </div>
        </footer>
      </div>
    </div>

    <!-- NOTIFICATION SYSTEM -->
    <div id="inAppAlert" class="hidden fixed top-24 left-1/2 -translate-x-1/2 z-[9999] bg-[#2c4931] dark:bg-green-600 text-white px-8 py-5 rounded-[32px] font-black shadow-2xl animate-bounce border-4 border-white/20">
        <div class="flex items-center gap-3">
            <i data-lucide="bell" class="w-6 h-6 text-white animate-pulse"></i>
            <span id="alertMsg"></span>
        </div>
        <button onclick="document.getElementById('inAppAlert').classList.add('hidden')" class="ml-6 p-2 rounded-xl bg-white/10 hover:bg-white/20 transition-all flex items-center justify-center">
            <i data-lucide="x" class="w-4 h-4 text-white"></i>
        </button>
    </div>

    <script>
    // --- NAV LOGIC ---
    const mobileNav = document.getElementById('mobileNav');
    const navOverlay = document.getElementById('navOverlay');
    const menuOpen = document.getElementById('menuOpen');
    const menuClose = document.getElementById('menuClose');

    function toggleMenu(show) {
        if (show) {
            navOverlay.classList.remove('hidden');
            setTimeout(() => {
                navOverlay.classList.remove('opacity-0');
                mobileNav.classList.remove('translate-x-full');
            }, 10);
        } else {
            navOverlay.classList.add('opacity-0');
            mobileNav.classList.add('translate-x-full');
            setTimeout(() => navOverlay.classList.add('hidden'), 300);
        }
    }

    menuOpen?.addEventListener('click', () => toggleMenu(true));
    menuClose?.addEventListener('click', () => toggleMenu(false));
    navOverlay?.addEventListener('click', () => toggleMenu(false));

    // --- THEME LOGIC ---
    const themeToggle = document.getElementById('themeToggle');
    const sunIcon = document.getElementById('sunIcon');
    const moonIcon = document.getElementById('moonIcon');

    function updateIcons() {
        if (document.documentElement.classList.contains('dark')) {
            sunIcon.classList.remove('hidden');
            moonIcon.classList.add('hidden');
        } else {
            sunIcon.classList.add('hidden');
            moonIcon.classList.remove('hidden');
        }
    }

    updateIcons();

    themeToggle?.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        const isDark = document.documentElement.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateIcons();
    });

    // --- NOTIFICATION LOGIC ---
    function showNotification(event) {
        const bodyTextRaw = `Drive "${event.title}" starts in 1 hour! (${event.start_time})`;
        
        if ("Notification" in window && Notification.permission === "granted") {
            try {
                new Notification("EcoConnect Reminder", {
                    body: bodyTextRaw,
                    icon: "<?php echo BASE_URL; ?>/public/assets/logo.jpg"
                });
            } catch (e) {}
        }

        const alertDiv = document.getElementById('inAppAlert');
        const alertMsg = document.getElementById('alertMsg');
        if (alertDiv && alertMsg) {
            alertMsg.innerText = bodyTextRaw;
            alertDiv.classList.remove('hidden');
        }
    }

    function checkReminders() {
        fetch('<?php echo BASE_URL; ?>/index.php?route=check_notifications')
            .then(res => res.json())
            .then(data => {
                if (data && data.length > 0 && !data.error) {
                    data.forEach(event => showNotification(event));
                }
            }).catch(() => {});
    }

    window.addEventListener('load', () => {
        // Initialize Lucide Icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        setTimeout(checkReminders, 2000);
        setInterval(checkReminders, 60000);
        
        if ("Notification" in window && Notification.permission === "default") {
            // Silence permission request to avoid annoying users, 
            // but you could add a button elsewhere to request it.
        }
    });
    </script>
  </body>
</html>
