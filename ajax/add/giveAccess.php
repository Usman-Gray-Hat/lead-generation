<?php
include("../../dbConnect.php");
if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['username']) && isset($_POST['password']))
{
    $employee_id = $_POST['id'];

    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = MD5($_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $query = "SELECT * FROM admins WHERE employee_id_FK='$employee_id'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_num_rows($result);
    if($row>0)
    {
        echo "Access has already been given to this user";
    }
    else
    {
        $query = "SELECT * FROM admins WHERE username='$username'";
        $result = mysqli_query($conn,$query);
        $row = mysqli_num_rows($result);
        if($row>0)
        {
            echo "This username has already taken by another user. Please try different username.";
        }
        else
        {
            $query = "SELECT * FROM admins WHERE email='$email'";
            $result = mysqli_query($conn,$query);
            $row = mysqli_num_rows($result);
            if($row>0)
            {
                echo "This email has already taken by another user. Please try different email.";
            }
            else
            {
                $query = "INSERT INTO admins (name, username, email, password,role,type,employee_id_FK) VALUES
                ('$name','$username','$email','$password','$role',2,'$employee_id')";
                $exec = mysqli_query($conn,$query);
                if($exec===true)
                {
                    echo "Access has been given";
                }
                else
                {
                    echo "Access has not been given";
                }
            }
        }
    }
}
?>