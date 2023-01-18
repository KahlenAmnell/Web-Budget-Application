<?php

namespace App;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use \App\Config;

//require 'vendor/autoload.php';

/**
 * Mail by PHPMailer
 */
class Mail
{
    /**
     * Send a message
     * 
     * @param string $to Recipient
     * @param string $subject Subjest
     * @param string $text Text only content of the message
     * @param string $html HTML content of the message
     * 
     * @return mixed
     */
    public static function send($to, $subject, $text, $html)
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        
        $mail->Host = Config::MAIL_HOST;
        $mail->Port = Config::MAIL_PORT;
        $mail-> SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;

        $mail->Username = Config::MAIL_USERNAME;
        $mail->Password = Config::MAIL_PASSWORD;

        $mail->CharSet = 'UTF-8';
        $mail->setFrom('no-reply@domena.pl', 'Aplikacja budżetowa');

        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html;
        $mail->AltBody = $text;

        if (!$mail->send()) {
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            //echo 'Message has been sent';
        }
    }
}
