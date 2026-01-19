<?php
// app/views/auth/register.php
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
  <!-- Background Decorations -->
  <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#2c4931]/5 rounded-full blur-3xl -tr-48 -mt-48"></div>
  <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-[#4ade80]/5 rounded-full blur-3xl -ml-48 -mb-48"></div>

  <div class="max-w-5xl w-full bg-white dark:bg-darkSurface rounded-[40px] shadow-2xl overflow-hidden border border-gray-100 dark:border-white/5 relative z-10">
    <div class="grid grid-cols-1 lg:grid-cols-12">
      
      <!-- Left: Branding & Steps -->
      <div class="lg:col-span-4 bg-[#2c4931] p-12 text-white flex flex-col justify-between relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('assets/hero2.webp')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>
        <div class="relative z-10">
          <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-lg mb-8">
            <i data-lucide="sprout" class="w-6 h-6 text-primary"></i>
          </div>
          <h2 class="text-3xl font-black leading-tight mb-6">Start Your Impact Journey</h2>
          <p class="text-white/70 font-medium">Whether you're an individual or an organization, your contribution matters to Sri Lanka's future.</p>
        </div>
        
        <div class="relative z-10 space-y-8">
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-xs font-black">01</div>
            <div>
              <p class="font-black text-sm uppercase tracking-widest text-[#4ade80]">Create Account</p>
              <p class="text-xs text-white/50">Basic info & credentials</p>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-xs font-black opacity-50">02</div>
            <div>
              <p class="font-black text-sm uppercase tracking-widest text-white/50">Setup Profile</p>
              <p class="text-xs text-white/30">Location & Role details</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Form Content -->
      <div class="lg:col-span-8 p-8 md:p-12">
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
          <div>
            <h1 class="text-3xl font-black text-[#121613] dark:text-white mb-2">Join EcoConnect</h1>
            <p class="text-[#677e6b] dark:text-gray-400 font-medium">Empower your environmental mission today.</p>
          </div>
          <p class="text-sm font-medium text-[#677e6b] dark:text-gray-400">
            Already a member? <a href="index.php?route=login" class="text-[#2c4931] dark:text-[#4ade80] font-black hover:underline">Log in</a>
          </p>
        </div>

        <?php if (!empty($error)): ?>
          <div class="mb-8 p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-red-700 dark:text-red-400 text-sm font-bold flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5"></i> <?php echo h($error); ?>
          </div>
        <?php elseif (!empty($success)): ?>
          <div class="mb-8 p-4 rounded-2xl bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-900/30 text-green-700 dark:text-green-400 text-sm font-bold flex items-center gap-3">
            <i data-lucide="sparkles" class="w-5 h-5"></i> <?php echo h($success); ?>
          </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="space-y-8">
          <?php echo csrf_field(); ?>
          <!-- Role Selection (Visual Cards) -->
          <?php 
            $selectedRole = $_POST['role'] ?? 'user';
          ?>
          <div class="grid grid-cols-2 gap-4">
            <label class="relative cursor-pointer group">
              <input type="radio" name="role" value="user" <?php echo $selectedRole === 'user' ? 'checked' : ''; ?> class="peer hidden">
              <div class="p-6 rounded-3xl bg-gray-50 dark:bg-white/5 border-2 border-transparent peer-checked:border-[#2c4931] peer-checked:bg-white dark:peer-checked:bg-darkSurface transition-all group-hover:bg-gray-100 dark:group-hover:bg-white/10 shadow-sm">
                <div class="w-10 h-10 bg-white dark:bg-darkBg rounded-xl flex items-center justify-center text-xl shadow-inner mb-4 text-[#2c4931] dark:text-[#4ade80]">
                    <i data-lucide="user"></i>
                </div>
                <p class="font-black text-sm uppercase tracking-widest text-[#121613] dark:text-white">Volunteer</p>
                <p class="text-[10px] font-bold text-[#677e6b] dark:text-gray-500 mt-1 uppercase">Join cleanup drives near you</p>
              </div>
              <div class="absolute top-4 right-4 text-[#2c4931] opacity-0 peer-checked:opacity-100 transition-opacity">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
              </div>
            </label>

            <label class="relative cursor-pointer group">
              <input type="radio" name="role" value="ngo" <?php echo $selectedRole === 'ngo' ? 'checked' : ''; ?> class="peer hidden">
              <div class="p-6 rounded-3xl bg-gray-50 dark:bg-white/5 border-2 border-transparent peer-checked:border-[#2c4931] peer-checked:bg-white dark:peer-checked:bg-darkSurface transition-all group-hover:bg-gray-100 dark:group-hover:bg-white/10 shadow-sm">
                <div class="w-10 h-10 bg-white dark:bg-darkBg rounded-xl flex items-center justify-center text-xl shadow-inner mb-4 text-[#2c4931] dark:text-[#4ade80]">
                    <i data-lucide="building"></i>
                </div>
                <p class="font-black text-sm uppercase tracking-widest text-[#121613] dark:text-white">Organization</p>
                <p class="text-[10px] font-bold text-[#677e6b] dark:text-gray-500 mt-1 uppercase">Host & Manage cleanup events</p>
              </div>
              <div class="absolute top-4 right-4 text-[#2c4931] opacity-0 peer-checked:opacity-100 transition-opacity">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
              </div>
            </label>
          </div>

          <!-- Account Info -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="group">
              <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Full Name</label>
              <input type="text" name="name" required value="<?php echo h($_POST['name'] ?? ''); ?>" placeholder="John Doe" class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
            </div>
            <div class="group">
              <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Email Address</label>
              <input type="email" name="email" required value="<?php echo h($_POST['email'] ?? ''); ?>" placeholder="john@example.com" class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
            </div>
            <div class="group">
              <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Password</label>
              <div class="relative">
                <input id="regPass" type="password" name="password" required placeholder="••••••••" class="block w-full px-5 pr-12 py-4 rounded-2xl border-2 border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
                <button type="button" onclick="togglePassword('regPass', 'eye1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400">
                  <span id="eye1"><i data-lucide="eye" class="w-4 h-4"></i></span>
                </button>
              </div>
              <p class="mt-2 text-[10px] text-[#677e6b] font-bold uppercase tracking-tighter">Min. 8 chars, numbers, @symbols & Caps</p>
            </div>
            <div class="group">
              <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Confirm Password</label>
              <div class="relative">
                <input id="regConfirm" type="password" name="password_confirm" required placeholder="••••••••" class="block w-full px-5 pr-12 py-4 rounded-2xl border-2 border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
                <button type="button" onclick="togglePassword('regConfirm', 'eye2')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400">
                  <span id="eye2"><i data-lucide="eye" class="w-4 h-4"></i></span>
                </button>
              </div>
            </div>
          </div>

          <!-- Volunteer Location Fields -->
          <div id="volunteerFields" class="space-y-6 pt-6 border-t border-gray-50 dark:border-white/5">
            <div class="group">
              <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Your Base City</label>
              <input type="text" name="city" id="locationInput" value="<?php echo h($_POST['city'] ?? ''); ?>" placeholder="e.g. Colombo, Galle..." class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
            </div>

            <div class="space-y-4">
              <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500">Pick Precise Location <span class="text-red-500 text-[14px]">*</span></label>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-[10px] font-black text-[#121613] dark:text-white uppercase tracking-widest mb-2">Latitude</label>
                  <input type="text" name="latitude" id="lat" value="<?php echo h($_POST['latitude'] ?? ''); ?>" placeholder="6.1234" class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
                </div>
                <div>
                  <label class="block text-[10px] font-black text-[#121613] dark:text-white uppercase tracking-widest mb-2">Longitude</label>
                  <input type="text" name="longitude" id="lng" value="<?php echo h($_POST['longitude'] ?? ''); ?>" placeholder="80.1234" class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
                </div>
              </div>
              <button type="button" onclick="getLocation()" class="w-full py-4 rounded-2xl bg-white dark:bg-white/5 border-2 border-dashed border-[#2c4931]/20 dark:border-white/10 text-[#2c4931] dark:text-[#4ade80] text-xs font-black uppercase tracking-widest hover:border-[#2c4931] dark:hover:border-[#4ade80] transition-all flex items-center justify-center gap-2">
                <i data-lucide="map-pin" class="w-4 h-4"></i> Use Current Location
              </button>
              <p class="text-[11px] font-bold text-[#677e6b] italic">Marking your location is required to notify you of cleanup drives in your area!</p>
            </div>
          </div>

          <!-- NGO ONLY FIELDS -->
          <div id="ngoFields" class="hidden space-y-6 pt-6 border-t border-gray-50 dark:border-white/5">
            <div class="group">
              <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">NGO / Organization Name <span class="text-red-500">*</span></label>
              <input type="text" name="ngo_name" value="<?php echo h($_POST['ngo_name'] ?? ''); ?>" class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
            </div>

            <div class="group">
              <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Organization Logo <span class="text-red-500">*</span></label>
              <input type="file" name="ngo_logo" accept="image/*" class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
              <p class="mt-2 text-[10px] font-bold text-[#677e6b]">Upload a clear logo (JPG, PNG). Max 2MB.</p>
            </div>

            <div class="group">
              <label class="block text-xs font-black uppercase tracking-widest text-[#677e6b] dark:text-gray-500 mb-2">Verification Link (URL) <span class="text-red-500">*</span></label>
              <input type="url" name="verification_link" value="<?php echo h($_POST['verification_link'] ?? ''); ?>" placeholder="https://..." class="block w-full px-5 py-4 rounded-2xl border-2 border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-darkBg text-[#121613] dark:text-white focus:ring-4 focus:ring-[#2c4931]/10 focus:border-[#2c4931] transition-all outline-none font-medium">
              <p class="mt-2 text-[10px] font-bold text-[#677e6b]">Link to your official website or registry for admin verification.</p>
            </div>
            
            <div class="p-5 bg-blue-50 dark:bg-blue-900/10 rounded-2xl flex gap-4">
              <span class="text-xl text-[#2c4931] dark:text-[#4ade80]">
                <i data-lucide="info"></i>
              </span>
              <p class="text-xs font-bold text-blue-700 dark:text-blue-400 leading-relaxed uppercase tracking-tighter">Your NGO profile will be reviewed by an administrator before you can host events. We prioritize verified environmental organizations.</p>
            </div>
          </div>

          <button type="submit" class="w-full py-5 rounded-2xl bg-[#2c4931] text-white text-lg font-black shadow-xl hover:bg-[#121613] transition-all transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
            Complete Registration <i data-lucide="check" class="w-5 h-5"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script>
  const roleRadios = document.querySelectorAll('input[name="role"]');
  const ngoFields = document.getElementById('ngoFields');
  const volunteerFields = document.getElementById('volunteerFields');
  const ngoNameInput = document.querySelector('input[name="ngo_name"]');
  const verificationInput = document.querySelector('input[name="verification_link"]');
  const cityInput = document.getElementById('locationInput');
  const latInput = document.getElementById('lat');
  const lngInput = document.getElementById('lng');

  function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            latInput.value = position.coords.latitude;
            lngInput.value = position.coords.longitude;
            alert('Location captured successfully!');
        }, error => {
            alert('Error getting location: ' + error.message);
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
  }

  function toggleRoleFields() {
    const selected = document.querySelector('input[name="role"]:checked').value;
    const isNgo = (selected === 'ngo');

    ngoFields.classList.toggle('hidden', !isNgo);
    volunteerFields.classList.toggle('hidden', isNgo);

    ngoNameInput.required = isNgo;
    verificationInput.required = isNgo;
    cityInput.required = !isNgo;
  }

  function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      if (input.type === 'password') {
          input.type = 'text';
          icon.innerHTML = '<i data-lucide="eye-off" class="w-4 h-4"></i>';
      } else {
          input.type = 'password';
          icon.innerHTML = '<i data-lucide="eye" class="w-4 h-4"></i>';
      }
      lucide.createIcons();
  }

  roleRadios.forEach(radio => radio.addEventListener('change', toggleRoleFields));
  toggleRoleFields();
</script>
