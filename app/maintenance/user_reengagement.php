<?php
// app/maintenance/user_reengagement.php
require_once __DIR__ . '/../config.php';

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../helpers/MailHelper.php';

/**
 * Re-engage inactive volunteers by suggesting upcoming drives
 */
function maintenance_reengage_inactive_volunteers(PDO $pdo, int $daysThreshold = 30): int
{
    // 1. Identify inactive users who:
    // - Haven't logged in for X days
    // - AND haven't been emailed for re-engagement in the last 14 days (prevent spam)
    // - AND are active accounts
    // - AND are 'user' role (volunteers)
    
    $thresholdDate = date('Y-m-d H:i:s', strtotime("-$daysThreshold days"));
    $stmt = $pdo->prepare("
        SELECT id, name, email 
        FROM users 
        WHERE role = 'user' 
          AND is_active = 1
          AND last_active_at < ?
        LIMIT 10
    ");
    $stmt->execute([$thresholdDate]);
    $inactiveUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($inactiveUsers)) {
        return 0;
    }

    // 2. Fetch top 3 upcoming open drives globally (or could be improved to proximity)
    $stmt = $pdo->prepare("
        SELECT p.*, n.name as ngo_name
        FROM projects p
        JOIN ngos n ON p.ngo_id = n.id
        WHERE p.status = 'open' 
          AND p.event_date >= CURRENT_DATE
        ORDER BY p.event_date ASC
        LIMIT 3
    ");
    $stmt->execute();
    $upcomingDrives = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($upcomingDrives)) {
        return 0; // No drives to suggest
    }

    $emailsSent = 0;
    foreach ($inactiveUsers as $user) {
        if (send_reengagement_email($user, $upcomingDrives)) {
            // Update the last emailed timestamp
            $upd = $pdo->prepare("UPDATE users SET last_reengagement_email_at = NOW() WHERE id = ?");
            $upd->execute([$user['id']]);
            $emailsSent++;
        }
    }

    return $emailsSent;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send the actual re-engagement email
 */
function send_reengagement_email(array $user, array $drives): bool {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->CharSet    = 'UTF-8';
        $mail->Host       = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'] ?? '';
        $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? '';
        $mail->SMTPSecure = ($_ENV['MAIL_ENCRYPTION'] === 'tls') ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $_ENV['MAIL_PORT'] ?? 587;

        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'] ?? 'support@ecoconnect.lk', $_ENV['MAIL_FROM_NAME'] ?? 'EcoConnect');
        $mail->addAddress($user['email'], $user['name']);

        $mail->isHTML(true);
        $mail->Subject = "We miss you on the Leaderboard, {$user['name']}! 🌿";

        $driveHtml = "";
        foreach ($drives as $d) {
            $driveHtml .= "
                <div style='background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 20px; margin-bottom: 15px;'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td style='vertical-align: top; width: 40px;'>
                                <div style='font-size: 24px;'>🌱</div>
                            </td>
                            <td>
                                <h4 style='margin: 0 0 5px 0; color: #121613; font-family: sans-serif; font-size: 16px; font-weight: 900;'>{$d['title']}</h4>
                                <div style='color: #677e6b; font-family: sans-serif; font-size: 12px; font-weight: 500; margin-bottom: 10px;'>
                                    📅 " . date('M d, Y', strtotime($d['event_date'])) . " &nbsp; | &nbsp; 📍 {$d['location']}
                                </div>
                                <a href='" . BASE_URL . "/index.php?route=event_show&id={$d['id']}' style='color: #2c4931; text-decoration: none; font-family: sans-serif; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;'>View Mission Details →</a>
                            </td>
                        </tr>
                    </table>
                </div>
            ";
        }

        $mail->Body = "
            <div style='background-color: #f9fafb; padding: 40px 20px; font-family: \"Public Sans\", sans-serif;'>
                <table width='100%' border='0' cellspacing='0' cellpadding='0' style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 40px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;'>
                    <!-- Header -->
                    <tr>
                        <td style='background-color: #2c4931; padding: 40px; text-align: center;'>
                            <div style='font-size: 40px; margin-bottom: 10px;'>🌿</div>
                            <h1 style='color: #ffffff; font-family: sans-serif; font-size: 24px; font-weight: 900; margin: 0; letter-spacing: -0.5px;'>EcoConnect</h1>
                            <p style='color: #4ade80; font-family: sans-serif; font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin: 5px 0 0 0;'>Warrior Check-in</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style='padding: 40px;'>
                            <h2 style='color: #121613; font-family: sans-serif; font-size: 20px; font-weight: 900; margin: 0 0 15px 0;'>Are you okay, {$user['name']}?</h2>
                            <p style='color: #677e6b; font-family: sans-serif; font-size: 14px; line-height: 1.6; font-weight: 500;'>
                                We noticed you haven't been active lately. Your spot on the leaderboard is waiting for you! The community misses your impact.
                            </p>
                            
                            <div style='margin: 30px 0;'>
                                <p style='color: #121613; font-family: sans-serif; font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;'>Upcoming Missions for You:</p>
                                {$driveHtml}
                            </div>
                            
                            <div style='text-align: center; margin-top: 30px;'>
                                <a href='" . BASE_URL . "/index.php?route=user_dashboard' style='display: inline-block; background-color: #2c4931; color: #ffffff; padding: 18px 40px; border-radius: 20px; text-decoration: none; font-family: sans-serif; font-size: 14px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 10px 20px rgba(44, 73, 49, 0.2);'>Return to Dashboard</a>
                                <p style='color: #677e6b; font-family: sans-serif; font-size: 11px; margin-top: 20px; font-style: italic;'>Sri Lanka needs your hands. Let's make an impact!</p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style='background-color: #f8fafc; padding: 30px; text-align: center; border-top: 1px solid #f1f5f9;'>
                            <p style='color: #94a3b8; font-family: sans-serif; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin: 0;'>Sent by EcoConnect Sri Lanka Global Ops</p>
                        </td>
                    </tr>
                </table>
            </div>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Re-engagement Email error: {$mail->ErrorInfo}");
        return false;
    }
}
