<?php
// app/views/ngo/attendance.php
?>

<h2 class="text-[22px] font-bold text-[#121613] mb-3 px-4">
  Attendance – <?= h($project['title']) ?>
</h2>

<p class="px-4 text-sm text-[#677e6b] mb-4">
  <?= h($project['location']) ?> • <?= h($project['event_date']) ?>
</p>

<div class="px-4 pb-6">

  <?php if (empty($attendance)): ?>
    <div class="rounded-lg border border-[#d8dfd9] bg-white p-4">
      <p class="text-sm text-[#677e6b]">
        No attendance records yet.
      </p>
    </div>
  <?php else: ?>

    <div class="overflow-x-auto rounded-lg border border-[#d8dfd9] bg-white">
      <table class="w-full text-sm">
        <thead class="bg-[#f0f5f1] text-[#121613]">
          <tr>
            <th class="px-4 py-2 text-left">Volunteer</th>
            <th class="px-4 py-2 text-left">Check-in</th>
            <th class="px-4 py-2 text-left">Check-out</th>
            <th class="px-4 py-2 text-left">Hours</th>
            <th class="px-4 py-2 text-left">Points</th>
            <th class="px-4 py-2 text-left">Status</th>
          </tr>
        </thead>
        <tbody>

          <?php foreach ($attendance as $row): ?>
            <?php
              $checkedOut = !empty($row['checkout_time']);
              $hours = $checkedOut
                ? floor((strtotime($row['checkout_time']) - strtotime($row['checkin_time'])) / 3600)
                : 0;
            ?>
            <tr class="border-t">
              <td class="px-4 py-2 font-medium">
                <?= h($row['user_name']) ?>
              </td>

              <td class="px-4 py-2">
                <?= h(date('H:i', strtotime($row['checkin_time']))) ?>
              </td>

              <td class="px-4 py-2">
                <?= $checkedOut ? h(date('H:i', strtotime($row['checkout_time']))) : '—' ?>
              </td>

              <td class="px-4 py-2">
                <?= $checkedOut ? max(1, $hours) : '—' ?>
              </td>

              <td class="px-4 py-2 font-semibold">
                <?= $checkedOut ? (int)$row['points_awarded'] : '—' ?>
              </td>

              <td class="px-4 py-2">
                <?php if ($checkedOut): ?>
                  <span class="rounded bg-green-100 px-2 py-1 text-xs text-green-700">
                    Completed
                  </span>
                <?php else: ?>
                  <span class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-700">
                    Checked in only
                  </span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>

        </tbody>
      </table>
    </div>

  <?php endif; ?>

</div>

<div class="px-4 mt-4">
  <a
    href="index.php?route=ngo_dashboard"
    class="inline-flex items-center justify-center rounded-lg bg-[#2c4931] px-4 py-2 text-sm font-bold text-white"
  >
    Back to Dashboard
  </a>
</div>
