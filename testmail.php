<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'assets/PHPMailer-master/src/Exception.php';
require 'assets/PHPMailer-master/src/PHPMailer.php';
require 'assets/PHPMailer-master/src/SMTP.php';



$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'support@shibokbill.space';
    $mail->Password   = 'Shibokbill@302';  // <-- Replace if needed
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    // Recipients
    $mail->setFrom('support@shibokbill.space', 'Shioji Apartelle');
    $mail->addAddress('kentjoshuazamoradaborbor@gmail.com', 'Kent Joshua Daborbor');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = '<h3>Hello!</h3><p>This is a test email from <b>PHPMailer</b> using Hostinger SMTP.</p>';
    $mail->AltBody = 'This is a test email from PHPMailer using Hostinger SMTP.';

    $mail->send();
    echo '✅ Test email sent successfully!';
} catch (Exception $e) {
    echo "❌ Email failed to send. Error: {$mail->ErrorInfo}";
}
