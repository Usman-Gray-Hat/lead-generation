<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
include("PHPMailer.php");
include("Exception.php");
include("SMTP.php");

// Instance of PHPMailer
$mail = new PHPMailer();

// SMTP Configuration
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "tls";
$mail->Host = "smtp.gmail.com";
$mail->Port = "587";

// Credentials
$mail->Username = "usmanhameed1790@gmail.com";
$mail->Password = "ltab ebam wsoy ufpz";
$mail->setFrom("usmanhameed1790@gmail.com","EmeraldX.Ltd Pvt");

// Content
$mail->isHTML(true);
$mail->CharSet = "UTF-8";
?>