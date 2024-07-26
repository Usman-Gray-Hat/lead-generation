<?php
include("../../dbConnect.php");
if(isset($_POST['current_password']) && isset($_POST['new_password']))
{
    if(isset($_SESSION['id']))
    {
        $id = $_SESSION['id'];
        $current_password = MD5($_POST['current_password']);
        $new_password = MD5($_POST['new_password']);
    
        $query = "SELECT * FROM admins WHERE id='$id'";
        $result = mysqli_query($conn,$query);
        $rows = mysqli_num_rows($result);
        if($rows>0)
        {
            $data = mysqli_fetch_assoc($result);
            if($current_password!==$data['password'])
            {
                echo "Current password is incorrect";
            }
            else
            {
                $query = "UPDATE admins SET password='$new_password' WHERE id='$id'";
                $exec = mysqli_query($conn,$query);
                if($exec==true)
                {
                    echo "Your password has been updated";
                }
                else
                {
                    echo "Your password has not been updated";
                }
            }
        }
    }

}
?>