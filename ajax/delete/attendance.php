<?php
include("../../dbConnect.php");
if(isset($_POST['id']) && isset($_POST['id'])!=="")
{
    $id = $_POST['id'];
    $query = "DELETE FROM attendance WHERE id='$id'";
    $exec = mysqli_query($conn,$query);
    if($exec==true)
    {
        echo "Absent has been removed";
    }
    else
    {
        echo "Absent has not been removed";
    }
}
?>