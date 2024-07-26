<?php
include("../../dbConnect.php");
if(isset($_POST['employee_id']) && isset($_POST['employee_name']))
{
    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $employee_id = $_POST['employee_id'];
    $employee_name = mysqli_real_escape_string($conn, $_POST['employee_name']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $date_of_absence = $_POST['date_of_absence'];
    $marked_by = mysqli_real_escape_string($conn, $_SESSION['name']);

    $query = "SELECT * FROM attendance WHERE employee_id_FK='$employee_id' AND employee_name='$employee_name'
    AND date_created='$date_of_absence'";
    $result = mysqli_query($conn,$query);
    $rows = mysqli_num_rows($result);
    if($rows>0)
    {
        // Extracting name of the admin who already marked this employee as absent for this date.
        $data = mysqli_fetch_assoc($result);
        $name = $data['marked_by'];
        $type = $data['type'];
        $formattedDate = date('F-d-Y',strtotime($date_of_absence));
        echo "$name has already marked $type of $employee_name for $formattedDate";
    }
    else
    {
        $admin_id = $_SESSION['id'];
        $query = "INSERT INTO attendance (employee_id_FK,employee_name,type,reason,marked_by,date_created,admin_id_FK) VALUES
        ('$employee_id','$employee_name','$type','$reason','$marked_by','$date_of_absence','$admin_id')";
        $exec = mysqli_query($conn,$query);
        if($exec==true)
        {
            echo "Absent has been marked";
        }
        else
        {
            echo "Absent has not been marked";
        }
    }
}
?>