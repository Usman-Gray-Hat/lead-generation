<?php
include("../../dbConnect.php");
if(isset($_POST['full_name']) && isset($_POST['full_name'])!=="")
{
    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $father_name = mysqli_real_escape_string($conn, $_POST['father_name']);

    $cell_no = $_POST['cell_no'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $cnic_no = mysqli_real_escape_string($conn, $_POST['cnic_no']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $shift = mysqli_real_escape_string($conn, $_POST['shift']);
    $doj = $_POST['doj'];
    $added_by = mysqli_real_escape_string($conn, $_SESSION['name']);
    $admin_id = $_SESSION['id'];

    $query = "INSERT INTO employees (full_name, father_name, cell_no, email, cnic_no, address,
    shift, date_of_joining, added_by, admin_id_FK) VALUES
    ('$full_name','$father_name','$cell_no','$email','$cnic_no','$address','$shift','$doj','$added_by','$admin_id')";
    $exec = mysqli_query($conn,$query);
    if($exec===true)
    {
        echo "Employee has been added";
    }
    else
    {
        echo "Employee has not been added";
    }
}
?>