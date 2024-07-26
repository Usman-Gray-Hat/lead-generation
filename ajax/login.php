<?php
include("../dbConnect.php");
if(isset($_POST['username']) && isset($_POST['username'])!=="" && isset($_POST['password']) && isset($_POST['password'])!=="")
{
    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = MD5($_POST['password']);
	$query = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
	$result = mysqli_query($conn,$query);
	$row = mysqli_num_rows($result);
    if($row==1)
    {
        $data = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $data['id']; // Admin ID
        $_SESSION['employee_id'] = $data['employee_id_FK']; // Employee ID
        $_SESSION['name'] = $data['name'];
        $_SESSION['login_type'] = $data['type'];
        $_SESSION['role'] = $data['role'];
        echo "Login Successful";
    }
    else
    {
        echo "Invalid username or password";
    }
}
?>