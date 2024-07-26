<?php
include("../../dbConnect.php");
if(isset($_POST['name']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['type']))
{
    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = MD5($_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);

    $query = "SELECT * FROM admins WHERE username='$username'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_num_rows($result);
    if($row>0)
    {
        echo "This username has already taken. Please try different username";
    }
    else
    {
        $query = "SELECT * FROM admins WHERE email='$email'";
        $result = mysqli_query($conn,$query);
        $row2 = mysqli_num_rows($result);
        if($row2>0)
        {
            echo "This email has already taken by another user. Please try different email";
        }
        else
        {
            $query = "INSERT INTO admins (name, username, email ,password, role ,type) VALUES
            ('$name','$username','$email','$password','$role','$type')";
            $exec = mysqli_query($conn,$query);
            if($exec===true)
            {
                echo "User has been added";
            }
            else
            {
                echo "User has not been added";
            }
        }
    }
}
?>