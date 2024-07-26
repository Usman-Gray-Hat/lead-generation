<?php
include("../../dbConnect.php");
if(isset($_POST['id']) && isset($_POST['id'])!=="")
{
    $id = $_POST['id'];
    $query = "DELETE FROM account_credentials WHERE id='$id'";
    $exec = mysqli_query($conn,$query);
    if($exec==true)
    {
        echo "Account has been deleted";
    }
    else
    {
        echo "Account has not been deleted";
    }
}
?>