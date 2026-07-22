<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

function sendEmail($toEmail, $toName, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // YOUR GMAIL
        $mail->Username   = 'cybershield540@gmail.com';

        // YOUR APP PASSWORD
        $mail->Password   = 'vsndudzjubzjzonh';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender
        $mail->setFrom('cybershield540@gmail.com', 'CyberShield');

        // Receiver
        $mail->addAddress($toEmail, $toName);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();

        return true;

    } 
    catch (Exception $e) {
    return false;
}

}
?>