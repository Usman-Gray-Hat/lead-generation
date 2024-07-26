<?php
include("../../dbConnect.php");
if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['username']) && isset($_POST['type']))
{
    $id = $_POST['id'];
    
    // Using "mysqli_real_escape_string" function to prevent getting mysqli error on single quotes
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);

    // Prevent duplication of Username
    $pre_query = "SELECT id FROM admins WHERE username='$username'";
    $result = mysqli_query($conn,$pre_query);
    $rows = mysqli_num_rows($result);
    if($rows>0)
    {
        $data = mysqli_fetch_assoc($result);
        $existed_admin_id = $data['id'];
        if($existed_admin_id===$id)
        {
            // Prevent duplication of Email
            $pre_query2 = "SELECT id FROM admins WHERE email='$email'";
            $result2 = mysqli_query($conn,$pre_query2);
            $rows2 = mysqli_num_rows($result2);
            if($rows2>0)
            {
                $data2 = mysqli_fetch_assoc($result2);
                $existed_admin_id = $data2['id'];
                if($existed_admin_id===$id)
                {
                    $query = "UPDATE admins SET name='$name', username='$username',
                    email='$email', role='$role', type='$type' WHERE id='$id'";
                    $exec = mysqli_query($conn,$query);
                    if($exec==true)
                    {
                        echo "User has been updated";
                    }
                    else
                    {
                        echo "User has not been updated";
                    }
                }
                else
                {
                    echo "This email has already taken by another user. Please try different email";
                }
            }
            else
            {
                $query = "UPDATE admins SET name='$name', username='$username',
                email='$email', role='$role', type='$type' WHERE id='$id'";
                $exec = mysqli_query($conn,$query);
                if($exec==true)
                {
                    echo "User has been updated";
                }
                else
                {
                    echo "User has not been updated";
                }
            }
        }
        else
        {
            echo "This username has already taken. Please try different username";
        }
    }
    else
    {
        // Prevent duplication of Email
        $pre_query2 = "SELECT id FROM admins WHERE email='$email'";
        $result2 = mysqli_query($conn,$pre_query2);
        $rows2 = mysqli_num_rows($result2);
        if($rows2>0)
        {
            $data2 = mysqli_fetch_assoc($result2);
            $existed_admin_id = $data2['id'];
            if($existed_admin_id===$id)
            {
                $query = "UPDATE admins SET name='$name', username='$username',
                email='$email', role='$role', type='$type' WHERE id='$id'";
                $exec = mysqli_query($conn,$query);
                if($exec==true)
                {
                    echo "User has been updated";
                }
                else
                {
                    echo "User has not been updated";
                }
            }
            else
            {
                echo "This email has already taken by another user. Please try different email";
            }
        }
        else
        {
            $query = "UPDATE admins SET name='$name', username='$username',
            email='$email', role='$role', type='$type' WHERE id='$id'";
            $exec = mysqli_query($conn,$query);
            if($exec==true)
            {
                echo "User has been updated";
            }
            else
            {
                echo "User has not been updated";
            }
        }
    }
}
?>