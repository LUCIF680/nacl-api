<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'application/libraries/vendor/autoload.php';

class Mail{
    static public function sendOTP($email){
            $mail = new PHPMailer(true);
                $mail->IsSMTP();
                $mail->Host = '	smtp.mail.yahoo.com';
                $mail->SMTPSecure = 'ssl';
                $mail->Username = 'pratikmazumdar680@yahoo.com';
                $mail->Password = 'xeqgnfcsfxpbwhak';
                $mail->Port = 465;
                $mail->SMTPAuth = true;
                $mail->setFrom('pratikmazumdar680@yahoo.com', 'NACL');
                $mail->addAddress($email);
                $realotp=mt_rand(100000,999999);
                $mail->isHTML(true);
                $mail->Subject = 'Authorize log-in';
                $mail->Body    = load_body($realotp);
                $mail->AltBody = 'Your Verification code is: '.$realotp;
                $mail->send();
                return $realotp;
        	function load_body($realotp){
			return'<span style="background:rgb(66,64,61);color:white;font-size:150%;padding:4%">
			Your Verification code is: '.$realotp.'</span>';
		}
    }
}
