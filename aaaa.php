<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'assets/PHPMailer-master/src/Exception.php';
require 'assets/PHPMailer-master/src/PHPMailer.php';
require 'assets/PHPMailer-master/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'support@shiojiapartelle.site';  // ✅ no line break
    $mail->Password   = 'Support@30214087695';           // your mailbox password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;     // ✅ SSL
    $mail->Port       = 465;                             // ✅ use 465 for SSL

    //Recipients
    $mail->setFrom('support@shiojiapartelle.site', 'Shioji Apartelle');
    $mail->addAddress('kentjoshuazamoradaborbor@gmail.com', 'Recipient Name');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = '<h3>Hello!</h3><p>This is a test email sent using <b>PHPMailer</b>.</p>';
    $mail->AltBody = 'Hello! This is a test email sent using PHPMailer.';

    $mail->send();
    echo 'Message has been sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
