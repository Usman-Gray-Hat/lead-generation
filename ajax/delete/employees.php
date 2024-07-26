<?php
include("../../dbConnect.php");
if(isset($_POST['employee_delete_id']) && isset($_POST['employee_delete_id'])!=="")
{
    $id = $_POST['employee_delete_id'];
    $query = "DELETE FROM employees WHERE id='$id'";
    $exec = mysqli_query($conn,$query);
    if($exec==true)
    {
        echo "Employee record has been deleted";
    }
    else
    {
        echo "Employee record has not been deleted";
    }
}
?>