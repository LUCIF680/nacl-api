<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

class Mail{
    function sendOTP($email){
            $mail = new PHPMailer(true);
            try{
                $mail->IsSMTP();
                $mail->Host = '	n3plcpnl0050.prod.ams3.secureserver.net';
                $mail->SMTPSecure = 'ssl';
                $mail->Username = 'help@itarimusic.com';
                $mail->Password = 'ProtonMail.com@157';
                $mail->Port = 465;
                $mail->SMTPAuth = true;
                $mail->setFrom('help@itarimusic.com', 'Motaku');
                $mail->addAddress($email);
                $realotp=mt_rand(100000,999999);
                $mail->isHTML(true);
                $mail->Subject = 'Authorize log-in';
                $mail->Body    = '<span style="background:rgb(66,64,61);color:white;font-size:150%;padding:4%">Your Verification code is: '.$realotp.'</span>';
                $mail->AltBody = 'Your Verification code is: '.$realotp;
                $mail->send();
                return $realotp;     //Will be used next page for otp verification
            }catch (Exception $e) {

        	}
    }
}
