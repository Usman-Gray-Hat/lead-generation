<?php
include("../../dbConnect.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
include("PHPMailer/PHPMailer.php");
include("PHPMailer/Exception.php");
include("PHPMailer/SMTP.php");

if(isset($_POST['username']) && isset($_POST['username'])!=="")
{
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $query = "SELECT * FROM admins WHERE username='$username'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_num_rows($result);
    if($row>0)
    {
        $data = mysqli_fetch_assoc($result);
        $email = mysqli_real_escape_string($conn, $data['email']);
        echo $email;
    }
}
?>