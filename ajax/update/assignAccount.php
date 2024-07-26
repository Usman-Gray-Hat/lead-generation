<?php
include("../../dbConnect.php");
if(isset($_POST['id']) && isset($_POST['id'])!=="")
{
    $id = $_POST['id'];
    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $account1 = mysqli_real_escape_string($conn, $_POST['account1']);
    $account2 = mysqli_real_escape_string($conn, $_POST['account2']);

    $query = "UPDATE employees SET account1='$account1', account2='$account2' WHERE id='$id'";
    $exec = mysqli_query($conn,$query);
    if($exec==true)
    {
        if($account1==="Not Assigned" && $account2==="Not Assigned")
        {
            echo "Accounts has been unassigned";
        }
        else
        {
            echo "Account has been assigned";
        }
    }
    else
    {
        echo "Account has not been assigned";
    }
}
?>