<?php
include("../../dbConnect.php");
if(isset($_POST['employee_edit_id']) && isset($_POST['full_name']) && isset($_POST['doj']))
{
    $id = $_POST['employee_edit_id'];

    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $father_name = mysqli_real_escape_string($conn, $_POST['father_name']);
    $cell_no = $_POST['cell_no'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $cnic_no = mysqli_real_escape_string($conn, $_POST['cnic_no']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $shift = mysqli_real_escape_string($conn, $_POST['shift']);
    $doj = $_POST['doj'];
    $first_sale = $_POST['first_sale'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $query = "UPDATE employees SET full_name='$full_name',father_name='$father_name',cell_no='$cell_no',
    email='$email',cnic_no='$cnic_no',address='$address', shift='$shift', date_of_joining='$doj',
    first_sale='$first_sale',status='$status' WHERE id='$id'";
    $exec = mysqli_query($conn,$query);
    if($exec==true)
    {
        echo "Employee record has been updated";
    }
    else
    {
        echo "Employee record has not been updated";
    }
}
?>