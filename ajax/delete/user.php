<?php
include("../../dbConnect.php");
if(isset($_POST['id']) && isset($_POST['id'])!=="")
{
    $id = $_POST['id'];
    $query = "DELETE FROM admins WHERE id='$id'";
    $exec = mysqli_query($conn,$query);
    if($exec==true)
    {
        echo "User has been deleted";
    }
    else
    {
        echo "User has not been deleted";
    }
}
?>