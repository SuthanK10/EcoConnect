<?php
// app/views/user/my_cleanups.php
?>

<div class="max-w-5xl mx-auto px-4 py-12">
  <!-- Page Header -->
  <div class="mb-10">
    <h1 class="text-[#121613] dark:text-white text-3xl font-black tracking-tight mb-2">My Impact Journey</h1>
    <p class="text-sm text-[#677e6b] dark:text-gray-400">Track your volunteer history and share your contributions with the world.</p>
  </div>

  <?php if (!empty($applications)): ?>
    <div class="grid grid-cols-1 gap-6">
      <?php foreach ($applications as $row): ?>
        <?php 
          $status = strtolower($row['status']);
          $isCompleted = ($status === 'completed');
          $statusColor = $isCompleted ? 'bg-[#4ade80]/10 text-[#2c4931]' : 'bg-orange-50 text-orange-600';
          $statusText = $isCompleted ? 'Completed' : 'Applied';
          $statusIcon = $isCompleted ? '<i data-lucide="check-circle-2" class="w-3 h-3"></i>' : '<i data-lucide="clock" class="w-3 h-3"></i>';
          $hasFeedback = !empty($row['feedback_id']);
        ?>
        
        <div class="group bg-white dark:bg-darkSurface rounded-[32px] border border-gray-100 dark:border-white/10 p-8 shadow-sm hover:shadow-xl transition-all duration-300">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            
            <!-- Left Side: Event Info -->
            <div class="flex-1">
              <div class="flex items-center gap-3 mb-3">
                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5 <?php echo $statusColor; ?>">
                  <?php echo $statusIcon; ?> <?php echo $statusText; ?>
                </span>
                <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">
                  Applied on <?php echo date('M d, Y', strtotime($row['applied_at'])); ?>
                </span>
              </div>
              
              <h3 class="text-xl font-black text-[#121613] dark:text-white mb-4 group-hover:text-[#2c4931] dark:group-hover:text-[#4ade80] transition-colors">
                <?php echo h($row['title']); ?>
              </h3>
              
              <div class="flex flex-wrap gap-4">
                <div class="flex items-center gap-2 text-xs font-bold text-[#677e6b] dark:text-gray-400">
                  <span class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80]">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                  </span>
                  <?php echo h($row['location']); ?>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-[#677e6b] dark:text-gray-400">
                  <span class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-white/5 flex items-center justify-center text-primary dark:text-[#4ade80]">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                  </span>
                  <?php echo h($row['event_date']); ?>
                </div>
              </div>
            </div>

            <!-- Right Side: Actions -->
            <div class="flex flex-col gap-3 min-w-[240px]">
              <?php if ($isCompleted): ?>
                <div class="flex flex-col gap-2">
                  <!-- Feedback Button -->
                  <?php if (!$hasFeedback): ?>
                    <a href="index.php?route=feedback&project_id=<?php echo $row['project_id']; ?>" class="w-full py-3 rounded-xl bg-primary text-white text-[10px] font-black uppercase text-center hover:bg-primary-dark transition-all flex items-center justify-center gap-2">
                      <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                      Share Feedback
                    </a>
                  <?php else: ?>
                    <button disabled class="w-full py-3 rounded-xl bg-gray-100 text-gray-400 text-[10px] font-black uppercase text-center flex items-center justify-center gap-2 cursor-not-allowed">
                      <i data-lucide="check" class="w-3.5 h-3.5"></i>
                      Feedback Submitted
                    </button>
                  <?php endif; ?>

                  <!-- Internal Feed Button -->
                  <a href="index.php?route=gallery&project_id=<?php echo $row['project_id']; ?>" class="w-full py-3 rounded-xl bg-[#2c4931] text-white text-[10px] font-black uppercase text-center hover:bg-[#1a2e1f] transition-all flex items-center justify-center gap-2">
                    <i data-lucide="image" class="w-3.5 h-3.5"></i>
                    Post to Eco-Action Feed
                  </a>

                  <!-- Social Sharing -->
                  <div class="bg-[#f0f5f1] dark:bg-white/5 rounded-2xl p-3 border border-[#2c4931]/5 dark:border-white/10 mt-2">
                    <p class="text-[9px] font-black text-[#2c4931] dark:text-[#4ade80] uppercase tracking-widest mb-2 text-center">Share to Socials</p>
                    <div class="flex justify-center gap-2">
                      <?php 
                        $shareText = "I just completed a cleanup drive at " . $row['location'] . " with @EcoConnectSriLanka! Join me in making Sri Lanka cleaner. #EcoConnect #CleanupSriLanka";
                        $shareUrl = "https://eco-connect.lk/events/" . $row['project_id']; 
                      ?>
                      <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($shareText); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-black flex items-center justify-center text-white hover:scale-110 transition-transform shadow-sm" title="Share on X">
                        <i data-lucide="twitter" class="w-3.5 h-3.5 text-white"></i>
                      </a>
                      <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($shareUrl); ?>&quote=<?php echo urlencode($shareText); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-[#1877F2] flex items-center justify-center text-white hover:scale-110 transition-transform shadow-sm" title="Share on Facebook">
                        <i data-lucide="facebook" class="w-4 h-4 text-white"></i>
                      </a>
                      <a href="https://wa.me/?text=<?php echo urlencode($shareText); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-[#25D366] flex items-center justify-center text-white hover:scale-110 transition-transform shadow-sm" title="Share on WhatsApp">
                        <i data-lucide="message-circle" class="w-4 h-4 text-white"></i>
                      </a>
                    </div>
                  </div>
                </div>
              <?php else: ?>
                <?php
                  $title_cal = urlencode("Cleanup Drive: " . $row['title']);
                  $loc_cal = urlencode($row['location']);
                  $desc_cal = urlencode("Organized by: " . $row['ngo_name'] . "\n\n" . $row['description']);
                  $dateStr = $row['event_date'];
                  $startStr = $row['start_time'] ?: '08:00:00';
                  $endStr = $row['end_time'] ?: '10:00:00';
                  $startFull = str_replace('-', '', $dateStr) . 'T' . str_replace(':', '', $startStr);
                  $endFull = str_replace('-', '', $dateStr) . 'T' . str_replace(':', '', $endStr);
                  $gCalUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$title_cal}&dates={$startFull}/{$endFull}&details={$desc_cal}&location={$loc_cal}";
                ?>
                <div class="flex flex-col gap-2">
                  <a href="index.php?route=event_show&id=<?php echo $row['project_id']; ?>" class="w-full py-3 rounded-xl bg-[#ebefec] dark:bg-white/5 text-[#2c4931] dark:text-white text-[10px] font-black uppercase text-center hover:bg-[#2c4931] dark:hover:bg-[#4ade80] hover:text-white dark:hover:text-primary transition-all flex items-center justify-center gap-1.5">
                    View Details <i data-lucide="chevron-right" class="w-3 h-3"></i>
                  </a>
                  <a href="<?php echo $gCalUrl; ?>" target="_blank" class="w-full py-3 rounded-xl bg-[#e0f2fe] dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase text-center hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center gap-2">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
                    Google Calendar
                  </a>
                </div>
              <?php endif; ?>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="text-center py-24 bg-white dark:bg-darkSurface rounded-[40px] border border-dashed border-gray-200 dark:border-white/10">
      <div class="text-6xl mb-6 flex justify-center text-gray-300">
        <i data-lucide="sprout" class="w-20 h-20"></i>
      </div>
      <h3 class="text-xl font-black text-[#121613] dark:text-white mb-2">No cleanup history yet</h3>
      <p class="text-[#677e6b] dark:text-gray-400 mb-8">You haven't applied for any cleanup drives. Start your impact journey today!</p>
      <a href="index.php?route=events" class="inline-flex items-center justify-center rounded-2xl bg-[#2c4931] px-8 py-4 text-sm font-black text-white shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
        Explore Cleanup Drives
      </a>
    </div>
  <?php endif; ?>
</div>
