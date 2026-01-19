<?php
require_once __DIR__ . '/../config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../lib/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../lib/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/src/SMTP.php';

function send_password_reset_email(string $toEmail, string $resetLink): bool
{
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'] ?? '';
        $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? '';
        $mail->SMTPSecure = ($_ENV['MAIL_ENCRYPTION'] === 'tls') ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $_ENV['MAIL_PORT'] ?? 587;

        // Email details
        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'] ?? 'support@ecoconnect.lk', $_ENV['MAIL_FROM_NAME'] ?? 'EcoConnect');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Reset your EcoConnect password';

        $mail->Body = "
            <p>You requested a password reset for your EcoConnect account.</p>
            <p>Click the button below to reset your password:</p>
            <p>
              <a href='{$resetLink}'
                 style='background:#2c4931;color:white;padding:10px 15px;
                        text-decoration:none;border-radius:6px;font-weight:bold'>
                Reset Password
              </a>
            </p>
            <p>This link will expire in 30 minutes.</p>
            <p>If you did not request this, please ignore this email.</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Email error: {$mail->ErrorInfo}");
        return false;
    }
}
