<?php
include("../../dbConnect.php");
include("PHPMailer/Config.php");

if(isset($_POST['username']) && isset($_POST['username'])!=="")
{
    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $query = "SELECT * FROM admins WHERE username='$username'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_num_rows($result);
    if($row>0)
    {
        $data = mysqli_fetch_assoc($result);
        $help_id = $data['id'];
        $name = mysqli_real_escape_string($conn, $data['name']);
        $email = mysqli_real_escape_string($conn, $data['email']);

        $query = "SELECT * FROM admins WHERE email='$email' AND id='$help_id'";
        $result = mysqli_query($conn,$query);
        $row = mysqli_num_rows($result);
        if($row>0)
        {
            $_SESSION['help_id'] = $help_id;
            $token = $_SESSION['token'] = MD5(rand());

            $mail->Subject = "Recover Password";
            $mail->Body = "Hey <b>$name</b>! <br> 
            You can recover your password by clicking on the below link: <br>
            <a href='http://192.168.100.128/lead-generation/reset_password.php?token=$token'>
            https://emeraldx/security/reset.php?token=$token </a> ";

            $mail->addAddress($email);
            $sendEmail = $mail->send();
            $mail->smtpClose();

	        if ($sendEmail==true) 
	        {
		        echo "The reset link has been sent to $email";
	        }
	        else
	        {
		        echo "Email not sent";
	        }            
        }
        else
        {
            echo "Something went wrong! We could not find your email to send you a reset link";
        }
    }
    else
    {
        echo "Invalid username";
    }
}
?>