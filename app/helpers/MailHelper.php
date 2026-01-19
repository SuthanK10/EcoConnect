<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../lib/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../lib/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/src/SMTP.php';

function send_password_reset_email(string $toEmail, string $resetLink): bool
{
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration (Gmail example)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'toplaygames108@gmail.com';   
        $mail->Password   = 'xcqzbhzmgelptohc';     
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email details
        $mail->setFrom('yourgmail@gmail.com', 'EcoConnect');
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
