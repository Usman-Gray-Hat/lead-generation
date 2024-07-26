<?php
include("../../dbConnect.php");
if(isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_SESSION['help_id']) && isset($_SESSION['token']))
{
    $id = $_SESSION['help_id']; 
    $password = MD5($_POST['password']);

    $query = "UPDATE admins SET password='$password' WHERE id='$id'";
    $exec = mysqli_query($conn,$query);
    if($exec==true)
    {
        echo "Your password has been reset";
        unset($_SESSION['help_id']);
        unset($_SESSION['token']);
    }
    else
    {
        echo "Your password has not been reset";
    }
}
?>