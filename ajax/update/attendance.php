<?php
include("../../dbConnect.php");
if(isset($_POST['id']) && isset($_POST['reason']) && isset($_POST['date_of_absence']))
{
    $id = $_POST['id']; // Atendance hidden id (Unique)
    
    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $date_of_absence = $_POST['date_of_absence'];
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    $query = "UPDATE attendance SET type='$type', reason='$reason', date_created='$date_of_absence' WHERE id='$id'";
    $exec = mysqli_query($conn,$query);
    if($exec==true)
    {
        echo "Record has been updated";
    }
    else
    {
        echo "Record has not been updated";
    }
}
?>